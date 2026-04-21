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
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;

        if ($startDate && $endDate && $endDate < $startDate) {
            $this->redirect('/policies');
        }

        (new Policy($this->config))->create($_POST);
        $this->redirect('/policies');
    }
}
