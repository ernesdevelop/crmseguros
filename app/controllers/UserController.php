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
        $model = new User($this->config);
        $model->create($_POST);
        $this->redirect('/users');
    }
}
