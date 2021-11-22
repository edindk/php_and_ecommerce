<?php
@extends('layouts.master')

@section('pageTitle', 'Products Index')

@section('content')
    <h1 class="display-6">Students</h1>
    <a href="{{route('products.create')}}">Create New</a>
    <hr/>


    <table class="products">
        <thead>
        <th>Product Name</th>
        </thead>

        @foreach($products as $products)
            <tr>
                <td>{{$products->first_name}}</td>
                <td>{{$products->last_name}}</td>
                <td>{{$products->age}}</td>
                <td>{{$products->email}}</td>
                <td>
                    <div class="d-flex">
                        <a href="{{route('products.show', $products->id)}}" class="btn btn-info m-1">Details</a>
                        <a href="{{route('products.edit', $products->id)}}" class="btn btn-primary m-1">Edit</a>

                        <form action="{{ route('products.destroy', $products->id) }}" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-danger m-1">Delete User</button>
                        </form>
                    </div>

                </td>
            </tr>
        @endforeach
    </table>

@endsection
