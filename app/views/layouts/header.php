<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName) ?></title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<header>
    <h1><?= htmlspecialchars($appName) ?></h1>
    <nav>
        <a href="/">Dashboard</a>
        <a href="/users">Usuarios</a>
        <a href="/clients">Clientes</a>
        <a href="/insurers">Compañías</a>
        <a href="/policies">Pólizas</a>
        <a href="/renewals">Vencimientos</a>
    </nav>
</header>
<main>
