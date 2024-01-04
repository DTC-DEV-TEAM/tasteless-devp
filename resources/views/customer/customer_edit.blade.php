<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    {{-- Summernote --}}
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/summernote/summernote.css')}}">
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
    </style>
@endpush

@section('content')
    <!-- Your html goes here -->
    <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Home</a></p>
    <div class='panel panel-default'>
        <div class='panel-heading'>Edit Form</div>
        <form method='post' action='' autocomplete="off">
        @csrf
        <div class='panel-body'>
            <div class="cb-header">
                Customer Information
            </div>
            <table class="custom_table">
                <tbody>
                    <tr>
                        <td>First Name:</td>
                        <td><input class="form-control" type="text" name="first_name" value="{{ $customer->first_name }}" readonly></td>
                        <td>Last Name:</td>
                        <td><input class="form-control" type="text" name="last_name" value="{{ $customer->last_name }}" readonly></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input class="form-control" type="text" name="email" value="{{ $customer->email }}" readonly></td>
                        <td>Contact:</td>
                        <td><input class="form-control" type="text" name="contact_number" value="{{ $customer->phone }}" readonly></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <br>
        <table class="custom_normal_table">
            <tbody>
                <tr>
                    <td>Concept</td>
                    <td>{{ $customer->store_concept }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <td>EGC Value</td>
                <td>
                    <select class="form-control" name="egc_value" id="egc_value">
                        <option value=""></option>
                    </select>
                </td>
                <tr>
                    <td>Input Invoice Number:</td>
                    <td><input class="form-control" type="text" name="invoice_number" value=""></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button class="hide" id="btn-submit" type="submit">submit</button>
    </div>
    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">Cancel</a>
        <input type='button' class='btn btn-primary pull-right' id='btn-fake' value='Submit'/>
    </div>

    <script>
    $(document).ready(function() {

        
        $('#btn-fake').on('click', function(){
            const btnText = $(this);

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: `Yes, ${btnText.text()} it!`,
                reverseButtons: true
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
    <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.min.js')}}"></script>
@endpush