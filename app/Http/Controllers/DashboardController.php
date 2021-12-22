<?php

namespace App\Http\Controllers;

use App\Events\ShowDashboardData;
use App\Models\Customer;
use App\Models\Invoice;


class DashboardController extends Controller
{

    public function index()
    {
        $chartData = HelperController::GenerateDataWithTotalAndDate();
        $latLngData = HelperController::GetLatLng();

        event(new ShowDashboardData($chartData, $latLngData));
        return view('dashboard');
    }
}

