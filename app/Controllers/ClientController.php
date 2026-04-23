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
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $document = trim((string) ($_POST['document'] ?? ''));
        $status = (string) ($_POST['status'] ?? 'activo');

        if ($fullName === '' || $document === '') {
            $this->redirectWithMessage('/clients', 'error', 'Nombre y documento son obligatorios.');
        }

        if (!in_array($status, ['activo', 'inactivo'], true)) {
            $this->redirectWithMessage('/clients', 'error', 'Estado de cliente inválido.');
        }

        try {
            (new Client($this->config))->create($_POST);
            $this->redirectWithMessage('/clients', 'success', 'Cliente creado correctamente.');
        } catch (\Throwable $e) {
            $this->redirectWithMessage('/clients', 'error', 'No se pudo crear el cliente.');
        }
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $document = trim((string) ($_POST['document'] ?? ''));
        $status = (string) ($_POST['status'] ?? 'activo');

        if ($id <= 0 || $fullName === '' || $document === '') {
            $this->redirectWithMessage('/clients', 'error', 'Datos inválidos para actualizar cliente.');
        }

        if (!in_array($status, ['activo', 'inactivo'], true)) {
            $this->redirectWithMessage('/clients', 'error', 'Estado de cliente inválido.');
        }

        try {
            (new Client($this->config))->update($id, $_POST);
            $this->redirectWithMessage('/clients', 'success', 'Cliente actualizado correctamente.');
        } catch (\Throwable $e) {
            $this->redirectWithMessage('/clients', 'error', 'No se pudo actualizar el cliente.');
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirectWithMessage('/clients', 'error', 'ID de cliente inválido.');
        }

        try {
            (new Client($this->config))->delete($id);
            $this->redirectWithMessage('/clients', 'success', 'Cliente eliminado correctamente.');
        } catch (\Throwable $e) {
            $this->redirectWithMessage('/clients', 'error', 'No se pudo eliminar el cliente.');
        }
    }
}
