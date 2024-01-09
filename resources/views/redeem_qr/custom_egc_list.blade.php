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

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        }

    </style>
@endpush
@section('content')

<div class='panel panel-default'>
    <div class='panel-heading'>EGC List</div>
    <div class='panel-body'>
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Uploaded Img</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Campaign ID</th>
                    <th>GC Description</th>
                    <th>GC Value</th>
                    <th>GC Reference Number</th>
                    <th>POS Invoice Number</th>
                    <th>POS Terminal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td>
                            <button class="btn btn-xs btn-primary">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-xs btn-warning">
                                <i class="fa fa-eye"></i>
                            </button>
                        </td>
                        <td>{{ $customer->uploaded_img }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->campaign_id }}</td>
                        <td>{{ $customer->gc_description }}</td>
                        <td>{{ $customer->gc_value }}</td>
                        <td>{{ $customer->invoice_number }}</td>
                        <td>{{ $customer->pos_terminal }}</td>
                        <td>{{ $customer->status }}</td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
        $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true
        });
    });
</script>

@endsection