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

        /* th, td {
            padding: 10px;
            border: 1px solid #bbbbbb;
            text-align: center;
            font-size: 12px;
        } */

        .sorting{
            text-align: center !important;
        }

        /* th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        } */

        .responsive-table {
            overflow: auto;
            padding-bottom: 10px;
        }

        .responsive-table table {
            width: 100% !important;
            border-collapse: collapse;
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            clear: both;
            border: 1px solid #ddd;
            color: #333;
            font-size: 12px;
            font-weight: normal;
        }

        table.dataTable thead th {
            font-weight: bold;
            background-color: #605CA8;
            color: #ffffff;
            border: 1px solid #ddd;
            padding: 1px;
        }

        table.dataTable tbody td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table.dataTable tbody tr:hover{
            background-color: #e0e0e0;
        }

        table.dataTable tfoot th {
            font-weight: bold;
            background-color: #f2f2f2;
            color: #333;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #statusFilter {
            padding: 5px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

    </style>
@endpush
@section('content')

<div class='panel panel-default'>
    {{-- <div class='panel-heading'>EGC List</div> --}}
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

        lightbox.option({
            disableScrolling: true
        })

        let table = $('#myTable').DataTable({
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
                        if("{{ in_array(CRUDBooster::myPrivilegeId(), [1,2,4,5,6]) }}"){
                            return `
                                <a class="btn btn-xs btn-primary" href='{{ CRUDBooster::mainpath("edit") }}/${full.id}?campaign_types_id=${full.campaign_types_id}&uploaded_img=${full.uploaded_img}'>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-xs btn-warning" href='{{ CRUDBooster::mainpath("detail") }}/${full.id}?campaign_types_id=${full.campaign_types_id}&uploaded_img=${full.uploaded_img}'>
                                    <i class="fa fa-eye"></i>
                                </a>
                            `;
                        }else{
                            return `
                                <a class="btn btn-xs btn-warning" href='{{ CRUDBooster::mainpath("detail") }}/${full.id}?campaign_types_id=${full.campaign_types_id}&uploaded_img=${full.uploaded_img}'>
                                    <i class="fa fa-eye"></i>
                                </a>
                            `
                        }

                    }
                },
                { 
                    data: 'uploaded_img', 
                    name: 'uploaded_img',
                    render: function(data, type, full, meta) {
                        // You might need to adjust the path or structure based on your setup
                        var imageUrl = '{{ asset("uploaded_item/img/") }}' + '/' + data;
                        return '<a href="' + imageUrl + '" data-lightbox="image-1" data-title="' + imageUrl + '"><img src="' + imageUrl + '" style="max-height: 100px; max-width: 120px;"></a>';
                    }
                },                
                { 
                    data: 'name', 
                    name: 'name',
                },
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
                { data: 'gclists', name: 'gclists' },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'pos_terminal', name: 'pos_terminal' },
                {
                    data: 'accounting_is_audit',
                    name: 'accounting_is_audit',
                    render: function(data, type, full, meta) {
                        if (data == 'CLOSED') {
                            return '<span class="label" style="background-color: rgb(31,114,183); color: white; font-size: 12px;">CLOSED</span>';
                        } else {
                            return '<span class="label" style="background-color: rgb(74,222,128); color: white; font-size: 12px;">CLAIMED</span>';
                        }
                    }
                }
            ],
        });

        $(document).on('change', '#statusFilter', function() {
        let  status = $(this).val();
            if (status === 'CLOSED') {
                table.column(11).search('CLOSED').draw();
            } else if (status === 'CLAIMED') {
                table.column(11).search('CLAIMED').draw();
            } else {
                table.column(11).search('').draw();
            }
        });
        
        let status = `
            <label for="statusFilter">Status:</label>
            <select  id="statusFilter">
                <option value="">All</option>
                <option value="CLAIMED">Claimed</option>
                <option value="CLOSED">Closed</option>
            </select>`
        
        $('#myTable_filter').prepend(status);
        });

    $()

</script>

@endsection