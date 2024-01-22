<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    {{-- Summernote --}}
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/summernote/summernote.css')}}">
    {{-- Jquery --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> --}}
    {{-- Css --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/timeline.css') }}">
    {{-- Swal --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .swal2-popup {
            font-size: 17px !important;
            color: rgb(0, 0, 0) !important;
        }

        .cb-header{
            /* margin-bottom: 15px; */
            margin-right: 15px;
            font-weight: bold;
            font-size: 2rem;
        }

        body.swal2-height-auto{
            height: 100% !important;
        }

        .container-res{
            position: relative;
            display: flex;
            flex-wrap:  wrap;
            margin-bottom: 15px;
        }

        .store-modal-bg-dark{
            /* position: absolute; */
            height: 100vh;
        }

        .store-modal{
            /* position: relative; */
            top: 50%;
            max-width: 70rem !important;
        }
    </style>
@endpush

@section('content')
    <!-- Your html goes here -->
    <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Home</a></p>
    <div class='panel panel-default'>
        <div class='panel-heading'>Edit Form</div>
        <form method='post' action='{{ $customer->store_status == 1 ? route('pending_invoice') : route('pending_oic') }} ' autocomplete="off">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input class="hide" type="text" name="id" value="{{ $customer->id }}">
        <div class='panel-body'>
            <div class="container-res">
                <div class="cb-header">
                    Customer Information
                </div>
                @if ($customer->store_status > 2)
                <button class="btn btn-primary" type="button" id="show-history">
                    Show History
                </button>
                @endif
            </div>
            <table class="custom_table">
                <tbody>
                    <tr>
                        <td>First Name:</td>
                        <td><input class="form-control inputs" type="text" name="cus_first_name" value="{{ $customer->cus_first_name }}" readonly></td>
                        <td>Last Name:</td>
                        <td><input class="form-control inputs" type="text" name="cus_last_name" value="{{ $customer->cus_last_name }}" readonly></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input class="form-control inputs" type="text" name="cus_email" value="{{ $customer->cus_email }}" readonly></td>
                        <td>Contact:</td>
                        <td><input class="form-control inputs" type="text" name="cus_contact_number" value="{{ $customer->cus_phone }}" readonly></td>
                    </tr>
                </tbody>
            </table>
            @if(!($customer->name == $customer->cus_name && $customer->email == $customer->cus_email))
            <br>
            <div class="cb-header">
                EGC Recipient
            </div>
            <table class="custom_table">
                <tbody>
                    <tr>
                        <td>First Name:</td>
                        <td><input class="form-control inputs" type="text" name="first_name" value="{{ $customer->first_name }}" readonly></td>
                        <td>Last Name:</td>
                        <td><input class="form-control inputs" type="text" name="last_name" value="{{ $customer->last_name }}" readonly></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input class="form-control inputs" type="text" name="email" value="{{ $customer->email }}" readonly></td>
                        <td>Contact:</td>
                        <td><input class="form-control inputs" type="text" name="contact_number" value="{{ $customer->phone }}" readonly></td>
                    </tr>
                </tbody>
            </table>
            @endif
        <br>
        <table class="custom_normal_table">
            <tbody>
                <tr>
                    <td>Branch</td>
                    <td>{{ $customer->store_branch }}</td>
                    <td>Concept:</td>
                    <td>{{ $customer->store_concept }}</td>
                </tr>
                <tr>
                    <td>EGC Value:</td>
                    <td>
                        <select class="form-control" name="egc_value" id="egc_value" required>
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($egcs as $egc)
                            @if ($customer->egc_value_id == $egc->id)
                                <option value="{{ $egc->value }}" selected>{{ $egc->name }}</option>
                            @else
                                <option value="{{ $egc->value }}">{{ $egc->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Invoice Number:</td>
                    <td><input class="form-control" type="text" name="store_invoice_number" value="{{ $customer->store_invoice_number }}" required></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

    </div>
    </form>
    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">Cancel</a>
    </div>

    <div class="store-modal-bg-dark">
        <div class="store-modal">
            <div class="store-modal-header">History</div>
            <br>
            <div class="timeline">
                <div class="container1 right">
                  <div class="content1">
                    <p>
                        <span style="font-weight: bold;">Created at: {{ \Carbon\Carbon::parse($original_history->created_at)->format('Y-m-d H:i') }} (Original)</span>
                    </p>
                    </table>
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between">
                        <div>
                            <span style="font-weight: bold;">Customer Information</span>
                            <ul>
                                <li><span style="color: #2a9bb1; font-weight: 600;">Name: </span>{{ $original_history->customer_first_name }}</li>
                                <li><span style="color: #2a9bb1; font-weight: 600;">Email: </span>{{ $original_history->customer_email }}</li>
                                <li><span style="color: #2a9bb1; font-weight: 600;">Phone: </span>{{ $original_history->customer_phone }}</li>
                            </ul>
                        </div>
                        <div>
                            <span style="font-weight: bold;">EGC Recipient</span>
                            <ul>
                                <li><span style="color: #2a9bb1; font-weight: 600;">Name: </span>{{ $original_history->egc_name }}</li>
                                <li><span style="color: #2a9bb1; font-weight: 600;">Email: </span>{{ $original_history->egc_email }}</li>
                                <li><span style="color: #2a9bb1; font-weight: 600;">Phone: </span>{{ $original_history->egc_phone }}</li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <span style="color: #000000; font-weight: 600;">EGC Value: </span>{{ $original_history->egc_value_id }}
                    </div>
                  </div>
                </div>
                
                @for ($i=0; $i < count($customer_information); $i++)
                <div class="container1 right">
                    <div class="content1">
                        <p style="font-weight: bold;">Updated at {{ \Carbon\Carbon::parse($history[$i]['created_at'])->format('Y-m-d H:i') }}</p>
                        <p>
                            <ul>
                                @foreach ($customer_information[$i] as $key => $value)
                                <li>{{ ucfirst(str_replace('_', ' ', $key)).": $value" }}</li>
                                @endforeach
                            </ul>
                        </p>
                        <p>Updated By: {{ $history[$i]['created_by'] }}</p>
                    </div>
                </div>
                @endfor
            </div>
            <button class="btn btn-default" id="btn-cancel">Close</button>
        </div>
    </div>

    <script>

        historyModal();
        
        $('input,select').attr('disabled', true);

        function historyModal(){

            $('#show-history').on('click', function(){
                $('body').css('overflow', 'hidden');
                $('.store-modal-bg-dark').show();
                $('.store-modal').show();
            })

            $('#btn-cancel').on('click', function(){
                $('.store-modal-bg-dark').hide();
                $('.store-modal').hide();
                $('body').css('overflow', 'auto');
            })
        }

    </script>
@endsection

@push('bottom')
    <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.min.js')}}"></script>
@endpush