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
                @if(permission('menu-add'))
                    <button type="button" class="btn btn-primary btn-sm" onclick="showFormModal('Add new menu', 'Save')">
                        <i class="fas fa-plus-square"></i> Add New
                    </button>
                @endif
            </div>
            <hr>
            <form id="form-filter">
                <div class="row">
                    <div class="col-md-4">
                        <label for="menu_name" class="mb-2">Menu Name</label>
                        <input type="text" name="menu_name" id="menu_name" class="form-control" placeholder="Enter Menu Name">
                    </div>
                    <div class="col-md-8 clearfix pt-24">
                        <button type="button" class="btn btn-danger btn-sm float-end" id="btn-reset" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Data"><i class="fas fa-redo-alt"></i></button>
                        <button type="button" class="btn btn-primary btn-sm float-end me-2" id="btn-filter" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Data"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            <table id="dataTable" class="table table-stripped table-bordered table-hover">
                <thead class="bg-primary">
                    <tr>
                        @if(permission('menu-bulk-delete'))
                            <th>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                    <label for="" class="custom-control-label" id="select_all"></label>
                                </div>
                        </th>
                        @endif
                        <th>SL</th>
                        <th>Menu Name</th>
                        <th>Datatable</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- menu add/edit modal start -->
@if(permission('menu-edit') || permission('menu-add'))
    @include('menu.modal')
@endif
<!-- menu add/edit modal end -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            var table;
            table = $('#dataTable').DataTable({
                "processing" : true, // control processing indicator
                "serverSide" : true, // serverside processing
                "order" : [], // initial no order
                "responsive" : true, // responsive true
                "bInfo" : true, // to show total number of data
                "bFilter" : false, // hide search box
                "lengthMenu" : [
                    [5,10,15,25,50,100,1000,10000,-1],
                    [5,10,15,25,50,100,1000,10000,"All"],
                ],
                "pageLength" : 25, // per page data,
                "language" : {
                    processing : '<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i>',
                    emptyTable : '<strong class="text-danger">No data found</strong>',
                    infoEmpty : '',
                    zeroRecords : '<strong class="text-danger">No data found</strong>'
                },
                "ajax" : {
                    "url" : "{{ route('menu.datatable.data') }}",
                    "type" : "POST",
                    "data" : function(data){
                        data.menu_name = $('#form-filter #menu_name').val();
                        data._token    = _token;
                    }
                },
                "columnDefs" : [
                    {
                        @if(permission('menu-bulk-delete'))
                        "targets" : [0, 4],
                        @else
                        "targets" : [3],
                        @endif
                        "orderable" : false,
                        "className" : "text-center"
                    },
                    {
                        @if(permission('menu-bulk-delete'))
                        "targets" : [1, 3],
                        @else
                        "targets" : [0, 2],
                        @endif
                        "className" : "text-center"
                    }
                ],
                "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "buttons" : [
                    @if(permission('menu-report'))
                    {
                        "extend" : "colvis",
                        "className" : "btn btn-secondary btn-sm text-white",
                        "text" : "Column"
                    },
                    {
                        "extend" : "print",
                        "text" : "Print",
                        "className" : "btn btn-secondary btn-sm text-white float-end",
                        "title" : "Menu List",
                        "orientation" : "landscape",
                        "pageSize" : "A4",
                        "exportOptions" : {
                            columns : function(index, data, node){
                                return table.column(index).visible();
                            }
                        },
                        customize : function(win){
                            $(win.document.body).addClass('bg-white');
                        }
                    },
                    {
                        "extend" : "csv",
                        "text" : "CSV",
                        "className" : "btn btn-secondary btn-sm text-white",
                        "title" : "Menu List",
                        "filename" : "menu-list",
                        "exportOptions" : {
                            columns : function(index, data, node){
                                return table.column(index).visible();
                            }
                        },
                    },
                    {
                        "extend" : "excel",
                        "text" : "Excel",
                        "className" : "btn btn-secondary btn-sm text-white",
                        "title" : "Menu List",
                        "filename" : "menu-list",
                        "exportOptions" : {
                            columns : function(index, data, node){
                                return table.column(index).visible();
                            }
                        },
                    },
                    {
                        "extend" : "pdf",
                        "text" : "PDF",
                        "className" : "btn btn-secondary btn-sm text-white",
                        "title" : "Menu List",
                        "filename" : "menu-list",
                        "orientation" : "landscape",
                        "exportOptions" : {
                            columns : [1, 2, 3]
                        },
                    },
                    @endif
                    @if(permission('menu-bulk-delete'))
                    {
                        "className" : "btn btn-danger btn-sm delete_btn d-none text-white",
                        "text" : "Delete",
                        action: function(e, dt, node, config){
                            multi_delete();
                        }
                    }
                    @endif
                ]
            });
            
            // if user search
            $('#btn-filter').click(function(){
                table.ajax.reload();
            });
    
            // if user reset
            $('#btn-reset').click(function(){
                $('#form-filter')[0].reset();
                table.ajax.reload();
            });
    
            // if user add new menu
            $(document).on('click', '#save-btn', function(){
                let form = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url = "{{ route('menu.store.or.update') }}";
                let id = $('#update_id').val();
                let method;
                if(id){
                    method = 'update';
                }else{
                    method = 'add';
                }
                store_or_update_data(table, method, url, formData);
            });
    
            // edit menu data
             $(document).on('click', '.edit_data', function(e){
                e.preventDefault();
                let id = $(this).data('id');
                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form #update_id').val('');
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                $('#store_or_update_form .selectpicker').selectpicker('refresh');
                $('#store_or_update_form table tbody').find('tr:gt(0)').remove();
                if(id){
                    $.ajax({
                        url : "{{ route('menu.edit') }}",
                        type : "POST",
                        data : {
                            id : id,
                            _token : _token
                        },
                        dataType : "JSON",
                        success: function(data){
                            $('#store_or_update_form #update_id').val(data.data.id);
                            $('#store_or_update_form #menu_name').val(data.data.menu_name);
                            $('#store_or_update_form #deletable').val(data.data.deletable);
                            $('#store_or_update_form #deletable.selectpicker').selectpicker('refresh');
    
                            myModal = new bootstrap.Modal(document.getElementById('store_or_update_modal'), {
                                keyboard: false,
                                backdrop: 'static'
                            });
    
                            myModal.show();
    
                            $("#store_or_update_modal .modal-title").html('<i class="fas fa-edit"></i> '+data.data.menu_name);
                            $("#store_or_update_modal #save-btn").text("Update");
                        }
                    });
                }
             });

            //  delete data
            $(document).on('click', '.delete_data', function(e){
                e.preventDefault();
                let id = $(this).data('id');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('menu.delete') }}";
                delete_data(id, url, table, row, name);
            });
            

            // bulk delete menu
            function multi_delete(){
                let ids = [];
                let rows;
                $('.select_data:checked').each(function(){
                    ids.push($(this).val());
                    rows = table.rows($('.select_data:checked').parents('tr'));
                });
                if(ids.length == 0){
                    Swal.fire({
                        type : 'error',
                        title : 'Error',
                        text : "Please checked at least one row if the table!",
                        icon : "warning"
                    });
                }else{
                    let url = "{{ route('menu.bulk.delete') }}";
                    bulk_delete(ids, url, table, rows);
                }
            }
        });
    </script>
@endpush
