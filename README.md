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

## Healthcheck
- Endpoint: `/healthcheck.php`
- Respuesta HTTP:
  - `200` cuando todo está bien.
  - `503` cuando falla algún chequeo.
- Verifica:
  - Versión mínima de PHP.
  - Archivos críticos del proyecto.
  - Configuración DB obligatoria.
  - Conexión a MySQL.
  - Tablas base (`users`, `clients`, `insurers`, `policies`).
- Formatos:
  - JSON por defecto.
  - HTML con `?format=html` (ejemplo: `/healthcheck.php?format=html`).

## Mensajes de validación
- Las acciones de alta/edición/baja muestran feedback visual global (`success`, `error`, `info`).
- Se validan datos clave antes de escribir en base de datos (campos obligatorios, email, rangos y estados permitidos).

## Protección CSRF
- Todos los formularios `POST` incluyen token CSRF (`_csrf`).
- El front controller valida el token antes de ejecutar la acción.
- Si el token falta o es inválido, la operación se rechaza y se muestra un mensaje.

## Login y permisos
- Login: `/login`
- Logout: botón **Salir** en la barra superior.
- El módulo `/admin/tools` ahora requiere sesión iniciada con rol `admin`.
- La gestión de usuarios `/users` también requiere sesión admin.
- El link **Admin** solo se muestra cuando el usuario autenticado tiene rol `admin`.
- Al crear usuarios desde `/users`, ahora se solicita contraseña.

## Manejo de errores
- Página amigable `404` para rutas no existentes.
- Página amigable `500` para errores internos de la aplicación.

## Backup de base de datos
1. Ejecutar:
   ```bash
   bash database/backup.sh
   ```
2. Opcional: indicar carpeta destino:
   ```bash
   bash database/backup.sh /ruta/de/backups
   ```
3. Variables opcionales para sobreescribir credenciales:
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`

### Backup desde UI
- Ir a `/admin/tools`.
- Botón: **Descargar backup (.sql.gz)**.
- En ese mismo panel se ejecuta y muestra el `healthcheck` (estado + detalle por check).

## Migración necesaria (si la base ya existía)
Si tu tabla `users` fue creada antes de agregar contraseñas, ejecutá:

```bash
mysql -u TU_USUARIO -p TU_BASE < database/migrations/2026-04-23_add_users_password_hash.sql
```
