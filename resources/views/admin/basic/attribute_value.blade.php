@extends('admin.layouts.app')
@section('title', 'Attribute Value')
@section('product_title', config('app.name'))

@push('styles')
@endpush

@php
    use App\Helpers\ColorHelper;
@endphp

@section('content')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->

    <!-- Add modal -->
    <div class="modal fade add-btn" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <form id="addForm" class="" action="{{ route('admin.attribute_value.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="id" value="{{ $attribute->id }}">
                            <label for="name">Name<span class="text-danger">&#42;</span></label>
                            <input id="name" class="form-control" name="name" type="text" placeholder="Name"
                                data-parsley-addformname data-parsley-addformname-message="Name is already exist"
                                data-parsley-required-message="Name is required." required>
                        </div>

                        <button id="btnSubmit" type="submit" class="btn btn-info">Save</button>

                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- edit modal -->
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <form id="editForm" class="" action="" method="put">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name<span class="text-danger">&#42;</span></label>
                            <input id="name" class="form-control" name="name" type="text" placeholder="Name"
                                data-parsley-editformname data-parsley-editformname-message="Name is already exist"
                                data-parsley-required-message="Name is required." required>
                        </div>

                        <button type="submit" class="btn btn-info">Update</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- /edit modal -->

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
                                <a href="{{ route('admin.attributes.index') }}"
                                    class="btn btn-dark waves-effect waves-light mb-3">
                                    <i class="fas fa-arrow-left"></i> Back</a>
                                <button type="button" class="btn btn-info waves-effect waves-light mb-3"
                                    data-toggle="modal" data-target=".add-btn">Add New Attribute Value</button>
                                <table id="ddDataTable" class="table table-centered dt-responsive nowrap w-100">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Actions</th>
                                            <th>Main Attribute</th>
                                            <th>Name</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($attribute_values as $attribute_value)
                                            <tr>
                                                <td>
                                                    <div class="dropdown">
                                                        <div class="btn btn btn-primary btn-xs dropdown-toggle"
                                                            data-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-h"></i>
                                                        </div>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                            style="">
                                                            <a href="#" class="dropdown-item edit-btn"
                                                                data-toggle="modal" data-id="{{ $attribute_value->id }}">
                                                                <i class="far fa-edit mr-3"></i>Edit</a>
                                                            <a href="#" class="dropdown-item delete-btn"
                                                                data-toggle="modal" data-id="{{ $attribute_value->id }}"><i
                                                                    class="far fa-trash-alt mr-3"></i>Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $attribute->attribute }}</td>
                                                <td>
                                                    {{ $attribute_value->attribute_value }}
                                                    @if (preg_match('/^#[0-9A-Fa-f]{6}$/', $attribute_value->attribute_value))
                                                        <label class="colour ml-2"
                                                            style="{{ ColorHelper::getDarkColorStyle($attribute_value->attribute_value) }}"></label>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
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
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });


            var datatableOptions = {
                responsive: true,
                processing: true,
            }

            var table = $('#ddDataTable').DataTable(datatableOptions);


            /************* inserdata addclass *****************/
            $('#addForm').parsley();

            var clickCount = 0;
            $("#btnSubmit").on('click', function() {
                clickCount = clickCount + 1;
                if (clickCount !== 1) {
                    return false;
                }
            });
            $('#addForm').parsley().on('field:error', function() {
                clickCount = 0;
            });

            $('#addForm').ajaxForm({
                beforeSubmit: function(arr, form, options) {
                    $.busyLoadFull("show");
                },
                dataType: 'json',
                statusCode: {
                    500: function(error) {
                        $.busyLoadFull("hide");
                        Swal.fire({
                            text: error.responseJSON.message,
                            type: "warning",
                            dangerMode: true,
                        }).then((res) => {
                            location.reload();
                        });
                    },
                    200: function(res) {
                        $.busyLoadFull("hide");
                        $('#addForm').parsley().reset();
                        if (res.type == 'success') {
                            Swal.fire({
                                text: res.text,
                                type: "success",
                            }).then(function(res) {
                                location.reload();
                            });
                            // console.log(res);
                            $('#add-modal').modal('hide');
                            $('#addForm')[0].reset();

                            // After add new show table and hide no data alert
                            // $('#tableCard').removeClass('hide');
                            // $('#noDataAlert').addClass('hide');

                            // table.destroy();
                            // table = $('#ddDataTable').DataTable(datatableOptions);
                        }
                    }
                }
            });

            // Unique check name Add form
            window.Parsley.addValidator('addformname', {
                validateString: function(value) {
                    // console.log(value);
                    return $.ajax({
                        url: "{{ route('admin.attribute_value.unique-name') }}",
                        method: "POST",
                        data: {
                            name: value,
                        },
                        dataType: "json",
                        success: function(data) {
                            return true;
                        }
                    });
                },
            });

            // after add-modal close reset add-forms
            $("#add-modal").on("hidden.bs.modal", function() {
                $('#addForm').parsley().reset();
            });

            /*************** edit editForm *****************/
            $('#editForm').parsley();

            var id = '';
            $('body').on('click', '.edit-btn', function() {
                $.busyLoadFull("show");
                id = $(this).data('id');
                // Make an AJAX request to fetch data
                $.get("{{ route('admin.attribute_value.edit', '') }}/" + id, function(data) {
                    $.busyLoadFull("hide");
                    $('#edit-modal').modal('show');
                    $("#editForm").attr("action",
                        "{{ route('admin.attribute_value.update', '') }}/" + data.id);
                    $('#editForm #name').val(data.attribute_value);
                });
            });

            $('#editForm').ajaxForm({
                beforeSubmit: function(arr, form, options) {
                    $.busyLoadFull("show");
                },
                type: 'PUT',
                statusCode: {
                    500: function(error) {
                        $.busyLoadFull("hide");
                        Swal.fire({
                            text: error.responseJSON.message,
                            type: "warning",
                            dangerMode: true,
                        });
                    },
                    200: function(res) {
                        // console.log(res);
                        $.busyLoadFull("hide");
                        $('#editForm').parsley().reset();
                        if (res.type == 'success') {
                            Swal.fire({
                                text: res.text,
                                type: "success",
                            }).then(function(res) {
                                location.reload();
                            });
                            // console.log(res);
                            $('#edit-modal').modal('hide');
                            $('#editForm').clearForm();

                            // After add new show table and hide no data alert
                            // $('#tableCard').removeClass('hide');
                            // $('#noDataAlert').addClass('hide');

                            // table.destroy();
                            // table = $('#ddDataTable').DataTable(datatableOptions);
                        }
                    }
                }
            });

            // Unique check name Edit form
            window.Parsley.addValidator('editformname', {
                validateString: function(value) {
                    return $.ajax({
                        url: "{{ route('admin.attribute_value.unique-name') }}",
                        method: "POST",
                        data: {
                            name: value,
                            exception_data: id,
                        },
                        dataType: "json",
                        success: function(data) {
                            return true;
                        }
                    });
                }
            });

            // after edit-class-modal close rest all forms
            $("#edit-modal").on("hidden.bs.modal", function() {
                $('#editForm').parsley().reset();
            });


            /*************** Delete *****************/
            $('body').on('click', ".delete-btn", function(e) {
                var id = $(this).data('id');
                e.preventDefault();
                Swal.fire({
                        title: "ATTENTION !!!",
                        text: "If you delete this attribute value it can't be restored.",
                        type: "warning",
                        showCancelButton: true,
                    })
                    .then((res) => {
                        if (res.value) {
                            $.ajax({
                                type: 'DELETE',
                                url: "{{ route('admin.attribute_value.destroy', ['id' => '__id__']) }}"
                                    .replace('__id__', id),

                                success: function(res) {
                                    Swal.fire({
                                        text: res.text,
                                        type: "success",
                                    }).then((res) => {
                                        location.reload();
                                    });
                                }
                            });
                        }
                    });
            });

        });
    </script>
@endpush
