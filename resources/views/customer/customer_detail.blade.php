<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    {{-- Jquery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    {{-- Css --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- Swal --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .swal2-popup {
            font-size: 17px !important;
            color: rgb(0, 0, 0) !important;
        }

        .cb-header{
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 2rem;
        }

        body.swal2-height-auto{
            height: 100% !important;
        }
    </style>
@endpush

@section('content')
    <!-- Your html goes here -->
    <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Home</a></p>
    <div class='panel panel-default'>
        <div class='panel-heading'>Edit Form</div>
        <form method='post' action='{{ $customer->store_status == 1 ? route('pending_invoice') : route('pending_oic') }} ' autocomplete="off">
        @csrf
        <input class="hide" type="text" name="id" value="{{ $customer->id }}">
        <div class='panel-body'>
            <div class="cb-header">
                Customer Information
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
        </form>
        <br>
        <table class="custom_normal_table">
            <tbody>
                <tr>
                    <td>Concept:</td>
                    <td>{{ $customer->store_concept }}</td>
                    <td></td>
                    <td></td>
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
        <button class="hide" id="btn-submit" type="submit">submit</button>
    </div>
    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">Cancel</a>
    </div>

    <script>
    $(document).ready(function() {

        $('input,select').attr('disabled', true);

        if("{{ $customer->store_status == 2 }}"){
            $('input').attr('readonly', false);
        }
        
        $('#btn-fake').on('click', function(){
            const btnText = $(this).val();
            console.log(btnText)
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: `Yes, ${btnText.toLowerCase()} it!`,
                reverseButtons: true,
                returnFocus: false
                }).then((result) => {
                if (result.isConfirmed) {
                    $('#btn-submit').click();
                }
            });  
        })
        
    });


    </script>
@endsection

@push('bottom')
    {{-- <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.min.js')}}"></script> --}}
@endpush