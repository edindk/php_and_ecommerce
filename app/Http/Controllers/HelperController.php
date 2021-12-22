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
        1 => array('date' => 'Januar', 'total' => 0, 'numbOfOrders' => 0),
        2 => array('date' => 'Februar', 'total' => 0, 'numbOfOrders' => 0),
        3 => array('date' => 'Marts', 'total' => 0, 'numbOfOrders' => 0),
        4 => array('date' => 'April', 'total' => 0, 'numbOfOrders' => 0),
        5 => array('date' => 'Maj', 'total' => 0, 'numbOfOrders' => 0),
        6 => array('date' => 'Juni', 'total' => 0, 'numbOfOrders' => 0),
        7 => array('date' => 'Juli', 'total' => 0, 'numbOfOrders' => 0),
        8 => array('date' => 'August', 'total' => 0, 'numbOfOrders' => 0),
        9 => array('date' => 'September', 'total' => 0, 'numbOfOrders' => 0),
        10 => array('date' => 'Oktober', 'total' => 0, 'numbOfOrders' => 0),
        11 => array('date' => 'November', 'total' => 0, 'numbOfOrders' => 0),
        12 => array('date' => 'December', 'total' => 0, 'numbOfOrders' => 0),

    ];

    public static function GenerateDataWithTotalAndDate()
    {
        $invoices = Invoice::all();
        $dataToReturn = HelperController::$tempData;

        foreach ($invoices as $invoice) {
            $date = new \DateTime($invoice->paidDate);
            $date = date_format($date, 'm');

            $dataToReturn[(int)$date]['total'] += $invoice->total;
            $dataToReturn[(int)$date]['numbOfOrders'] += 1;
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
