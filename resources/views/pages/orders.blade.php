@extends('layouts.app')

@section('content')
    @include('layouts.headers.customCards')
    <div class="container-fluid mt--7">
        <div class="dropdown mb-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sortér efter
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="/orders/sortbynewestdate">Nyeste dato</a>
                <a class="dropdown-item" href="/orders/sortbyoldestdate">Ældste dato</a>
                <a class="dropdown-item" href="/orders/sortbytotal">Total</a>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">Ordrer</h3>
                    </div>
                    <!-- Light table -->
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Ordre ID</th>
                                <th scope="col" class="sort" data-sort="budget">Kunde ID</th>
                                <th scope="col" class="sort" data-sort="status">Betalingsdato</th>
                                <th scope="col" class="sort" data-sort="status">Total</th>
                            </tr>
                            </thead>
                            <tbody class="list">

                            @foreach($invoices as $invoice)
                                <tr>
                                    <th scope="row">
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                                <span class="name mb-0 text-sm">{{$invoice['invoiceID']}}</span>
                                            </div>
                                        </div>
                                    </th>
                                    <td class="budget">
                                        {{$invoice['customerID']}}
                                    </td>
                                    <td>
                                        {{$invoice['paidDate']}}
                                    </td>
                                    <td>
                                        {{$invoice['total']}} DKK
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
@endpush
