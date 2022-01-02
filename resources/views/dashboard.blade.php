@extends('layouts.app')

@section('content')
    @include('layouts.headers.customCards')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="text-white mb-0">Salg i DKK</h2>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-light" role="status" style="width: 100px; height: 100px"
                             id="salesInDKKSpinner">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Sales in DKK -->
                        <canvas id="salesInDkk" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="mb-0">Total antal ordre pr. måned</h2>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border mt-3" role="status" style="width: 100px; height: 100px"
                             id="salesPrMonthSpinner">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Orders per month -->
                        <canvas id="ordersPerMonth" class="chart-canvas" height="350"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 mt-3">
                <div class="card shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="mb-0">Oversigt over hvor dine kunder er fra</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border mt-3" role="status" style="width: 100px; height: 100px"
                                 id="customerOverviewSpinner">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <!-- Customer location overview -->
                        <div id="map" style="height: 800px; width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
    <script src="https://maps.google.com/maps/api/js?sensor=false"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        let chartData = @json($chartData);
        let listOfLatLng = @json($latLngData);
        let salesInDkkChart;
        let ordersPerMonthChart;

        function salesInDkk() {
            let labelsForLineChart = Object.values(chartData).map(function (item) {
                return item['date'];
            });
            let dataForLineChart = Object.values(chartData).map(function (item) {
                return item['total'];
            });
            let ctx = document.getElementById('salesInDkk').getContext('2d');
            salesInDkkChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsForLineChart,
                    datasets: [{
                        label: 'Salg',
                        data: dataForLineChart,
                        backgroundColor: [
                            'rgba(94,114,228)'
                        ],
                        borderColor: [
                            'rgba(94,114,228)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            var salesInDKKSpinner = document.getElementById('salesInDKKSpinner');
            salesInDKKSpinner.style.display = "none";
        }

        function ordersPrMonth() {
            let labelsForBarChart = Object.values(chartData).map(function (item) {
                return item['date'];
            });
            let dataForBarChart = Object.values(chartData).map(function (item) {
                return item['numbOfOrders'];
            });
            const ctx = document.getElementById('ordersPerMonth').getContext('2d');
            ordersPerMonthChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelsForBarChart,
                    datasets: [{
                        label: 'Antal ordre',
                        data: dataForBarChart,
                        backgroundColor: 'rgba(94,114,228)',
                        borderColor: 'rgba(94,114,228)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            var salesPrMonthSpinner = document.getElementById('salesPrMonthSpinner');
            salesPrMonthSpinner.style.display = "none";
        }

        function mapInit() {
            // Tømmer markers kollektionen
            let markers = [];
            // Opretter nyt Google Maps og opbevarer det i map
            let map = new google.maps.Map(document.getElementById("map"), {
                zoom: 7,
                center: {lat: 56.26392, lng: 9.501785}
            })
            // Looper igennem listOfLatLng
            for (const key in listOfLatLng) {
                // Opretter markerOptions med længdegrader, breddegrader og bynavn
                const markerOptions = {
                    map: map,
                    position: {lat: parseFloat(listOfLatLng[key].lat), lng: parseFloat(listOfLatLng[key].lng)},
                    title: listOfLatLng[key].cityName + ' ' + listOfLatLng[key].zipCode,
                }
                // Opretter et infoWindow med bynavn og postnr.
                const infoWindow = new google.maps.InfoWindow({
                    content: listOfLatLng[key].cityName + ' ' + listOfLatLng[key].zipCode
                })
                // Opretter et nyt marker objekt og sender markerOptions objektet med i parameterlisten
                const marker = new google.maps.Marker(markerOptions)
                // Tilføjer en listener på markeren, så infoWindow vises ved klik på marker
                marker.addListener("click", () => {
                    infoWindow.open(this.map, marker)
                })
                // Pusher markeren ind i markers kollektionen
                markers.push(marker)
            }
            var customerOverviewSpinner = document.getElementById('customerOverviewSpinner');
            customerOverviewSpinner.style.display = "none";
        }

        function updateSalesInDkk(data) {
            var salesInDKKSpinner = document.getElementById('salesInDKKSpinner');
            salesInDKKSpinner.style.display = "block";
            let labelsForLineChart = Object.values(data).map(function (item) {
                return item['date'];
            });
            let dataForLineChart = Object.values(data).map(function (item) {
                return item['total'];
            });
            salesInDkkChart.data.labels = labelsForLineChart
            salesInDkkChart.data.datasets[0]['data'] = dataForLineChart
            salesInDKKSpinner.style.display = "none";
            salesInDkkChart.update();
        }

        function updateOrdersPerMonth(data) {
            let labelsForBarChart = Object.values(data).map(function (item) {
                return item['date'];
            });
            let dataForBarChart = Object.values(data).map(function (item) {
                return item['numbOfOrders'];
            });
            ordersPerMonthChart.data.labels = labelsForBarChart
            ordersPerMonthChart.data.datasets[0]['data'] = dataForBarChart
            ordersPerMonthChart.update();
        }

        function updateMap(latLngData) {
            listOfLatLng = latLngData;
            mapInit();
        }

        // Enable pusher logging - don't include this in production
        //Pusher.logToConsole = true;

        var pusher = new Pusher('e5217b92bbac90d908ee', {
            cluster: 'eu'
        });

        var channel = pusher.subscribe('test');

        channel.bind('App\\Events\\ShowDashboardData', function (data) {
            updateSalesInDkk(data['chartData']);
            updateOrdersPerMonth(data['chartData']);
            updateMap(data['latLngData']);
        });

        salesInDkk();
        ordersPrMonth();
        mapInit();


        document.addEventListener('DOMContentLoaded', e => {
            fetch('/broadcast');
        })
    </script>
@endpush
