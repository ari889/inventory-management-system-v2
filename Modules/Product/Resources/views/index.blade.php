@extends('layouts.app')

@section('title')
{{ $page_title }}
@endsection

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">{{ config('settings.title') }}</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <h5 class="card-title"><i class="{{ $page_icon }} text-primary"></i> {{ $page_title }}</h5>
                @if(permission('product-add'))
                <button type="button" class="btn btn-primary btn-sm" onclick="showStoreFormModal('Add new product', 'Save')">
                    <i class="fas fa-plus-square"></i> Add New
                </button>
                @endif
            </div>
            <hr>
            <form id="form-filter">
                <div class="row">
                    <div class="col-md-4">
                        <label for="name" class="mb-2">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control mb-2" placeholder="Enter name">
                    </div>
                    <div class="col-md-4">
                        <label for="code" class="mb-2">Barcode</label>
                        <input type="text" name="code" id="code" class="form-control mb-2" placeholder="Enter code">
                    </div>
                    <x-form.selectbox labelName="Brand" name="brand_id" col="col-md-4 mb-2" class="selectpicker">
                        @if (!$brands->isEmpty())
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>
                    <x-form.selectbox labelName="Category" name="category_id" col="col-md-4 mb-2" class="selectpicker">
                        @if (!$categories->isEmpty())
                            @foreach ($categories as $catgory)
                                <option value="{{ $catgory->id }}">{{ $catgory->name }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>
                    <div class="col-md-8 clearfix pt-24">
                        <button type="button" class="btn btn-danger float-end btn-sm" id="btn-reset"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Data"><i
                                class="fas fa-redo-alt"></i></button>
                        <button type="button" class="btn btn-primary float-end btn-sm me-2" id="btn-filter"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Data"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            <table id="dataTable" class="table table-striped table-bordered table-hover">
                <thead class="bg-primary">
                    <tr>
                        @if (permission('product-bulk-delete'))
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                <label class="custom-control-label" for="select_all"></label>
                            </div>
                        </th>
                        @endif
                        <th>Sl</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Cost</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Alert Qty</th>
                        <th>Tax</th>
                        <th>Tax Method</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- supplier add/edit modal start -->
@if(permission('product-edit') || permission('product-add'))
@include('product::modal')
@endif
<!-- supplier add/edit modal end -->

<!-- supplier view modal start -->
@if(permission('product-view'))
@include('product::view-modal')
@endif
<!-- supplier view modal end -->
@endsection

@push('scripts')
<script src="js/spartan-multi-image-picker-min.js"></script>
<script>
    var table;
    var productModal;
    $(document).ready(function () {
        table = $('#dataTable').DataTable({
            "processing": true, //Feature control the processing indicator
            "serverSide": true, //Feature control DataTable server side processing mode
            "order": [], //Initial no order
            "responsive": true, //Make table responsive in mobile device
            "bInfo": true, //TO show the total number of data
            "bFilter": false, //For datatable default search box show/hide
            "lengthMenu": [
                [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
            ],
            "pageLength": 25, //number of data show per page
            "language": { 
                processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                emptyTable: '<strong class="text-danger">No Data Found</strong>',
                infoEmpty: '',
                zeroRecords: '<strong class="text-danger">No Data Found</strong>'
            },
            "ajax": {
                "url": "{{route('product.datatable.data')}}",
                "type": "POST",
                "data": function (data) {
                    data.name        = $("#form-filter #name").val();
                    data.code        = $("#form-filter #code").val();
                    data.brand_id    = $("#form-filter #brand_id").val();
                    data.category_id = $("#form-filter #category_id").val();
                    data._token      = _token;
                }
            },
            "columnDefs": [{
                    @if (permission('product-bulk-delete'))
                    "targets": [0,15],
                    @else 
                    "targets": [14],
                    @endif
                    "orderable": false,
                    "className": "text-center"
                },
                {
                    @if (permission('product-bulk-delete'))
                    "targets": [1,2,4,5,6,7,10,11,12,13,14],
                    @else 
                    "targets": [0,1,3,4,5,6,9,10,11,12,13],
                    @endif
                    "className": "text-center"
                },
                {
                    @if (permission('product-bulk-delete'))
                    "targets": [8,9],
                    @else 
                    "targets": [7,8],
                    @endif
                    "className": "text-right"
                }
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

            "buttons": [
                @if (permission('product-report'))
                {
                    'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
                },
                {
                    "extend": 'print',
                    'text':'Print',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": "Menu List",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                    customize: function (win) {
                        $(win.document.body).addClass('bg-white');
                    },
                },
                {
                    "extend": 'csv',
                    'text':'CSV',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": "Menu List",
                    "filename": "product-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    }
                },
                {
                    "extend": 'excel',
                    'text':'Excel',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": "Menu List",
                    "filename": "product-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    }
                },
                {
                    "extend": 'pdf',
                    'text':'PDF',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": "Menu List",
                    "filename": "product-list",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns: [1, 2, 3]
                    },
                },
                @endif 
                @if (permission('product-bulk-delete'))
                {
                    'className':'btn btn-danger btn-sm delete_btn d-none text-white',
                    'text':'Delete',
                    action:function(e,dt,node,config){
                        multi_delete();
                    }
                }
                @endif
            ],
        });

        // if user search
        $('#btn-filter').click(function () {
            table.ajax.reload();
        });

        // if user reset
        $('#btn-reset').click(function () {
            $('#form-filter')[0].reset();
            table.ajax.reload();
        });

        // if user add new menu
        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            let url = "{{ route('product.store.or.update') }}";
            let id = $('#update_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function () {
                    $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                },
                complete: function () {
                    $('#save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                },
                success: function (data) {
                    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                    $('#store_or_update_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#store_or_update_form input#' + key).addClass('is-invalid');
                            $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                            $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                            if(key == 'code'){
                                $('#store_or_update_form #' + key).parents('.form-group').append(
                                    '<small class="error text-danger">' + value + '</small>'
                                );
                            }else{
                                $('#store_or_update_form #' + key).parent().append(
                                    '<small class="error text-danger">' + value + '</small>'
                                );
                            }
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            if (method == 'update') {
                                table.ajax.reload(null, false);
                            } else {
                                table.ajax.reload();
                            }
                            productModal.hide();
                        }
                    }
                }
            });
        });

        // edit menu data
        $(document).on('click', '.edit_data', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('#store_or_update_form')[0].reset();
            $('#store_or_update_form #update_id').val('');
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            $('#store_or_update_form .selectpicker').selectpicker('refresh');
            $('#store_or_update_form table tbody').find('tr:gt(0)').remove();
            if (id) {
                $.ajax({
                    url: "{{ route('product.edit') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: _token
                    },
                    dataType: "JSON",
                    success: function (data) {
                        $('#store_or_update_form #update_id').val(data.id);
                        $('#store_or_update_form #name').val(data.name);
                        $('#store_or_update_form #barcode_symbology').val(data.barcode_symbology);
                        $('#store_or_update_form #code').val(data.code);
                        $('#store_or_update_form #brand_id').val(data.brand_id);
                        $('#store_or_update_form #category_id').val(data.category_id);
                        $('#store_or_update_form #unit_id').val(data.unit_id);
                        $('#store_or_update_form #cost').val(data.cost);
                        $('#store_or_update_form #price').val(data.price);
                        $('#store_or_update_form #qty').val(data.qty);
                        $('#store_or_update_form #alert_qty').val(data.alert_qty);
                        $('#store_or_update_form #tax_id').val(data.tax_id);
                        $('#store_or_update_form #tax_method').val(data.tax_method);
                        $('#store_or_update_form #description').val(data.description);
                        $('#store_or_update_form #old_image').val(data.image);
                        $('#store_or_update_form .selectpicker').selectpicker('refresh');
                        populate_unit(data.unit_id,data.purchase_unit_id,data.sale_unit_id);
                        if(data.image){
                            var image = "{{ asset('storage/'.PRODUCT_IMAGE_PATH)}}/"+data.image;
                            $('#store_or_update_form #image img.spartan_image_placeholder').css('display','none');
                            $('#store_or_update_form #image .spartan_remove_row').css('display','none');
                            $('#store_or_update_form #image .img_').css('display','block');
                            $('#store_or_update_form #image .img_').attr('src',image);
                        }else{
                            $('#store_or_update_form #image img.spartan_image_placeholder').css('display','block');
                            $('#store_or_update_form #image .spartan_remove_row').css('display','none');
                            $('#store_or_update_form #image .img_').css('display','none');
                            $('#store_or_update_form #image .img_').attr('src','');
                        }

                        productModal = new bootstrap.Modal(document.getElementById(
                            'store_or_update_modal'), {
                            keyboard: false,
                            backdrop: 'static'
                        });

                        productModal.show();

                        $("#store_or_update_modal .modal-title").html(
                            '<i class="fas fa-edit"></i> ' + data.name);
                        $("#store_or_update_modal #save-btn").text("Update");
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                            .responseText);
                    }
                });
            }
        });

        //  delete data
        $(document).on('click', '.delete_data', function (e) {
            e.preventDefault();
            let id   = $(this).data('id');
            let name = $(this).data('name');
            let row  = table.row($(this).parent('tr'));
            let url  = "{{ route('product.delete') }}";
            delete_data(id, url, table, row, name);
        });


        // bulk delete menu
        function multi_delete() {
            let ids = [];
            let rows;
            $('.select_data:checked').each(function () {
                ids.push($(this).val());
                rows = table.rows($('.select_data:checked').parents('tr'));
            });
            if (ids.length == 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: "Please checked at least one row if the table!",
                    icon: "warning"
                });
            } else {
                let url = "{{ route('product.bulk.delete') }}";
                bulk_delete(ids, url, table, rows);
            }
        }

        // change status
        $(document).on('click', '.change_status', function () {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let name = $(this).data('name');
            let row = table.row($(this).parent('tr'));
            let url = "{{ route('product.change.status') }}";
            Swal.fire({
                title: 'Are you sure to change ' + name + ' status?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            id: id,
                            status: status,
                            _token: _token
                        },
                        dataType: "JSON",

                    }).done(function (response) {
                        if (response.status == 'success') {
                            Swal.fire("Status Changed!", response.message, "success")
                                .then(function () {
                                    table.row(row).remove().draw(false);
                                });
                        }
                        if (response.status == 'error') {
                            Swal.fire('Opps...', "Something went wrong!", "error");
                        }
                    }).fail(function () {
                        Swal.fire('Opps...', "Something went wrong with ajax!",
                        "error");
                    });
                }
            });
        });

        // view data
        $(document).on('click', '.view_data', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            if(id){
                $.ajax({
                    url: "{{ route('product.show') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: _token
                    },
                    success : function(data){
                        $('#view_modal .details').html('');
                        $('#view_modal .details').html(data);

                        let viewModal = new bootstrap.Modal(document.getElementById(
                            'view_modal'), {
                            keyboard: false,
                            backdrop: 'static'
                        });

                        viewModal.show();
                        $('#view_modal .modal-title').html(
                        '<i class="fas fa-eye"></i> <span>Product Details</span>');
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            }
        });

        // dropify for product image
        $('#image').spartanMultiImagePicker({
            fieldName: 'image',
            maxCount: 1,
            rowHeight: '200px',
            groupClassName: 'col-md-12 com-sm-12 com-xs-12',
            maxFileSize: '',
            dropFileLabel: 'Drop Here',
            allowExt: 'png|jpg|jpeg',
            onExtensionErr: function(index, file){
                Swal.fire({icon:'error',title:'Oops...',text: 'Only png,jpg,jpeg file format allowed!'});
            }
        });

        $('input[name="image"]').prop('required',true);

        $('.remove-files').on('click', function(){
            $(this).parents('.col-md-12').remove();
        });

        // generate barcode
        $('#generate_barcode').click(function(e){
            e.preventDefault();
            $.get('generate-code', function(data){
                $('#store_or_update_modal #code').val(data);
            });
        });
    });

    // get populated units
    function populate_unit(unit_id, purchase_unit_id='',sale_unit_id=''){
        $.ajax({
            url : "{{ url('populate-unit') }}/"+unit_id,
            type : 'GET',
            dataType : 'JSON',
            success : function(data){
                $('#sale_unit_id').empty();
                $('#purchase_unit_id').empty();
                $.each(data, function(key, value){
                    $('#sale_unit_id').append('<option value="'+key+'">'+value+'</option>')
                    $('#purchase_unit_id').append('<option value="'+key+'">'+value+'</option>')
                });
                $('.selectpicker').selectpicker('refresh');
                if(purchase_unit_id){
                    $('#purchase_unit_id').val(purchase_unit_id);
                }
                if(sale_unit_id){
                    $('#sale_unit_id').val(sale_unit_id);
                }
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }

    //show modal
    function showStoreFormModal(modal_title, btn_text)
    {
        $('#store_or_update_form')[0].reset();
        $('#store_or_update_form #update_id').val('');
        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
        $('#store_or_update_form').find('.error').remove();

        $('#store_or_update_form #image img.spartan_image_placeholder').css('display','block');
        $('#store_or_update_form #image .spartan_remove_row').css('display','none');
        $('#store_or_update_form #image .img_').css('display','none');
        $('#store_or_update_form #image .img_').attr('src','');
        $('.selectpicker').selectpicker('refresh');

        productModal = new bootstrap.Modal(document.getElementById('store_or_update_modal'), {
            keyboard: false,
            backdrop: 'static'
        });

        productModal.show();

        $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square"></i> '+modal_title);
        $('#store_or_update_modal #save-btn').text(btn_text);
    }
</script>
@endpush
