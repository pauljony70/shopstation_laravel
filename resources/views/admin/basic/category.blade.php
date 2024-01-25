@extends('admin.layouts.app')
@section('title', 'Category')
@section('product_title', config('app.name'))

@push('styles')
    <style>
        #editForm .dropify-wrapper {
            height: 114px;
        }

        ul {
            list-style-type: none;
        }

        .category-list {
            height: 250px;
            overflow-y: scroll;
        }
    </style>
@endpush

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
                    <form id="addForm" class="" action="{{ route('admin.category.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="make_parent_cat">
                            <label class="form-check-label" for="make_parent_cat">
                                Make Parent Category
                            </label>
                        </div>

                        <div class="form-group" id="parent_cat_div">
                            <label for="category">Choose category</label>
                            <input type="text" name="searchCategory" id="searchCategory" class="form-control"
                                placeholder="Search">
                            <ul class="category-list py-2 mb-1 border rounded">
                                @foreach ($hierarchicalCategories as $category)
                                    <li>
                                        <input type="radio" name="selected_category" value="{{ $category->id }}">
                                        {{ $category->cat_name }}
                                        @if ($category->children && count($category->children) > 0)
                                            @include('admin.partials.subcategoriesoption', [
                                                'subcategories' => $category->children,
                                            ])
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            <ul class="filled" id="parent-cat-error" aria-hidden="false">
                                <li></li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">&#42;</span></label>
                            <input id="name" class="form-control" name="name" type="text" placeholder="Name"
                                data-parsley-addformname data-parsley-addformname-message="Name is already exist"
                                data-parsley-required-message="Name is required." required>
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" id="image" class="dropify" data-height="100"
                                data-allowed-file-extensions="jpg jpeg png" data-max-file-size="1m" name="image"
                                data-parsley-errors-container="#image-error"
                                data-parsley-required-message="Image is required." required />
                            <div id="image-error" role="alert"></div>
                        </div>

                        <div class="form-group">
                            <label for="category_status">Status</label>
                            <select id="category_status" class="form-control" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
                    <form id="editForm" class="" action="" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">&#42;</span></label>
                            <input id="name" class="form-control" name="name" type="text" placeholder="Name"
                                data-parsley-editformname data-parsley-editformname-message="Name is already exist"
                                data-parsley-required-message="Name is required." required>
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" id="image" class="dropify" data-height="100"
                                data-allowed-file-extensions="jpg jpeg png" data-max-file-size="1m" data-default-file=""
                                name="image" data-parsley-errors-container="#edit-image-error"
                                data-parsley-required-message="Image is required." />
                            <div id="edit-image-error" role="alert"></div>
                        </div>

                        <div class="form-group">
                            <label for="category_status">Status</label>
                            <select id="category_status" class="form-control" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
                                <button type="button" class="btn btn-info waves-effect waves-light mb-lg-3"
                                    data-toggle="modal" data-target=".add-btn">Add New Category</button>
                                <table id="ddDataTable" class="table table-centered dt-responsive nowrap w-100">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Actions</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{-- @foreach ($categories as $category)

                                        @endforeach --}}
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

            $('#make_parent_cat').change(function() {
                // Check if the checkbox is not checked
                if (!$(this).is(':checked')) {
                    // If not checked, add the data-parsley-required-if attribute to the radio input
                    $('#category_radio').attr('data-parsley-required-if', 'make_parent_cat:not(:checked)');
                } else {
                    // If checked, remove the data-parsley-required-if attribute from the radio input
                    $('#category_radio').removeAttr('data-parsley-required-if');
                }
            });

            $('#searchCategory').on('input', function() {
                var searchText = $(this).val().toLowerCase();
                // Hide all list items
                $('.category-list li').hide();
                // Show only the items that match the search text
                $('.category-list li:contains("' + searchText + '")').show();
            });

            /* $('#make_parent_cat').on('change', function() {
                // Check if the checkbox is checked
                if ($(this).is(':checked')) {
                    // If checked, hide the parent_cat_div
                    $('#parent_cat_div').hide();
                } else {
                    // If unchecked, show the parent_cat_div
                    $('#parent_cat_div').show();
                }
            }); */

            /************* inserdata addclass *****************/
            $('#addForm').parsley();
            $('#addForm #image').dropify();

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
                    // Check if "Make Parent Category" is checked and a category is selected
                    if (!$('#make_parent_cat').prop('checked') && !$(
                            'input[name="selected_category"]:checked').val()) {
                        // Display an error message and prevent form submission
                        $('#parent-cat-error li').html('Please select a category.');
                        $('#parent-cat-error').addClass('parsley-errors-list');
                        clickCount = 0;
                        return false;
                    } else {
                        $('#parent-cat-error li').html('');
                        $('#parent-cat-error').removeClass('parsley-errors-list');
                    }
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
                        }).then(function(res) {
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
                        url: "{{ route('admin.categories.unique-name') }}",
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

        });

        // Custom jQuery expression for case-insensitive :contains
        $.expr[':'].contains = function(a, i, m) {
            return $(a).text().toLowerCase()
                .indexOf(m[3].toLowerCase()) >= 0;
        };

        function make_parent_cat() {
            if ($("#make_parent_cat").prop('checked') == true)
                $("#parent_cat_div").hide();
            else
                $("#parent_cat_div").show();
        }
    </script>
@endpush
