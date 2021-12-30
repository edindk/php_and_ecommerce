<?php

namespace App\Http\Controllers;

use App\Events\ShowDashboardData;

class DashboardController extends Controller
{

    public function index()
    {
        $chartData = HelperController::generateDataWithTotalAndDate();
        $latLngData = HelperController::getLatLng();
        return view('dashboard', ['chartData' => $chartData, 'latLngData' => $latLngData]);
    }

    public function broadcast()
    {
        $chartData = HelperController::updateDataWithTotalAndDate();
        $latLngData = HelperController::updateLatLng();

        event(new ShowDashboardData($chartData, $latLngData));
    }
}

