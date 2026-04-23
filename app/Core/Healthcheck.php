<?php
namespace App\Core;

use PDO;
use Throwable;

class Healthcheck
{
    public static function run(array $config): array
    {
        $startedAt = microtime(true);
        $checks = [];
        $allOk = true;

        $checks['php_version'] = [
            'ok' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'current' => PHP_VERSION,
            'required' => '>=8.1.0',
        ];
        if (!$checks['php_version']['ok']) {
            $allOk = false;
        }

        $criticalFiles = [
            __DIR__ . '/../../public/index.php',
            __DIR__ . '/../../config/config.php',
            __DIR__ . '/Router.php',
            __DIR__ . '/Controller.php',
            __DIR__ . '/Database.php',
        ];

        $missingFiles = [];
        foreach ($criticalFiles as $file) {
            if (!file_exists($file)) {
                $missingFiles[] = $file;
            }
        }

        $checks['critical_files'] = [
            'ok' => count($missingFiles) === 0,
            'missing' => $missingFiles,
        ];
        if (!$checks['critical_files']['ok']) {
            $allOk = false;
        }

        $dbConfig = $config['db'] ?? [];
        $requiredDbKeys = ['host', 'port', 'dbname', 'user', 'pass', 'charset'];
        $missingDbKeys = [];
        foreach ($requiredDbKeys as $key) {
            if (!array_key_exists($key, $dbConfig)) {
                $missingDbKeys[] = $key;
            }
        }

        $checks['db_config'] = [
            'ok' => count($missingDbKeys) === 0,
            'missing_keys' => $missingDbKeys,
            'database' => $dbConfig['dbname'] ?? null,
        ];
        if (!$checks['db_config']['ok']) {
            $allOk = false;
        }

        if ($checks['db_config']['ok']) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    $dbConfig['host'],
                    $dbConfig['port'],
                    $dbConfig['dbname'],
                    $dbConfig['charset']
                );

                $pdo = new PDO($dsn, (string) $dbConfig['user'], (string) $dbConfig['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                $checks['db_connection'] = ['ok' => true];

                $requiredTables = ['users', 'clients', 'insurers', 'policies'];
                $stmt = $pdo->prepare(
                    'SELECT table_name
                     FROM information_schema.tables
                     WHERE table_schema = :schema AND table_name IN ("users","clients","insurers","policies")'
                );
                $stmt->execute(['schema' => (string) $dbConfig['dbname']]);
                $existingTables = array_map(
                    static function (array $row): string {
                        return (string) ($row['table_name'] ?? $row['TABLE_NAME'] ?? '');
                    },
                    $stmt->fetchAll()
                );
                $existingTables = array_values(array_filter($existingTables, static fn(string $name): bool => $name !== ''));

                $missingTables = array_values(array_diff($requiredTables, $existingTables));
                $checks['db_tables'] = [
                    'ok' => count($missingTables) === 0,
                    'missing' => $missingTables,
                ];

                if (!$checks['db_tables']['ok']) {
                    $allOk = false;
                }
            } catch (Throwable $e) {
                $checks['db_connection'] = [
                    'ok' => false,
                    'error' => $e->getMessage(),
                ];
                $allOk = false;
            }
        }

        $elapsedMs = (int) round((microtime(true) - $startedAt) * 1000);

        return [
            'status' => $allOk ? 'ok' : 'error',
            'timestamp' => date('c'),
            'duration_ms' => $elapsedMs,
            'checks' => $checks,
        ];
    }
}
