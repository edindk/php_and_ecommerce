<?php

namespace App\Http\Controllers;

use App\Models\City;
use GuzzleHttp\Client;

class HelperController
{
    /**
     * @param $month
     * @return string
     */
    public static function GetMonthText($month)
    {
        $monthToReturn = '';
        // switch statement der returnerer måned ud fra månedsnummer.
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

    /**
     * @param $data
     * @return mixed
     */
    public static function SortData($data)
    {
        // Tager en kollektionen og funktion i parameter.
        usort($data, function ($a, $b) {
            // Hvis datoen er det samme returneres 0
            if ($a['date'] == $b['date']) {
                return 0;
            }
            // Hvis $b['date'] er større end $a['date'] returneres -1, hvis omvendt returneres 1.
            return ($a['date'] < $b['date']) ? -1 : 1; // ternary operator || conditional operator
        });
        return $data;
    }

    /**
     * @param $month
     * @param $data
     * @return bool
     */
    public static function SearchForMonth($month, $data)
    {
        // monthExist sættes til false som standard.
        $monthExist = false;

        // Loopes igennem data kollektionen.
        foreach ($data as $key => $val) {
            // Hvis månedsnummer findes sættes monthExist til true.
            if ($month == (int)$val['date']) {
                $monthExist = true;
            }
        }

        // Returnerer monthExist.
        return $monthExist;
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function AddRemainingMonths($data)
    {
        // Kollektionen der har månedsnumre fra 1-12.
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        // Looper igennem months kollektionen.
        foreach ($months as $month) {
            // Tjekker op på om månedsnumret findes i data kollektionen.
            $monthExist = HelperController::SearchForMonth($month, $data);

            // Hvis monthExist er false indsættes månedsnummer og en total på 0 i data kollektioen.
            if (!$monthExist) {
                array_push($data, array('date' => $month, 'total' => 0));
            }
        }

        // Data kollektionen sorteres.
        $data = HelperController::SortData($data);

        // Loopes igennem data kollektionen
        for ($i = 0; $i < count($data); $i++) {
            // Midlertidig opbevaring af element i lokal variabel.
            $element = $data[$i];

            // Henter måned ud fra månedsnummer. 1 -> Januar, 2 -> Februar osv.
            $monthText = HelperController::GetMonthText($data[$i]['date']);

            // data property sættes til den nye værdi.
            $element['date'] = $monthText;

            // Elementet opdateres med det nye element.
            $data[$i] = $element;
        }

        // Returnerer data kollektionen.
        return $data;
    }

    /**
     * @param $invoices
     * @return mixed
     * @throws \Exception
     */
    public static function GenerateDataWithTotalAndDate($invoices)
    {
        // Oprettelse af en tom kollektion.
        $data = [];

        // Loopes igennem invoices.
        foreach ($invoices as $invoice) {
            // Opretetlse af et nyt DateTime objekt ved brug af paidDate på invoice.
            $date = new \DateTime($invoice->paidDate);

            // DateTime objektet indsættes i kollektionen med formatet 'm'.
            array_push($data, array('date' => date_format($date, 'm')));
        }

        // array_unique og array_values kaldes på kolletkionen for fjernelse af duplikater
        // samt reindeksering.
        $data = array_values(array_unique($data, SORT_REGULAR));

        // Loopes igennem data kollektionen.
        for ($i = 0; $i < count($data); $i++) {
            // Variabel indeholdende total salg i DKK.
            $total = 0;

            // Loopes igennem invoices.
            foreach ($invoices as $invoice) {
                // Oprettelse af nyt DateTime objekt.
                $date = new \DateTime($invoice->paidDate);

                // Hvis datoen er det samme som den fra data kollektionen
                // eksekveres indholdet af if statement.
                if ($data[$i]['date'] == date_format($date, 'm')) {
                    // Totalen fra invoices lægges oveni total.
                    $total += $invoice->total;

                    // Total property i data kollektionen opdateres til den nye værdi.
                    $data[$i]['total'] = $total;
                }
            }
        }
        // Kald til AddRemainingMonths hvor data kollektionen sendes med i parameteren.
        return HelperController::AddRemainingMonths($data);
    }

    /**
     * @param $invoices
     * @return mixed
     * @throws \Exception
     */
    public static function GenerateNumbOfOrdersMonthly($invoices)
    {
        // Oprettelse af en tom kollektion.
        $data = [];

        // Loopes igennem invoices.
        foreach ($invoices as $invoice) {
            // Opretetlse af et nyt DateTime objekt ved brug af paidDate på invoice.
            $date = new \DateTime($invoice->paidDate);

            // DateTime objektet indsættes i kollektionen med formatet 'm'.
            array_push($data, array('date' => date_format($date, 'm')));
        }

        // array_unique og array_values kaldes på kolletkionen for fjernelse af duplikater
        // samt reindeksering.
        $data = array_values(array_unique($data, SORT_REGULAR));

        // Loopes igennem data kollektionen.
        for ($i = 0; $i < count($data); $i++) {
            // Variabel indeholdende totale ordre.
            $totalOrders = 0;

            // Loopes igennem invoices.
            foreach ($invoices as $invoice) {
                // Oprettelse af nyt DateTime objekt.
                $date = new \DateTime($invoice->paidDate);

                // Hvis datoen er det samme som den fra data kollektionen
                // eksekveres indholdet af if statement.
                if ($data[$i]['date'] == date_format($date, 'm')) {
                    // 1 lægges oveni totalOrders.
                    $totalOrders += 1;

                    // Total property i data kollektionen opdateres til den nye værdi.
                    $data[$i]['total'] = $totalOrders;
                }
            }
        }
        // Kald til AddRemainingMonths hvor data kollektionen sendes med i parameteren.
        return HelperController::AddRemainingMonths($data);
    }

    /**
     * @param $customers
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function GetLatLng($customers)
    {
        // Oprettelse af et nyt Client objekt.
        $http = new Client;

        // Oprettelse af en tom kollektion.
        $arrOfLatLng = [];

        // Loopes igennem customers.
        foreach ($customers as $customer) {
            // Henter bynavn ud fra post nr.
            $city = City::where('zipCode', $customer->zipCode)->first();

            // Foretager et api kald til openstreetmap for at få længdegrader og breddegrader.
            $response = $http->get('https://nominatim.openstreetmap.org/search.php?q=' . $city->city . '&format=jsonv2');
            $response = json_decode($response->getBody()->getContents());

            // Der loopes igennem response fra openstreetmap.
            foreach ($response as $item) {
                // Hvis display_name er Danmark, sættes lat og lng.
                if (strpos($item->display_name, 'Danmark')) {
                    $lat = $item->lat;
                    $lng = $item->lon;
                }
            }

            // Lat,  lng, bynavn og post nr. indsættes i arrOfLatLng.
            array_push($arrOfLatLng, array('cityName' => $city->city, 'zipCode' => $city->zipCode, 'lat' => $lat, 'lng' => $lng));
        }

        // array_unique kaldes på kollektionen og derefter array_values for at reindeksere kollektionen.
        // Kollektionen returneres.
        return array_values(array_unique($arrOfLatLng, SORT_REGULAR));
    }
}
