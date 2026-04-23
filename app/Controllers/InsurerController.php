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
        $name = trim((string) ($_POST['name'] ?? ''));
        $contactEmail = trim((string) ($_POST['contact_email'] ?? ''));

        if ($name === '') {
            $this->redirectWithMessage('/insurers', 'error', 'El nombre de la compañía es obligatorio.');
        }

        if ($contactEmail !== '' && !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithMessage('/insurers', 'error', 'El email de contacto no es válido.');
        }

        try {
            (new Insurer($this->config))->create($_POST);
            $this->redirectWithMessage('/insurers', 'success', 'Compañía creada correctamente.');
        } catch (\Throwable $e) {
            $this->redirectWithMessage('/insurers', 'error', 'No se pudo crear la compañía.');
        }
    }
}
