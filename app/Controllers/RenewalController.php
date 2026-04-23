<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Policy;

class RenewalController extends Controller
{
    public function index(): void
    {
        $days = isset($_GET['days']) ? min(365, max(1, (int) $_GET['days'])) : 30;
        $renewals = (new Policy($this->config))->upcomingRenewals($days);
        $this->view('renewals/index', ['renewals' => $renewals, 'days' => $days]);
    }
}
