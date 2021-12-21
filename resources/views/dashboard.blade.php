@extends('layouts.app')

@section('content')
    @include('layouts.headers.customCards')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <div class="card-body">
                        <!-- Sales in DKK -->
                        <canvas id="salesInDkk" width="400" height="200"></canvas>
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
                    <div class="card-body">
                        <!-- Orders per month -->
                        <canvas id="ordersPerMonth" class="chart-canvas" height="320"></canvas>
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
    <script>

        let salesInDkk = () => {
            const ctx = document.getElementById('salesInDkk').getContext('2d');
            const labelsForLineChart = @json($totalAndDateData['dates']);
            const dataForLineChart = @json($totalAndDateData['total']);

            const salesInDkkChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsForLineChart,
                    datasets: [{
                        label: 'Salg',
                        data: dataForLineChart,
                        backgroundColor: [
                            'rgb(94,114,228)'
                        ],
                        borderColor: [
                            'rgb(94,114,228)'
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

        };

        let ordersPerMonth = () => {
            const ctx = document.getElementById('ordersPerMonth').getContext('2d');
            const labelsForBarChart = @json($totalAndDateData['dates']);
            const dataForBarChart = @json($totalAndDateData['numbOfOrders']);

            const ordersPerMonth = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelsForBarChart,
                    datasets: [{
                        label: 'Antal ordre',
                        data: dataForBarChart,
                        backgroundColor: 'rgb(94,114,228)',
                        borderColor: 'rgb(94,114,228)',
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

        };

        let mapInit = () => {
            let listOfLatLng = @json($latLngData);


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

        }
        salesInDkk();
        ordersPerMonth();
        mapInit();

    </script>
@endpush
