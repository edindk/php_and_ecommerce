<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private function getInvoices()
    {
        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {
            $date = new \DateTime($invoice->paidDate);
            $date = date_format($date, 'y-m-d');

            $invoice->paidDate = $date;
        }
        return $invoices;
    }

    public function showOrders()
    {
        $invoices = $this->getInvoices()->toArray();

        return view('pages.orders', ['invoices' => $invoices]);
    }

    public function sortByNewestDate()
    {
        $invoices = $this->getInvoices()->toArray();

        usort($invoices, function ($a, $b) {
            if ($a['paidDate'] == $b['paidDate']) {
                return 0;
            }
            return ($a['paidDate'] < $b['paidDate']) ? -1 : 1;
        });

        return view('pages.orders', ['invoices' => $invoices]);
    }

    public function sortByOldestDate()
    {
        $invoices = $this->getInvoices()->toArray();

        usort($invoices, function ($a, $b) {
            if ($a['paidDate'] == $b['paidDate']) {
                return 0;
            }
            return ($a['paidDate'] > $b['paidDate']) ? -1 : 1;
        });

        return view('pages.orders', ['invoices' => $invoices]);
    }

    public function sortByTotal()
    {
        $invoices = $this->getInvoices()->toArray();

        usort($invoices, function ($a, $b) {
            if ($a['total'] == $b['total']) {
                return 0;
            }
            return ($a['total'] > $b['total']) ? -1 : 1;
        });

        return view('pages.orders', ['invoices' => $invoices]);
    }
}
