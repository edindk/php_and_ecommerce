<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;


class DashboardController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Henter alle invoices og kunder.
        $invoices = Invoice::all();
        $customers = Customer::all();

        // Kalder eksterne methoder, hvori hhv. invoices og customers sendes med i paramteren.
        $tempLineChartData = HelperController::GenerateDataWithTotalAndDate($invoices);
        $tempBarChartData = HelperController::GenerateNumbOfOrdersMonthly($invoices);
        $latLngData = HelperController::GetLatLng($customers);

        // Oprettelse af tomme kollektioner.
        $labelsForLineChart = [];
        $dataForLineChart = [];

        //Oprettelse af tomme kollektioner.
        $labelsForBarChart = [];
        $dataForBarChart = [];

        // Loopes igennem tempLineChartData.
        foreach ($tempLineChartData as $key => $val) {
            array_push($labelsForLineChart, $val['date']); // værdien med nøglen date indsættes i labelsForLineChart.
            array_push($dataForLineChart, $val['total']); // værdien med total indsættes i dataForLineChart.
        }

        // Loopes igennem tempBarChartData.
        foreach ($tempBarChartData as $key => $val) {
            array_push($labelsForBarChart, $val['date']); // værdien med nøglen date indsættes i labelsForBarChart.
            array_push($dataForBarChart, $val['total']); // værdien med nøglen total indsættes i dateForBarChart.
        }

        // Renderer dashboard siden indeholdende data for linechart, barchart og latLng.
        return view('dashboard', ['labelsForLineChart' => $labelsForLineChart, 'dataForLineChart' => $dataForLineChart, 'labelsForBarChart' => $labelsForBarChart, 'dataForBarChart' => $dataForBarChart, 'latLngData' => $latLngData]);
    }
}


