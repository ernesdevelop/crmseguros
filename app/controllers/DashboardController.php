<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\Policy;
use App\Models\Insurer;

class DashboardController extends Controller
{
    public function index(): void
    {
        $clients = new Client($this->config);
        $policies = new Policy($this->config);
        $insurers = new Insurer($this->config);

        $this->view('dashboard/index', [
            'clientsCount' => count($clients->all()),
            'policiesCount' => count($policies->all()),
            'insurersCount' => count($insurers->all()),
            'renewals' => $policies->upcomingRenewals(45),
        ]);
    }
}
