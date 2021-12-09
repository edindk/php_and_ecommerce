<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;

class DashboardController extends Controller
{

    public function index()
    {
        $invoices = Invoice::all();
        $customers = Customer::all();
        $tempLineChartData = HelperController::GenerateDataWithTotalAndDate($invoices);
        $tempBarChartData = HelperController::GenerateNumbOfOrdersMonthly($invoices);
        $latLngData = HelperController::GetLatLng($customers);

        $labelsForLineChart = [];
        $dataForLineChart = [];

        $labelsForBarChart = [];
        $dataForBarChart = [];

        foreach ($tempLineChartData as $key => $val) {
            array_push($labelsForLineChart, $val['date']);
            array_push($dataForLineChart, $val['total']);
        }

        foreach ($tempBarChartData as $key => $val) {
            array_push($labelsForBarChart, $val['date']);
            array_push($dataForBarChart, $val['total']);
        }

        return view('dashboard', ['labelsForLineChart' => $labelsForLineChart, 'dataForLineChart' => $dataForLineChart, 'labelsForBarChart' => $labelsForBarChart, 'dataForBarChart' => $dataForBarChart, 'latLngData' => $latLngData]);
    }
}


