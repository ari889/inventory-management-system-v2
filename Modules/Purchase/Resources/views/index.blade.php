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
                @if(permission('purchase-add'))
                <a href="{{ route('purchase.add') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-square"></i> Add New
                </a>
                @endif
            </div>
            <hr>
            <form id="form-filter">
                <div class="row">
                    <div class="col-md-4">
                        <label for="purchase_no" class="mb-2">Purchase No</label>
                        <input type="text" name="purchase_no" id="purchase_no" class="form-control" placeholder="Enter purchase number">
                    </div>
                    <x-form.selectbox labelName="Supplier" name="supplier_id" required="required" col="col-md-4 mb-3" class="selectpicker">
                        @if(!$suppliers->isEmpty())
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name.' - '.$supplier->phone }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>
                    <div class="col-md-4">
                        <label for="from_date" class="mb-2">From Date</label>
                        <input type="text" name="from_date" id="from_date" class="form-control date" placeholder="Enter from date">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="mb-2">To Date</label>
                        <input type="text" name="to_date" id="to_date" class="form-control date" placeholder="Enter to date">
                    </div>
                    <x-form.selectbox labelName="Purchase Status" name="purchase_status" required="required" col="col-md-4 mb-3" class="selectpicker">
                        @foreach(PURCHASE_STATUS as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-form.selectbox>
                    <x-form.selectbox labelName="Payment Status" name="payment_status" required="required" col="col-md-4 mb-3" class="selectpicker">
                        @foreach(PAYMENT_STATUS as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-form.selectbox>
                    <div class="col-md-4 pt-24">
                        <button type="button" class="btn btn-danger btn-sm" id="btn-reset"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Data"><i
                                class="fas fa-redo-alt"></i></button>
                        <button type="button" class="btn btn-primary btn-sm me-2" id="btn-filter"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Data"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            <table id="dataTable" class="table table-stripped table-bordered table-hover">
                <thead class="bg-primary">
                    <tr>
                        @if(permission('purchase-bulk-delete'))
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="select_all"
                                    onchange="select_all()">
                                <label for="" class="custom-control-label" id="select_all"></label>
                            </div>
                        </th>
                        @endif
                        <th>SL</th>
                        <th>Purchase No</th>
                        <th>Supplier</th>
                        <th>Total Items</th>
                        <th>Total Qty</th>
                        <th>Total Discount</th>
                        <th>Total Tax</th>
                        <th>Total Cost</th>
                        <th>Tax Rate</th>
                        <th>Total Order Tax</th>
                        <th>Total Order Discount</th>
                        <th>Shipping Cost</th>
                        <th>Grand Total</th>
                        <th>Paid Amount</th>
                        <th>Due Amount</th>
                        <th>Purchase Status</th>
                        <th>Payment Status</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script>
    var table;
    $(document).ready(function () {
        $('.date').datetimepicker({format:'YYYY-MM-DD'});
        table = $('#dataTable').DataTable({
            "processing": true, // control processing indicator
            "serverSide": true, // serverside processing
            "order": [], // initial no order
            "responsive": true, // responsive true
            "bInfo": true, // to show total number of data
            "bFilter": false, // hide search box
            "lengthMenu": [
                [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                [5, 10, 15, 25, 50, 100, 1000, 10000, "All"],
            ],
            "pageLength": 25, // per page data,
            "language": {
                processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i>',
                emptyTable: '<strong class="text-danger">No data found</strong>',
                infoEmpty: '',
                zeroRecords: '<strong class="text-danger">No data found</strong>'
            },
            "ajax": {
                "url": "{{ route('purchase.datatable.data') }}",
                "type": "POST",
                "data": function (data) {
                    data.purchase_no = $('#form-filter #purchase_no').val();
                    data.supplier_id = $('#form-filter #supplier_id').val();
                    data.from_date = $('#form-filter #from_date').val();
                    data.to_date = $('#form-filter #to_date').val();
                    data.purchase_status = $('#form-filter #purchase_status').val();
                    data.payment_status = $('#form-filter #payment_status').val();
                    data._token = _token;
                }
            },
            "columnDefs": [{
                    @if(permission('purchase-bulk-delete'))
                    "targets": [0, 20],
                    @else "targets": [19],
                    @endif "orderable": false,
                    "className": "text-center"
                },
                {
                    @if(permission('purchase-bulk-delete'))
                    "targets": [1,4,5,16,17,18,19],
                    @else "targets": [0,3,4,15,16,17,18],
                    @endif "className": "text-center"
                },
                {
                    @if(permission('purchase-bulk-delete'))
                    "targets": [6,7,8,9,10,11,12,13,14,15],
                    @else "targets": [5,6,7,8,9,10,11,12,13,14],
                    @endif "className": "text-right"
                }
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            "buttons": [
                @if(permission('purchase-report')) {
                    "extend": "colvis",
                    "className": "btn btn-secondary btn-sm text-white",
                    "text": "Column"
                },
                {
                    "extend": "print",
                    "text": "Print",
                    "className": "btn btn-secondary btn-sm text-white float-end",
                    "title": "Purchase List",
                    "orientation": "landscape",
                    "pageSize": "A4",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                    customize: function (win) {
                        $(win.document.body).addClass('bg-white');
                    }
                },
                {
                    "extend": "csv",
                    "text": "CSV",
                    "className": "btn btn-secondary btn-sm text-white",
                    "title": "Purchase List",
                    "filename": "purchase-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                },
                {
                    "extend": "excel",
                    "text": "Excel",
                    "className": "btn btn-secondary btn-sm text-white",
                    "title": "Purchase List",
                    "filename": "purchase-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                },
                {
                    "extend": "pdf",
                    "text": "PDF",
                    "className": "btn btn-secondary btn-sm text-white",
                    "title": "Purchase List",
                    "filename": "purchase-list",
                    "orientation": "landscape",
                    "exportOptions": {
                        columns: [1, 2, 3]
                    },
                },
                @endif
                @if(permission('purchase-bulk-delete')) {
                    "className": "btn btn-danger btn-sm delete_btn d-none text-white",
                    "text": "Delete",
                    action: function (e, dt, node, config) {
                        multi_delete();
                    }
                }
                @endif
            ]
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

        //  delete data
        $(document).on('click', '.delete_data', function (e) {
            e.preventDefault();
            let id   = $(this).data('id');
            let name = $(this).data('name');
            let row  = table.row($(this).parent('tr'));
            let url  = "{{ route('purchase.delete') }}";
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
                let url = "{{ route('purchase.bulk.delete') }}";
                bulk_delete(ids, url, table, rows);
            }
        }
    });
</script>
@endpush
