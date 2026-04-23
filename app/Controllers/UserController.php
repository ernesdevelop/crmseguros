<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $model = new User($this->config);
        $this->view('users/index', ['users' => $model->all()]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $role = (string) ($_POST['role'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithMessage('/users', 'error', 'Nombre y email válido son obligatorios.');
        }

        if (!in_array($role, ['admin', 'operador'], true)) {
            $this->redirectWithMessage('/users', 'error', 'Rol de usuario inválido.');
        }

        if (strlen($password) < 8) {
            $this->redirectWithMessage('/users', 'error', 'La contraseña debe tener al menos 8 caracteres.');
        }

        if ($password !== $passwordConfirm) {
            $this->redirectWithMessage('/users', 'error', 'La confirmación de contraseña no coincide.');
        }

        try {
            $payload = $_POST;
            $payload['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            (new User($this->config))->create($payload);
            $this->redirectWithMessage('/users', 'success', 'Usuario creado correctamente.');
        } catch (\Throwable $e) {
            $this->redirectWithMessage('/users', 'error', 'No se pudo crear el usuario. Verificá email único y estructura de la tabla users.');
        }
    }
}
