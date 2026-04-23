<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Insurer;

class InsurerController extends Controller
{
    public function index(): void
    {
        $model = new Insurer($this->config);
        $this->view('insurers/index', ['insurers' => $model->all()]);
    }

    public function store(): void
    {
        (new Insurer($this->config))->create($_POST);
        $this->redirect('/insurers');
    }
}
