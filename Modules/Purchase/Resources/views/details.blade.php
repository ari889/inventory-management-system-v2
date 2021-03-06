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
                <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm" id="print-invoice"><i class="fas fa-print"></i> Print</button>
                    <a href="{{ route('purchase') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <hr>
        </div>
        <div id="invoice">
            <style>
                body,
                html {
                    background: #fff !important;
                    -webkit-print-color-adjust: exact !important;
                }

                .invoice {
                    /* position: relative; */
                    background: #fff !important;
                    /* min-height: 680px; */
                }

                .invoice header {
                    padding: 10px 0;
                    margin-bottom: 20px;
                    border-bottom: 1px solid #036;
                }

                .invoice .company-details {
                    text-align: right
                }

                .invoice .company-details .name {
                    margin-top: 0;
                    margin-bottom: 0;
                }

                .invoice .contacts {
                    margin-bottom: 20px;
                }

                .invoice .invoice-to {
                    text-align: left;
                }

                .invoice .invoice-to .to {
                    margin-top: 0;
                    margin-bottom: 0;
                }

                .invoice .invoice-details {
                    text-align: right;
                }

                .invoice .invoice-details .invoice-id {
                    margin-top: 0;
                    color: #036;
                }

                .invoice main {
                    padding-bottom: 50px
                }

                .invoice main .thanks {
                    margin-top: -100px;
                    font-size: 2em;
                    margin-bottom: 50px;
                }

                .invoice main .notices {
                    padding-left: 6px;
                    border-left: 6px solid #036;
                }

                .invoice table {
                    width: 100%;
                    border-collapse: collapse;
                    border-spacing: 0;
                    margin-bottom: 20px;
                }

                .invoice table th {
                    background: #036;
                    color: #fff;
                    padding: 15px;
                    border-bottom: 1px solid #fff
                }

                .invoice table td {
                    padding: 15px;
                    border-bottom: 1px solid #fff
                }

                .invoice table th {
                    white-space: nowrap;
                }

                .invoice table td h3 {
                    margin: 0;
                    color: #036;
                }

                .invoice table .qty {
                    text-align: center;
                }

                .invoice table .price,
                .invoice table .discount,
                .invoice table .tax,
                .invoice table .total {
                    text-align: right;
                }

                .invoice table .no {
                    color: #fff;
                    background: #036
                }

                .invoice table .total {
                    background: #036;
                    color: #fff
                }

                .invoice table tbody tr:last-child td {
                    border: none
                }

                .invoice table tfoot td {
                    background: 0 0;
                    border-bottom: none;
                    white-space: nowrap;
                    text-align: right;
                    padding: 10px 20px;
                    border-top: 1px solid #aaa
                }

                .invoice table tfoot tr:first-child td {
                    border-top: none
                }

                .invoice table tfoot tr:last-child td {
                    color: #036;
                    border-top: 1px solid #036
                }

                .invoice table tfoot tr td:first-child {
                    border: none
                }

                .invoice footer {
                    width: 100%;
                    text-align: center;
                    color: #777;
                    border-top: 1px solid #aaa;
                    padding: 8px 0
                }

                .invoice a {
                    content: none !important;
                    text-decoration: none !important;
                    color: #036 !important;
                }

                .page-header,
                .page-header-space {
                    height: 100px;
                }

                .page-footer,
                .page-footer-space {
                    height: 20px;

                }

                .page-footer {
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                    text-align: center;
                    color: #777;
                    border-top: 1px solid #aaa;
                    padding: 8px 0
                }

                .page-header {
                    position: fixed;
                    top: 0mm;
                    width: 100%;
                    border-bottom: 1px solid black;
                }

                .page {
                    page-break-after: always;
                }

                @media screen {
                    .no_screen {display: none;}
                    .no_print {display: block;}
                    thead {display: table-header-group;} 
                    tfoot {display: table-footer-group;}
                    button {display: none;}
                    body {margin: 0;}
                }

                @media print {

                    body,
                    html {
                        /* background: #fff !important; */
                        -webkit-print-color-adjust: exact !important;
                        font-family: sans-serif;
                        /* font-size: 12px !important; */
                        margin-bottom: 100px !important;
                    }

                    .m-0 {
                        margin: 0 !important;
                    }

                    h1,
                    h2,
                    h3,
                    h4,
                    h5,
                    h6 {
                        margin: 0 !important;
                    }

                    .no_screen {
                        display: block !important;
                    }

                    .no_print {
                        display: none;
                    }

                    a {
                        content: none !important;
                        text-decoration: none !important;
                        color: #036 !important;
                    }

                    .text-center {
                        text-align: center !important;
                    }

                    .text-left {
                        text-align: left !important;
                    }

                    .text-right {
                        text-align: right !important;
                    }

                    .float-left {
                        float: left !important;
                    }

                    .float-right {
                        float: right !important;
                    }

                    .text-bold {
                        font-weight: bold !important;
                    }

                    .invoice {
                        /* font-size: 11px!important; */
                        overflow: hidden !important;
                        background: #fff !important;
                        margin-bottom: 100px !important;
                    }

                    .invoice footer {
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        /* page-break-after: always */
                    }

                    /* .invoice>div:last-child {
                        page-break-before: always
                    } */
                    .hidden-print {
                        display: none !important;
                    }
                }

                @page {
                    /* size: auto; */
                    margin: 5mm 5mm;

                }
            </style>
            <div class="invoice overflow-auto">
                <div>
                    <table>
                        <tr>
                            <td>
                                <a href="{{ route('dashboard') }}" class="logo-text">
                                    <img src="{{ asset('storage/'.LOGO_PATH.config('settings.logo')) }}" alt="Logo" style="width:  250px;">
                                </a>
                            </td>
                            <td class="text-end">
                                <h2 class="name m-0">{{ config('settings.title') ? config('settings.title') : env('APP_NAME') }}</h2>
                                <p class="m-0">{{ config('settings.address') }}</p>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td width="50%">
                                <div class="invoice-to">
                                    <div class="text-gray-light">INVOICE TO:</div>
                                    <div class="to">{{ $purchase->supplier->name }}</div>
                                    <div class="phone">{{ $purchase->supplier->phone }}</div>
                                    @if($purchase->supplier->email) <div class="email">{{ $purchase->supplier->email }}</div> @endif
                                    @if($purchase->supplier->address) <div class="address">{{ $purchase->supplier->address }}</div> @endif
                                </div>
                            </td>
                            <td width="50%" class="text-end">
                                <h4 class="name m-0">{{ $purchase->purchase_no }}</h4>
                                <div class="m-0 date">Date: {{ date('d-M-Y', strtotime($purchase->created_at)) }}</div>
                            </td>
                        </tr>
                    </table>
                    <table border="0" collspacing="0" collpadding="0">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-start">DESCRIPTION</th>
                                <th class="text-center">QUANTITY</th>
                                <th class="text-end">PRICE</th>
                                <th class="text-end">DISCOUNT</th>
                                <th class="text-end">TAX</th>
                                <th class="text-end">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$purchase->purchase_products->isEmpty())
                                @foreach ($purchase->purchase_products  as $key => $purchase_product)
                                    <tr>
                                        <td class="text-center no">{{ $key+1 }}</td>
                                        <td class="text-start">{{ $purchase_product->name }}</td>
                                        <td class="text-center qty">{{ $purchase_product->pivot->qty.' '.DB::table('units')->where('id', $purchase_product->pivot->unit_id)->value('unit_name') }}</td>
                                        <td class="text-end price">{{ number_format($purchase_product->pivot->net_unit_cost, 2) }}</td>
                                        <td class="text-end discount">{{ number_format($purchase_product->pivot->discount, 2) }}</td>
                                        <td class="text-end tax">{{ number_format($purchase_product->pivot->tax, 2) }}</td>
                                        <td class="text-end total">{{ number_format($purchase_product->pivot->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4"></td>
                                <td colspan="2" class="text-right">TOTAL</td>
                                <td class="text-right">
                                    @if (config('settings.currency_position') == 'suffix')
                                        {{ number_format($purchase->total_price, 2) }} {{ config('settings.currency_symbol') }}
                                    @else
                                        {{ config('settings.currency_symbol') }} {{ number_format($purchase->total_price, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td colspan="2" class="text-right">DISCOUNT</td>
                                <td class="text-right">
                                    @if (config('settings.currency_position') == 'suffix')
                                        {{ number_format($purchase->total_discount, 2) }} {{ config('settings.currency_symbol') }}
                                    @else
                                        {{ config('settings.currency_symbol') }} {{ number_format($purchase->total_discount, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td colspan="2" class="text-right">TAX {{ $purchase->order_tax_rate }}%</td>
                                <td class="text-right">
                                    @if (config('settings.currency_position') == 'suffix')
                                        {{ number_format($purchase->order_tax_rate, 2) }} {{ config('settings.currency_symbol') }}
                                    @else
                                        {{ config('settings.currency_symbol') }} {{ number_format($purchase->order_tax_rate, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td colspan="2" class="text-right">SHIPPING COST</td>
                                <td class="text-right">
                                    @if (config('settings.currency_position') == 'suffix')
                                        {{ number_format($purchase->shipping_cost, 2) }} {{ config('settings.currency_symbol') }}
                                    @else
                                        {{ config('settings.currency_symbol') }} {{ number_format($purchase->shipping_cost, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td colspan="2" class="text-right">GRAND TOTAL</td>
                                <td class="text-right">
                                    @if (config('settings.currency_position') == 'suffix')
                                        {{ number_format($purchase->grand_total, 2) }} {{ config('settings.currency_symbol') }}
                                    @else
                                        {{ config('settings.currency_symbol') }} {{ number_format($purchase->grand_total, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td colspan="2" class="text-right">PAID AMOUNT</td>
                                <td class="text-right">
                                    @if (config('settings.currency_position') == 'suffix')
                                        {{ number_format($purchase->paid_amount, 2) }} {{ config('settings.currency_symbol') }}
                                    @else
                                        {{ config('settings.currency_symbol') }} {{ number_format($purchase->paid_amount, 2) }}
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="js/jquery.printarea.js"></script>
<script>
    $(document).ready(function(){
        $(document).on('click', '#print-invoice', function(e){
            var mode = 'iframe';
            var close = mode == "popup";
            var options = {
                mode : mode,
                popClose: close
            };
            $('#invoice').printArea(options);
        });
    });
</script>
@endpush
