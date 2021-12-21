<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;


class DashboardController extends Controller
{

    public function index()
    {
        $startTime = microtime(true);

        $invoices = Invoice::all();
        $customers = Customer::all();

        $totalAndDateData = HelperController::GenerateDataWithTotalAndDate($invoices);
        $latLngData = HelperController::GetLatLng($customers);

        dd("Elapsed time is: " . (microtime(true) - $startTime) . " seconds");
        return view('dashboard', ['totalAndDateData' => $totalAndDateData], ['latLngData' => $latLngData]);
    }
}

