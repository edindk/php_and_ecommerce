<?php

namespace App\Http\Controllers;

use App\Models\City;
use GuzzleHttp\Client;

class HelperController
{
    public static function GetMonthText($month)
    {
        $monthToReturn = '';
        switch ($month) {
            case 1:
                $monthToReturn = 'Januar';
                break;
            case 2:
                $monthToReturn = 'Februar';
                break;
            case 3:
                $monthToReturn = 'Marts';
                break;
            case 4:
                $monthToReturn = 'April';
                break;
            case 5:
                $monthToReturn = 'Maj';
                break;
            case 6:
                $monthToReturn = 'Juni';
                break;
            case 7:
                $monthToReturn = 'Juli';
                break;
            case 8:
                $monthToReturn = 'August';
                break;
            case 9:
                $monthToReturn = 'September';
                break;
            case 10:
                $monthToReturn = 'Oktober';
                break;
            case 11:
                $monthToReturn = 'November';
                break;
            case 12:
                $monthToReturn = 'December';
                break;
        }
        return $monthToReturn;
    }

    public static function SortData($data)
    {
        usort($data, function ($a, $b) {
            if ($a['date'] == $b['date']) {
                return 0;
            }
            return ($a['date'] < $b['date']) ? -1 : 1; // ternary operator || conditional operator
        });
        return $data;
    }

    public static function SearchForMonth($month, $data)
    {
        $monthExist = false;
        foreach ($data as $key => $val) {
            if ($month == (int)$val['date']) {
                $monthExist = true;
            }
        }
        return $monthExist;
    }

    public static function AddRemainingMonths($data)
    {
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        foreach ($months as $month) {
            $monthExist = HelperController::SearchForMonth($month, $data);
            if (!$monthExist) {
                array_push($data, array('date' => $month, 'total' => 0));
            }
        }
        $data = HelperController::SortData($data);

        for ($i = 0; $i < count($data); $i++) {
            $element = $data[$i];

            $monthText = HelperController::GetMonthText($data[$i]['date']);
            $element['date'] = $monthText;

            $data[$i] = $element;
        }
        return $data;
    }

    public static function GenerateDataWithTotalAndDate($invoices)
    {
        $data = [];
        foreach ($invoices as $invoice) {
            $date = new \DateTime($invoice->paidDate);
            array_push($data, array('date' => date_format($date, 'm')));
        }

        $data = array_values(array_unique($data, SORT_REGULAR));

        for ($i = 0; $i < count($data); $i++) {
            $total = 0;
            foreach ($invoices as $invoice) {
                $date = new \DateTime($invoice->paidDate);
                if ($data[$i]['date'] == date_format($date, 'm')) {
                    $total += $invoice->total;
                    $data[$i]['total'] = $total;
                }
            }
        }
        return HelperController::AddRemainingMonths($data);
    }

    public static function GenerateNumbOfOrdersMonthly($invoices)
    {
        $data = [];
        foreach ($invoices as $invoice) {
            $date = new \DateTime($invoice->paidDate);
            array_push($data, array('date' => date_format($date, 'm')));
        }

        $data = array_values(array_unique($data, SORT_REGULAR));

        for ($i = 0; $i < count($data); $i++) {
            $totalOrders = 0;
            foreach ($invoices as $invoice) {
                $date = new \DateTime($invoice->paidDate);
                if ($data[$i]['date'] == date_format($date, 'm')) {
                    $totalOrders += 1;
                    $data[$i]['total'] = $totalOrders;
                }
            }
        }
        return HelperController::AddRemainingMonths($data);
    }

    public static function GetLatLng($customers)
    {
        $http = new Client;
        $arrOfLatLng = [];

        foreach ($customers as $customer) {
            $city = City::where('zipCode', $customer->zipCode)->first();

            $response = $http->get('https://nominatim.openstreetmap.org/search.php?q=' . $city->city . '&format=jsonv2');
            $response = json_decode($response->getBody()->getContents());

            foreach ($response as $item) {
                if (strpos($item->display_name, 'Danmark')) {
                    $lat = $item->lat;
                    $lng = $item->lon;
                }
            }

            array_push($arrOfLatLng, array('cityName' => $city->city, 'zipCode' => $city->zipCode, 'lat' => $lat, 'lng' => $lng));
        }
        return array_values(array_unique($arrOfLatLng, SORT_REGULAR));
    }
}
