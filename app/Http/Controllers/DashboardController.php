<?php

namespace App\Http\Controllers;

use App\Events\ShowDashboardData;

class DashboardController extends Controller
{

    public function index()
    {
        return view('dashboard');
    }

    public function broadcast()
    {
        $chartData = HelperController::GenerateDataWithTotalAndDate();
        $latLngData = HelperController::GetLatLng();

        event(new ShowDashboardData($chartData, $latLngData));
    }
}

