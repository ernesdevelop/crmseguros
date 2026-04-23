<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(): void
    {
        $model = new Client($this->config);
        $editClient = null;
        if (isset($_GET['edit'])) {
            $editClient = $model->find((int) $_GET['edit']);
        }
        $this->view('clients/index', ['clients' => $model->all(), 'editClient' => $editClient]);
    }

    public function store(): void
    {
        (new Client($this->config))->create($_POST);
        $this->redirect('/clients');
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        (new Client($this->config))->update($id, $_POST);
        $this->redirect('/clients');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        (new Client($this->config))->delete($id);
        $this->redirect('/clients');
    }
}
