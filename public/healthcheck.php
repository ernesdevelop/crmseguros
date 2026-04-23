<?php
declare(strict_types=1);

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
    __DIR__ . '/index.php',
    __DIR__ . '/../config/config.php',
    __DIR__ . '/../app/Core/Router.php',
    __DIR__ . '/../app/Core/Controller.php',
    __DIR__ . '/../app/Core/Database.php',
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

$configPath = __DIR__ . '/../config/config.php';
$config = [];
if (file_exists($configPath)) {
    $loaded = require $configPath;
    if (is_array($loaded)) {
        $config = $loaded;
    }
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
$response = [
    'status' => $allOk ? 'ok' : 'error',
    'timestamp' => date('c'),
    'duration_ms' => $elapsedMs,
    'checks' => $checks,
];

http_response_code($allOk ? 200 : 503);

$format = strtolower((string) ($_GET['format'] ?? ''));
$accept = strtolower((string) ($_SERVER['HTTP_ACCEPT'] ?? ''));
$wantsHtml = $format === 'html' || ($format !== 'json' && PHP_SAPI !== 'cli' && str_contains($accept, 'text/html'));

if ($wantsHtml) {
    header('Content-Type: text/html; charset=utf-8');
    echo renderHealthcheckHtml($response);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

function renderHealthcheckHtml(array $response): string
{
    $status = (string) ($response['status'] ?? 'error');
    $statusClass = $status === 'ok' ? 'ok' : 'error';
    $statusText = strtoupper($status);
    $timestamp = (string) ($response['timestamp'] ?? '');
    $duration = (string) ($response['duration_ms'] ?? '0');
    $rows = [];

    foreach (($response['checks'] ?? []) as $name => $check) {
        $ok = !empty($check['ok']);
        $summary = $ok ? 'OK' : 'ERROR';
        $details = htmlspecialchars(json_encode($check, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');
        $rows[] = sprintf(
            '<tr><td>%s</td><td class="%s">%s</td><td><code>%s</code></td></tr>',
            htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'),
            $ok ? 'ok' : 'error',
            $summary,
            $details
        );
    }

    return '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcheck CRM Seguros</title>
    <style>
        :root { color-scheme: light; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fb;
            color: #10253f;
        }
        .wrap { max-width: 980px; margin: 0 auto; padding: 20px; }
        .card {
            background: #fff;
            border: 1px solid #d7e2ef;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 8px 20px rgba(16, 37, 63, 0.06);
        }
        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .status.ok { background: #eaf8ef; color: #196a37; }
        .status.error { background: #fdeceb; color: #8a2320; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td {
            border-bottom: 1px solid #e6edf6;
            padding: 10px 8px;
            vertical-align: top;
            text-align: left;
        }
        th { background: #f8fbff; color: #4f6480; }
        td.ok { color: #196a37; font-weight: 700; }
        td.error { color: #8a2320; font-weight: 700; }
        code {
            font-size: 12px;
            white-space: pre-wrap;
            word-break: break-word;
            color: #31445f;
            background: #f4f8ff;
            border: 1px solid #dbe7fb;
            padding: 6px;
            border-radius: 8px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="status ' . $statusClass . '">' . htmlspecialchars($statusText, ENT_QUOTES, 'UTF-8') . '</div>
            <h1>Healthcheck CRM Seguros</h1>
            <p>Timestamp: ' . htmlspecialchars($timestamp, ENT_QUOTES, 'UTF-8') . '</p>
            <p>Duracion: ' . htmlspecialchars($duration, ENT_QUOTES, 'UTF-8') . ' ms</p>
            <table>
                <thead>
                    <tr><th>Check</th><th>Estado</th><th>Detalle</th></tr>
                </thead>
                <tbody>' . implode('', $rows) . '</tbody>
            </table>
        </div>
    </div>
</body>
</html>';
}
