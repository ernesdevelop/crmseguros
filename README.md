# CRM Seguros (PHP MVC)

Sistema CRM básico para brokers de seguros desarrollado en **PHP + MySQL + CSS** bajo patrón **MVC**.

## Funcionalidades
- Gestión de usuarios.
- ABM de clientes.
- ABM de compañías de seguros.
- ABM de pólizas.
- Vista de vencimientos/renovaciones próximas.

## Requisitos
- PHP 8.1+
- MySQL 8+
- Extensión PDO MySQL habilitada

## Instalación rápida
1. Crear base de datos y tablas:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
2. Copiar configuración:
   ```bash
   cp config/config.example.php config/config.php
   ```
3. Ajustar credenciales en `config/config.php`.
4. Levantar servidor:
   ```bash
   php -S localhost:8000 -t public
   ```
5. Abrir `http://localhost:8000`.

## Estructura
- `app/core`: núcleo MVC (Router, Controller base, DB).
- `app/models`: acceso a datos.
- `app/controllers`: controladores por módulo.
- `app/views`: vistas.
- `public`: front controller y assets.

