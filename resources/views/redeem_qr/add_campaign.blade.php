<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@push('head')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        
        .form-input-content{
            display: flex;
            flex-wrap: wrap
        }

        .form-inputs{
            margin: 3px 20px;
            width: 600px;
            flex-grow: 1;
        }

        .form-inpunts-file-type{
            margin: 3px 20px;
            width: 600px;
            flex-grow: 1;
        }

        .form-inputs label{
            width: 130px;
            display:block;
        }

        .form-inputs input{
            width: 100%;
            width: 100%;
            height: 35px;
            border: 1px solid #c7c5c5;
            border-radius: 5px;
            outline: none;
            padding: 0 10px;
        }

        .form-inputs input:focus {
            border: 2px solid #605CA8;
        }

        .form-inputs select{
            width: 100%;
            height: 35px;
            border-radius: 5px;
            outline: none;
            padding: 0 10px;  
            border: 1px solid #c7c5c5;
        }

        /* select:invalid {
            color: #999;
        } */

        .for_approval_head{
            padding: 5px 10px;
            border-radius: 5px;
            background-color: rgb(34 211 238);
            color: white;
        }

        .approve{
            padding: 5px 10px;
            border-radius: 5px;
            background-color: rgb(74 222 128);
            color: white;
        }

        .approve_accounting{
            padding: 5px 10px;
            border-radius: 5px;
            background-color: rgb(251 146 60);
            color: white;
        }

        .rejected{
            padding: 5px 10px;
            border-radius: 5px;
            background-color: rgb(239 68 68);
            color: white;
        }

        .for_approval{
            display: flex;
            margin: 3px 20px;
            flex-wrap: wrap;

        }

        .for_approval_content{
            margin-right: 20px;
            margin-bottom: 20px;
        }

        .for_approval_content .label_status{
            display: block;
            margin-bottom: 9px;
        }

        input[name="billing_number"]{
            height: 35px;
            border: 1px solid #c7c5c5;
            width: 100%;
            max-width: 185px;
            padding: 0 5px;
            outline: none;
            text-align: center;
        }

        input[name="billing_number"]:focus{
            border: 2px solid #605CA8;
        }

        .file-upload-content input[type="file"]::after {
            content: "Choose a PDF File";
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #E6E6FA; 
            color: #000000; 
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
            color: #000000;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #c7c5c5;
        }

        .swal2-popup {
            font-size: 17px !important;
            color: rgb(0, 0, 0) !important;
        }
    </style>
