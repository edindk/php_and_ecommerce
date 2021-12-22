<?php

namespace App\Listeners;

use App\Events\ShowDashboardData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GetDashboardData
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ShowDashboardData $event
     * @return void
     */
    public function showDashboardData(ShowDashboardData $event)
    {
        //var_dump($event->chartData);
        //var_dump($event->latLngData);
    }
}
