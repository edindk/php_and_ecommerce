<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Customer;
use App\Models\Invoice;
use GuzzleHttp\Client;
use PHPUnit\TextUI\Help;

class HelperController
{
    public static $tempData = [
        array('date' => 'Januar', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'Februar', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'Marts', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'April', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'Maj', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'Juni', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'Juli', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'August', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'September', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'Oktober', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'November', 'total' => 0, 'numbOfOrders' => 0),
        array('date' => 'December', 'total' => 0, 'numbOfOrders' => 0),

    ];

    public static function GetMonthText($month)
    {
        $monthToReturn = '';
        switch ($month) {
            case '01':
                $monthToReturn = 'Januar';
                break;
            case '02':
                $monthToReturn = 'Februar';
                break;
            case '03':
                $monthToReturn = 'Marts';
                break;
            case '04':
                $monthToReturn = 'April';
                break;
            case '05':
                $monthToReturn = 'Maj';
                break;
            case '06':
                $monthToReturn = 'Juni';
                break;
            case '07':
                $monthToReturn = 'Juli';
                break;
            case '08':
                $monthToReturn = 'August';
                break;
            case '09':
                $monthToReturn = 'September';
                break;
            case '10':
                $monthToReturn = 'Oktober';
                break;
            case '11':
                $monthToReturn = 'November';
                break;
            case '12':
                $monthToReturn = 'December';
                break;
        }
        return $monthToReturn;
    }

    public static function GenerateDataWithTotalAndDate()
    {
        $invoices = Invoice::all();
        $tempData = HelperController::$tempData;

        foreach ($invoices as $invoice) {
            $date = new \DateTime($invoice->paidDate);
            $date = date_format($date, 'm');
            $date = HelperController::GetMonthText($date);

            $index = array_search($date, array_column($tempData, 'date'));

            if ($index || $index == 0) {
                $tempData[$index]['total'] += $invoice->total;
                $tempData[$index]['numbOfOrders'] += 1;
            }
        }

        $dataToReturn = ['dates' => array(), 'total' => array(), 'numbOfOrders' => array()];

        foreach ($tempData as $data) {
            array_push($dataToReturn['dates'], $data['date']);
            array_push($dataToReturn['total'], $data['total']);
            array_push($dataToReturn['numbOfOrders'], $data['numbOfOrders']);
        }

        return $dataToReturn;
    }

    public static function GetLatLng()
    {
        $customers = Customer::all();
        $arrOfLatLng = [];

        foreach ($customers as $customer) {
            $city = City::where('zipCode', $customer->zipCode)->first();

            if ($city->lat == null && $city->lng == null) {
                $http = new Client;
                $response = $http->get('https://nominatim.openstreetmap.org/search.php?q=' . $city->city . '&format=jsonv2');
                $response = json_decode($response->getBody()->getContents());

                foreach ($response as $item) {
                    if (strpos($item->display_name, 'Danmark')) {
                        $lat = $item->lat;
                        $lng = $item->lon;

                        $city->lat = $lat;
                        $city->lng = $lng;

                        $city->save();
                    }
                }
            }
            array_push($arrOfLatLng, array('cityName' => $city->city, 'zipCode' => $city->zipCode, 'lat' => $city->lat, 'lng' => $city->lng));
        }

        return array_unique($arrOfLatLng, SORT_REGULAR);
    }
}