@endpush
@section('content')
  <!-- Your html goes here -->
    <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Campaign Creation Home</a></p>
    <div class='panel panel-default'>
        <div class='panel-heading'>Add Form</div>
        <form method='post' action='{{ route('add_campaign') }}' autocomplete="off" enctype="multipart/form-data">
            @csrf
            <input type="text" class="hide" name="id" value="{{ $qr_creation->id }}">
            <div class='panel-body'>
                <div class="form-input-content">
                    <div class="form-inputs">
                        <label for="">Campaign ID:</label>
                        <input type="text" name="campaign_id" placeholder="Enter Campaign ID" required>
                    </div>
                    <div class="form-inputs">
                        <label for="">GC Description:</label>
                        <input type="text" name="gc_description" placeholder="Enter GC Description" required>
                    </div>
                    <div class="form-inputs">
                        <label for="">GC Value:</label>
                        <input type="number" name="gc_value" placeholder="Enter GC Value" required>
                    </div>
                    <div class="form-inputs">
                        <label for="">Number of GCs:</label>
                        <input type="number" name="batch_number" placeholder="Number of Gcs" required>
                    </div>
                    <div class="form-inputs">
                        <label for="">Batch Group:</label>
                        <input type="number" name="batch_group" placeholder="Batch Group" required>
                    </div>
                    <div class="form-inputs">
                        <label for="">Company Name:</label>
                        <select id="company_select" name="company_id" required>
                            <option value="" disabled required selected></option>
                            @foreach ($company_id as $company)
                            <option value="{{ $company->id }}" {{ $qr_creation->company_id == $company->id ? 'selected':'' }}>{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-inputs">
                        <label for="">PO Number:</label>
                        <input type="text" name="po_number" placeholder="PO number">
                    </div>
                    <div class="form-inputs f-select">
                        <label for="">Excluded Stores:</label>
                        <select class="store_concept" name="stores[]" multiple='multiple' required>
                            @if (!$qr_creation->campaign_id)
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                            @else
                            @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ in_array($store->id,explode(',',$qr_creation->number_of_gcs)) ? 'selected':'' }}>{{ $store->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-inpunts-file-type file-upload-content">
                        @if (!$qr_creation->po_attachment)
                        <label for="">PO Attachment (Optional):</label>
                        <input type="file" name="po_attachment" accept=".pdf">
                        <br>
                        @endif
                    </div>
                    <div class="form-inputs">
                        <label for="">Store Logo:</label>
                        <select id="company_select" name="store_logo" required>
                            <option value="" disabled required selected></option>
                            @foreach ($store_logo as $store)
                            <option value="{{ $store->id }}" {{ $qr_creation->store_logo == $store->id ? 'selected':'' }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="for_approval">
                    <div class="for_approval_content">
                        @if($qr_creation->campaign_status)
                        <label for="" class="label_status">Status</label>
                        @endif
                        @if ($qr_creation->campaign_status == 1)            
                        <span class="for_approval_head">{{ $qr_creation->campaign_status_name }}</span>
                        @elseif($qr_creation->campaign_status == 2)
                        <span class="approve_accounting">{{ $qr_creation->campaign_status_name }}</span>
                        @elseif($qr_creation->campaign_status == 3)
                        <span class="approve">{{ $qr_creation->campaign_status_name }}</span>
                        @elseif($qr_creation->campaign_status == 4)
                        <span class="rejected">{{ $qr_creation->campaign_status_name }}</span>
                        @endif
                    </div>
                    @if ($qr_creation->campaign_status == 2 || $qr_creation->campaign_status == 3)     
                    <div class="for_approval_content" style="max-width: 200px;">
                        <label for="">Billing Number</label>
                        <input type="text" name="billing_number" placeholder="Input billing number" value="{{ $qr_creation->billing_number }}" {{ (Request::segment(3) == 'detail') ? 'disabled' : '' }} required>
                    </div>
                    @endif
                </div>
                <div class="form-inputs">
                    @if($errors->has('campaign_id'))
                    <p style="color: red;">{{ $errors->first('campaign_id') }}</p>
                    @endif
                </div>
                @if ($qr_creation->po_attachment)
                <div class="text-center">
                <br>
                    <label for="">PO Attachment</label>
                    <iframe src="{{ asset("uploaded_po/file/$qr_creation->po_attachment") }}" width='100%' height='650' frameborder='0'></iframe>
                </div>
                @endif
            </div>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default" style="border: 1px solid #ddd;">Cancel</a>
                <input type='submit' class='btn btn-primary hide' id="submit_form" value='Save changes'/>
                <input type='submit' class='btn btn-primary hide' id="reject_form" name="reject" value='Reject'/>
                <input type='submit' class='btn btn-primary hide' id="approve_form" name="approve" value='Approve'/>
                @if($qr_creation->campaign_status == null)
                <button type="button" class='btn btn-primary' id="submit_btn">Submit</button>
                @else
                @if (!(Request::segment(3) == 'detail') && ($qr_creation->campaign_status != 3 && $qr_creation->campaign_status != 4))          
                <button type="button" class='btn btn-success' style="float: right; margin-left: 5px;" id="approve_btn">Approve</button>
                <button type="button" class='btn btn-danger' style="float: right;" id="reject_btn">Reject</button>
                @endif
                @endif
            </div>
        </form>
    </div>
    
    <script>
        // $(document).ready(function(){

            function campaignCreationDetails(){

                if("{{ $qr_creation->campaign_id }}"){
                    
                    $('.panel-heading').text('Details');
                    $('input').css('background-color', '#eee');
                    $('input').attr('readonly', true);
                    $('select').css('background-color', '#eee');
                    $('select').attr('disabled', true);
                    $('input[name="campaign_id"]').val('{{ $qr_creation->campaign_id }}');
                    $('input[name="gc_description"]').val('{{ $qr_creation->gc_description }}');
                    $('input[name="gc_value"]').val('{{ $qr_creation->gc_value }}');
                    $('input[name="batch_number"]').val('{{ $qr_creation->batch_number }}');
                    $('input[name="batch_group"]').val('{{ $qr_creation->batch_group }}');
                    $('input[name="po_number"]').val('{{ $qr_creation->po_number }}');
                }

                if("{{ $qr_creation->campaign_status == 2 }}"){
                    $('input[name="billing_number"]').attr('readonly', false);
                    $('input[name="billing_number"]').css('background-color', '#fff');
                }

            }

            campaignCreationDetails()

            $('.store_concept').select2({
                width: '100%',
            });

            $('#submit_btn').click(function(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!',
                    returnFocus: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#submit_form').click();
                    }
                })
            });

            $('#reject_btn').click(function(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!',
                    returnFocus: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('form').attr("action", "{{ route('campaign_approval') }}");
                        $('#reject_form').click();
                    }
                })
            });

            $('#approve_btn').click(function(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!',
                    returnFocus: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('form').attr("action", "{{ route('campaign_approval') }}");
                        $('#approve_form').click();
                    }
                })
            });

            
        // });
    </script>
@endsection