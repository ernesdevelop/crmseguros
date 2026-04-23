<?php
declare(strict_types=1);

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/../app/Core/Healthcheck.php';
}

$configPath = __DIR__ . '/../config/config.php';
$config = file_exists($configPath) ? (require $configPath) : [];
if (!is_array($config)) {
    $config = [];
}

$response = \App\Core\Healthcheck::run($config);
http_response_code(($response['status'] ?? 'error') === 'ok' ? 200 : 503);

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
