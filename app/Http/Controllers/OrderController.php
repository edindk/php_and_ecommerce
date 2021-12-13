<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @return Invoice[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    private function getInvoices()
    {
        // Henter alle invoices fra DB.
        $invoices = Invoice::all();

        // Looper igennem invoices
        foreach ($invoices as $invoice) {
            // Opretter nyt DateTime objekt.
            $date = new \DateTime($invoice->paidDate);

            // Bestemmelse af formatet for date.
            $date = date_format($date, 'y-m-d');

            // Opdatering af paidDate property.
            $invoice->paidDate = $date;
        }
        return $invoices;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function showOrders()
    {
        // Henter alle invoices og kaldes toArray metoden.
        $invoices = $this->getInvoices()->toArray();

        // Renderer orders siden indeholdende data for invoices.
        return view('pages.orders', ['invoices' => $invoices]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function sortByNewestDate()
    {
        // Henter alle invoices og kaldes toArray metoden.
        $invoices = $this->getInvoices()->toArray();

        // Sortering af invoices.
        usort($invoices, function ($a, $b) {
            if ($a['paidDate'] == $b['paidDate']) {
                return 0;
            }
            return ($a['paidDate'] < $b['paidDate']) ? -1 : 1;
        });

        // Renderer orders siden indeholdende data for invoices.
        return view('pages.orders', ['invoices' => $invoices]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function sortByOldestDate()
    {
        // Henter alle invoices og kaldes toArray metoden.
        $invoices = $this->getInvoices()->toArray();

        // Sortering af invoices.
        usort($invoices, function ($a, $b) {
            if ($a['paidDate'] == $b['paidDate']) {
                return 0;
            }
            return ($a['paidDate'] > $b['paidDate']) ? -1 : 1;
        });

        // Renderer orders siden indeholdende data for invoices.
        return view('pages.orders', ['invoices' => $invoices]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function sortByTotal()
    {
        // Henter alle invoices og kaldes toArray metoden.
        $invoices = $this->getInvoices()->toArray();

        // Sortering af invoices.
        usort($invoices, function ($a, $b) {
            if ($a['total'] == $b['total']) {
                return 0;
            }
            return ($a['total'] > $b['total']) ? -1 : 1;
        });
        
        // Renderer orders siden indeholdende data for invoices.
        return view('pages.orders', ['invoices' => $invoices]);
    }
}
