<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Policy;

class RenewalController extends Controller
{
    public function index(): void
    {
        $rawDays = $_GET['days'] ?? null;
        $days = isset($rawDays) ? min(365, max(1, (int) $rawDays)) : 30;

        if (isset($rawDays) && ((int) $rawDays !== $days)) {
            $this->flash('info', 'El filtro de días se ajustó automáticamente al rango permitido (1 a 365).');
        }

        $renewals = (new Policy($this->config))->upcomingRenewals($days);
        $this->view('renewals/index', ['renewals' => $renewals, 'days' => $days]);
    }
}
