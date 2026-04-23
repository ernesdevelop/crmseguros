<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index(): void
    {
        $model = new User($this->config);
        $this->view('users/index', ['users' => $model->all()]);
    }

    public function store(): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $role = (string) ($_POST['role'] ?? '');

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithMessage('/users', 'error', 'Nombre y email válido son obligatorios.');
        }

        if (!in_array($role, ['admin', 'operador'], true)) {
            $this->redirectWithMessage('/users', 'error', 'Rol de usuario inválido.');
        }

        try {
            (new User($this->config))->create($_POST);
            $this->redirectWithMessage('/users', 'success', 'Usuario creado correctamente.');
        } catch (\Throwable $e) {
            $this->redirectWithMessage('/users', 'error', 'No se pudo crear el usuario. Verificá que el email no esté duplicado.');
        }
    }
}
