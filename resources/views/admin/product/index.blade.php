@extends('admin.layouts.app')
@section('title', 'Product')
@section('product_title', config('app.name'))

@push('styles')
@endpush

@section('content')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            {{-- <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">UBold</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                    <li class="breadcrumb-item active">Datatables</li>
                                </ol>
                            </div> --}}
                            <h4 class="page-title">@yield('title')</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('admin.product.create') }}" class="btn btn-info waves-effect waves-light mb-3">Add Member</a>
                                <table id="ddDataTable" class="table table-centered dt-responsive nowrap w-100">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Actions</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>

                                </table>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->

        </div> <!-- content -->

    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->

@endsection
@push('scripts')
    <script type="text/javascript"></script>
@endpush
