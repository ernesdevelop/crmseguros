<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Policy;
use App\Models\Client;
use App\Models\Insurer;

class PolicyController extends Controller
{
    public function index(): void
    {
        $policyModel = new Policy($this->config);
        $clientModel = new Client($this->config);
        $insurerModel = new Insurer($this->config);

        $this->view('policies/index', [
            'policies' => $policyModel->all(),
            'clients' => $clientModel->all(),
            'insurers' => $insurerModel->all(),
        ]);
    }

    public function store(): void
    {
        $policyNumber = trim((string) ($_POST['policy_number'] ?? ''));
        $clientId = (int) ($_POST['client_id'] ?? 0);
        $insurerId = (int) ($_POST['insurer_id'] ?? 0);
        $coverageType = trim((string) ($_POST['coverage_type'] ?? ''));
        $startDate = (string) ($_POST['start_date'] ?? '');
        $endDate = (string) ($_POST['end_date'] ?? '');
        $premium = $_POST['premium'] ?? null;
        $status = (string) ($_POST['status'] ?? 'vigente');

        if ($policyNumber === '' || $coverageType === '' || $startDate === '' || $endDate === '') {
            $this->redirectWithMessage('/policies', 'error', 'Completá todos los campos obligatorios de la póliza.');
        }

        if ($clientId <= 0 || $insurerId <= 0) {
            $this->redirectWithMessage('/policies', 'error', 'Seleccioná cliente y compañía válidos.');
        }

        if ($endDate < $startDate) {
            $this->redirectWithMessage('/policies', 'error', 'La fecha de fin no puede ser menor a la fecha de inicio.');
        }

        if (!is_numeric((string) $premium) || (float) $premium < 0) {
            $this->redirectWithMessage('/policies', 'error', 'La prima debe ser un número válido mayor o igual a 0.');
        }

        if (!in_array($status, ['vigente', 'vencida', 'cancelada'], true)) {
            $this->redirectWithMessage('/policies', 'error', 'Estado de póliza inválido.');
        }

        try {
            (new Policy($this->config))->create($_POST);
            $this->redirectWithMessage('/policies', 'success', 'Póliza creada correctamente.');
        } catch (\Throwable $e) {
            $this->redirectWithMessage('/policies', 'error', 'No se pudo crear la póliza. Verificá que el número no esté duplicado.');
        }
    }
}
