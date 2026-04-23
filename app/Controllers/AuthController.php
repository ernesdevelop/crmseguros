<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $current = $this->currentUser();
        if ($current !== null) {
            $this->redirect('/');
        }

        $this->view('auth/login');
    }

    public function login(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            $this->redirectWithMessage('/login', 'error', 'Ingresá email y contraseña válidos.');
        }

        $user = (new User($this->config))->verifyCredentials($email, $password);
        if ($user === null) {
            $this->redirectWithMessage('/login', 'error', 'Credenciales inválidas.');
        }

        $this->loginUser($user);
        $this->redirectWithMessage('/', 'success', 'Sesión iniciada correctamente.');
    }

    public function logout(): void
    {
        $this->logoutUser();
        $this->redirectWithMessage('/login', 'info', 'Sesión cerrada.');
    }
}
