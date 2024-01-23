@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('product_title', config('app.name'))
@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Dashboard</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->


                @if (!empty($count_member_in_portals))
                    @foreach ($count_member_in_portals as $portal_name => $portal_member_counts)
                        <div class="row">
                            <div class="col-12">
                                <h4 class="header-title mb-3">{{ ucfirst($portal_name) }}: Member Details</h4>
                            </div>

                            @foreach ($portal_member_counts as $type => $count)
                                <div class="col-md-6 col-xl-3">
                                    <div class="widget-rounded-circle card-box">
                                        <div class="row">
                                            <div class="col-4">
                                                @if ($type == 'active')
                                                    <div
                                                        class="avatar-lg rounded-circle bg-soft-success border-success border">
                                                        <i class="fe-user-check font-22 avatar-title text-success"></i>
                                                    </div>
                                                @elseif($type == 'inactive')
                                                    <div
                                                        class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                                        <i class="fe-user-x font-22 avatar-title text-warning"></i>
                                                    </div>
                                                @elseif($type == 'suspend')
                                                    <div
                                                        class="avatar-lg rounded-circle bg-soft-danger border-danger border">
                                                        <i class="fe-user-minus font-22 avatar-title text-danger"></i>
                                                    </div>
                                                @else
                                                    <div
                                                        class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                                        <i class="fe-users font-22 avatar-title text-primary"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-8">
                                                <div class="text-right">
                                                    <h3 class="mt-1"><span
                                                            data-plugin="counterup">{{ $count }}</span></h3>
                                                    <p class="text-muted mb-1 text-truncate">{{ ucfirst($type) }} Members
                                                    </p>
                                                </div>
                                            </div>
                                        </div> <!-- end row-->
                                    </div> <!-- end widget-rounded-circle-->
                                </div> <!-- end col-->
                            @endforeach
                        </div>
                    @endforeach
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="header-title mb-3">Today's Collection Details</h4>

                            <div class="table-responsive">
                                <table class="table table-nowrap table-hover table-centered m-0">

                                    <thead class="thead-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Package Collection</th>
                                            <th>Installation Collection</th>
                                            <th>Total Collected Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                 
                                    $total_collection = 0;
                                    if (!empty($bill_collectors)):
                                        // dump($bill_collectors);
                                      foreach ($bill_collectors as $bill_collector):
                                        $total_collection += $bill_collector->total;
                                  ?>

                                        <tr>
                                            <td>
                                                <h5 class="m-0 font-weight-normal">{{ $bill_collector->name }}</h5>
                                            </td>

                                            <td>
                                                {{ $bill_collector->wallet_transaction }}
                                            </td>

                                            <td>
                                                {{ $bill_collector->billing_transaction }}
                                            </td>

                                            <td>
                                                {{ $bill_collector->total }}
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot class="">
                                        <?php if (!empty($total_collection)): ?>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">Total</th>
                                            <th>{{ $total_collection }}</th>
                                        </tr>
                                        <?php endif; ?>
                                    </tfoot>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div> <!-- end card-box-->
                    </div><!-- end today's collections-->

                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="header-title mb-3">Upcoming Renew Details</h4>

                            <div class="table-responsive">
                                <table class="table table-nowrap table-hover table-centered m-0">

                                    <thead class="thead-light">
                                        <tr>
                                            <th>Date</th>
                                            @if (!empty($portals))
                                                @foreach ($portals as $portal)
                                                    <th>{{ ucfirst($portal) }}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if (!empty($upcoming_renew_lists))
                                            @foreach ($upcoming_renew_lists as $list)
                                                <tr>
                                                    @foreach ($list as $item)
                                                        <td>{{ $item }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div> <!-- end card-box-->
                    </div><!-- end today's collections-->

                </div>
                <!-- end row-->
            </div> <!-- container -->

        </div> <!-- content -->
    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

        })
    </script>
@endpush
