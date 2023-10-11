<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('content')
  <!-- Your html goes here -->
  <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Redeem Code Home</a></p>
  <div class='panel panel-default'>
    <div class='panel-heading'>Edit Form</div>
    <div class='panel-body'>      
        <div class='form-group'>
          <table id='table-detail' class='table table-striped'>
          <tr>
            <td class="label-bold" style="width: 25%;">Name</td>
            <td>{{ $row->name }}</td>
          </tr>
          <tr>
            <td class="label-bold">Phone</td>
            <td>{{ $row->phone }}</td>
          </tr>
          <tr>
            <td class="label-bold">Email</td>
            <td><a href='mailto:hills.velda@schiller.com' target="_blank">{{ $row->email }}</a></td>
          </tr>
          <tr>
            <td class="label-bold">Campaign ID</td>
            <td>{{ $row->campaign_id }}</td>
          </tr>
          <tr>
            <td class="label-bold">GC Description</td>
            <td>{{ $row->gc_description }}</td>
          </tr>
          <tr>
            <td class="label-bold">GC Value</td>
            <td>{{ $row->gc_value }}</td>
          </tr>
          <tr>
            <td class="label-bold">Number of GCs</td>
            <td>{{ $row->batch_number }}</td>
          </tr>
          <tr>
            <td class="label-bold">Goverment ID#</td>
            <td>{{ $row->valid_ids }}</td>
          </tr>
          <tr>
            <td class="label-bold">GC Reference #</td>
            <td>{{ $row->campaign_id.' - '.$row->qr_reference_number }}</td>
          </tr>
          <tr>
            <td class="label-bold">Invoice #</td>
            <td>{{ $row->invoice_number }}</td>
          </tr>
          <tr>
            <td class="label-bold">Redeemed</td>
            @if ($row->redeem)
            <td>Yes</td>
            @else
            <td>No</td>
            @endif
          </tr>
          </table>
        </div>
                
      </form>
    </div>
  </div>
@endsection