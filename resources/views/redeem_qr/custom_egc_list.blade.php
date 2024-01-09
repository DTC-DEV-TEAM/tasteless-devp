<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@push('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <style>

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #bbbbbb;
            text-align: center;
            font-size: 12px;
        }

        .sorting{
            text-align: center !important;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        }

        .responsive-table {
            overflow: auto;
            padding-bottom: 10px;
        }

        .responsive-table table {
            width: 100% !important;
            border-collapse: collapse;
        }

  

    </style>
@endpush
@section('content')

<div class='panel panel-default'>
    <div class='panel-heading'>EGC List</div>
    <div class='panel-body'>
        <div class="responsive-table">
            <table id="myTable" class="">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Uploaded Img</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Campaign ID</th>
                        <th>GC Description</th>
                        <th>GC Value</th>
                        <th>GC Reference Number</th>
                        <th>POS Invoice Number</th>
                        <th>POS Terminal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                {{-- <tbody>
                        @foreach ($customers as $customer)
                        <tr>
                            <td>
                                <a class="btn btn-xs btn-primary" href='{{ CRUDBooster::mainpath("edit")."/$customer->id?campaign_types_id=$customer->campaign_types_id&uploaded_img=$customer->uploaded_img"}}'>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-xs btn-warning" href='{{ CRUDBooster::mainpath("detail")."/$customer->id?campaign_types_id=$customer->campaign_types_id&uploaded_img=$customer->uploaded_img"}}'>
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                            <td>
                                <img src='{{ asset("uploaded_item/img/$customer->uploaded_img ") }}' style='max-height: 100px; max-width: 120px;'>
                            </td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->campaign_id }}</td>
                            <td>{{ $customer->gc_description ? $customer->gc_description : 'EGC Purchased in the store' }}</td>
                            <td>{{ $customer->gc_value }}</td>
                            <td>{{ $customer->gclists }}</td>
                            <td>{{ $customer->invoice_number }}</td>
                            <td>{{ $customer->pos_terminal }}</td>
                            <td>
                                @if ($customer->accounting_is_audit == 1)
                                <span class="label" style="background-color: rgb(31,114,183); color: white; font-size: 12px;">CLOSED</span>
                                @else
                                <span class="label" style="background-color: rgb(74,222,128); color: white; font-size: 12px;">CLAIMED</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                </tbody> --}}

            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#myTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('get_gc_list') }}",
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return `
                            <a class="btn btn-xs btn-primary" href='{{ CRUDBooster::mainpath("edit") }}/${full.id}?campaign_types_id=${full.campaign_types_id}&uploaded_img=${full.uploaded_img}'>
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-xs btn-warning" href='{{ CRUDBooster::mainpath("detail") }}/${full.id}?campaign_types_id=${full.campaign_types_id}&uploaded_img=${full.uploaded_img}'>
                                <i class="fa fa-eye"></i>
                            </a>
                        `;
                    }
                },
                { 
                    data: 'uploaded_img', 
                    name: 'uploaded_img',
                    render: function(data, type, full, meta) {
                        // You might need to adjust the path or structure based on your setup
                        var imageUrl = '{{ asset("uploaded_item/img/") }}' + '/' + data;
                        return '<img src="' + imageUrl + '" style="max-height: 100px; max-width: 120px;">';
                    }
                },                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'campaign_id', name: 'campaign_id' },
                { 
                    data: 'gc_description', 
                    name: 'gc_description',
                    render: function(data, type, full, meta){
                        return data ? data : 'EGC Purchased in the store';
                    }
                },
                { data: 'gc_value', name: 'gc_value' },
                { data: 'gclists', name: 'gclis ts' },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'pos_terminal', name: 'pos_terminal' },
                {
                    data: 'accounting_is_audit',
                    name: 'accounting_is_audit',
                    render: function(data, type, full, meta) {
                        if (data == 1) {
                            return '<span class="label" style="background-color: rgb(31,114,183); color: white; font-size: 12px;">CLOSED</span>';
                        } else {
                            return '<span class="label" style="background-color: rgb(74,222,128); color: white; font-size: 12px;">CLAIMED</span>';
                        }
                    }
                }
            ]
        });
    });

</script>

@endsection