@extends('layouts.app')

@section('content')
    @include('layouts.headers.customCards')
{{--    <script>--}}
{{--        function showCreateModal() {--}}
{{--            let modal = document.getElementsByClassName('modal fade');--}}
{{--            modal.classList.add("show");--}}
{{--        }--}}

{{--        function readURL(input) {--}}
{{--            if (input.files && input.files[0]) {--}}
{{--                var reader = new FileReader();--}}

{{--                reader.onload = function (e) {--}}
{{--                    $('#image').attr('src', e.target.result).width(150).height(150);--}}
{{--                };--}}

{{--                reader.readAsDataURL(input.files[0]);--}}
{{--            }--}}
{{--        }--}}
{{--    </script>--}}

    <?php
    $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

    $up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order);
    $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';

    ?>
    <div class="container-fluid mt--7">
        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                <span class="alert-text"><strong>Opdateret!</strong> {{session('message')}}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0 d-inline" style="display: inline">
                        <h3 class="mb-0 d-inline">Kategorier</h3>

                        <div class="d-inline w-auto float-right">
                            <button type="button" class="btn-block btn-neutral btn-outline-white  mb-0 border-0 rounded"
                                    data-toggle="modal" data-target="#modal{{$categories[0]->productCategoryID}}">Opret nyt kategori</button>
                        </div>
                    </div>
                    <!-- Light table -->
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr id="headerTR">
                                <th scope="col" class="sort" data-sort="categoryName"><a href="categories?column=categoryName&order=<?php echo $asc_or_desc; ?>">Kategori navn <i class="fas fa-sort"></i></a></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            @foreach($categories as $category)

                                <tr
                                    @if($category->productCategoryID == 0)
                                    class="invisible collapse"
                                    @endif
                                >
                                    <th scope="row">
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                                <span class="name mb-0 text-sm">{{$category->categoryName}}</span>
                                            </div>
                                        </div>
                                    </th>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" data-toggle="modal"
                                                   data-target="#modal{{$category->productCategoryID}}">Rediger</a>
                                                <a class="dropdown-item" href="{{url('/categories/delete/'.$category->productCategoryID)}}">Slet</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal{{$category->productCategoryID}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{$category->categoryName}}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form role="form" action="
                                                    @if($category->productCategoryID > 0)
                                                {{url('/categories/update')}}
                                                @else
                                                {{url('/categories/create')}}
                                                @endif
                                                    " method="post" enctype="multipart/form-data">
                                                    @csrf()
                                                    <div class="form-group mb-3">
                                                        <input type="hidden" id="productCategoryID" name="productCategoryID"
                                                               value="{{$category->productCategoryID}}">
                                                        <div class="mb-2 ml-1">
                                                            <span>Kategori navn</span>
                                                        </div>
                                                        <div
                                                            class="input-group input-group-merge input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"></span>
                                                            </div>
                                                            <input class="form-control" placeholder="{{$category->categoryName}}"
                                                                   type="text" name="categoryName">
                                                        </div>
                                                    </div>
                                                    @if($category->productCategoryID < 1)
                                                        <div class="form-group mb-3">
                                                            <div class="mb-2 ml-1">
                                                                <span>Image file</span>
                                                            </div>
                                                            <div
                                                                class="input-group input-group-merge input-group-alternative">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"></span>
                                                                </div>
                                                                <input type="file" id="img" name="img" accept="image/*">
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">
                                                            Luk
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Gem</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
