<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Healthcheck;

class AdminController extends Controller
{
    public function tools(): void
    {
        $this->requireAdmin();
        $health = Healthcheck::run($this->config);
        $this->view('admin/tools', ['health' => $health]);
    }

    public function downloadBackup(): void
    {
        $this->requireAdmin();
        $db = $this->config['db'] ?? [];
        $required = ['host', 'port', 'dbname', 'user', 'pass'];
        foreach ($required as $key) {
            if (!array_key_exists($key, $db)) {
                $this->redirectWithMessage('/admin/tools', 'error', 'Configuración DB incompleta para generar backup.');
            }
        }

        if (!function_exists('exec')) {
            $this->redirectWithMessage('/admin/tools', 'error', 'La función exec está deshabilitada en PHP.');
        }

        $tmpSql = tempnam(sys_get_temp_dir(), 'crm_backup_');
        if ($tmpSql === false) {
            $this->redirectWithMessage('/admin/tools', 'error', 'No se pudo crear archivo temporal para backup.');
        }

        $command = sprintf(
            'mysqldump -h %s -P %s -u %s --password=%s --single-transaction --routines --triggers %s > %s 2>&1',
            escapeshellarg((string) $db['host']),
            escapeshellarg((string) $db['port']),
            escapeshellarg((string) $db['user']),
            escapeshellarg((string) $db['pass']),
            escapeshellarg((string) $db['dbname']),
            escapeshellarg($tmpSql)
        );

        $output = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($tmpSql) || filesize($tmpSql) === 0) {
            @unlink($tmpSql);
            $this->redirectWithMessage('/admin/tools', 'error', 'No se pudo generar el backup. Revisá credenciales y permisos de MySQL.');
        }

        $sqlContents = file_get_contents($tmpSql);
        @unlink($tmpSql);

        if ($sqlContents === false) {
            $this->redirectWithMessage('/admin/tools', 'error', 'No se pudo leer el backup generado.');
        }

        $gz = gzencode($sqlContents, 9);
        unset($sqlContents);

        if ($gz === false) {
            $this->redirectWithMessage('/admin/tools', 'error', 'No se pudo comprimir el backup.');
        }

        $filename = sprintf(
            '%s_%s.sql.gz',
            preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $db['dbname']),
            date('Y-m-d_H-i-s')
        );

        header('Content-Description: File Transfer');
        header('Content-Type: application/gzip');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($gz));
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo $gz;
        exit;
    }
}
