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

        .form-inputs-file-type{
            margin: 3px 20px;
            width: 600px;
            flex-grow: 1;
        }

        .form-inputs label{
            font-size: 15px;
            width: 130px;
            display:block;
        }

        .input{
            width: 100%;
            height: 35px;
            border: 1px solid #c7c5c5;
            border-radius: 5px;
            outline: none;
            padding: 0 10px;
        }

        .input::placeholder{
            font-size: 15px;
        }

        .input:focus {
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
            background-color: rgb(31,114,183);
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
            margin: 3px 0px;
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

        /* select2 single */
        .select2-container--default .select2-selection--single {
            height: 35px;
        }

        /* Target the Select2 placeholder */
        .select2-container .select2-selection--single .select2-selection__placeholder {
            font-size: 15px;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 35px;
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
            padding-top: 2px;
            padding-bottom: 6px;
            padding-left: 5px;
        }

        .swal2-popup {
            font-size: 17px !important;
            color: rgb(0, 0, 0) !important;
        }

        /* Loading Animation */
        .sk-chase-position{
            position: absolute;
            top: 47%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 100%;
            width: 100%;
            display: grid;
            place-items: center;
            z-index: 9999;
            background-color: #21212172;
        }

        .sk-chase {
            width: 100px;
            height: 100px;
            position: relative;
            animation: sk-chase 2.5s infinite linear both;
        }

        .sk-chase-text{
            position: absolute;
            top: 60%;
            color: white;
        }

        .sk-chase-text{
            font-weight: bold;
            font-size: 15px;
        }

        .sk-chase-dot {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0; 
            animation: sk-chase-dot 2.0s infinite ease-in-out both; 
        }

        .sk-chase-dot:before {
            content: '';
            display: block;
            width: 25%;
            height: 25%;
            background-color: #fff;
            border-radius: 100%;
            animation: sk-chase-dot-before 2.0s infinite ease-in-out both; 
        }

        .sk-chase-dot:nth-child(1) { animation-delay: -1.1s; }
        .sk-chase-dot:nth-child(2) { animation-delay: -1.0s; }
        .sk-chase-dot:nth-child(3) { animation-delay: -0.9s; }
        .sk-chase-dot:nth-child(4) { animation-delay: -0.8s; }
        .sk-chase-dot:nth-child(5) { animation-delay: -0.7s; }
        .sk-chase-dot:nth-child(6) { animation-delay: -0.6s; }
        .sk-chase-dot:nth-child(1):before { animation-delay: -1.1s; }
        .sk-chase-dot:nth-child(2):before { animation-delay: -1.0s; }
        .sk-chase-dot:nth-child(3):before { animation-delay: -0.9s; }
        .sk-chase-dot:nth-child(4):before { animation-delay: -0.8s; }
        .sk-chase-dot:nth-child(5):before { animation-delay: -0.7s; }
        .sk-chase-dot:nth-child(6):before { animation-delay: -0.6s; }

        @keyframes sk-chase {
        100% { transform: rotate(360deg); } 
        }

        @keyframes sk-chase-dot {
        80%, 100% { transform: rotate(360deg); } 
        }

        @keyframes sk-chase-dot-before {
        50% {
            transform: scale(0.4); 
        } 100%, 0% {
            transform: scale(1.0); 
        } 
        }
    </style>
@endpush
@section('content')
    <!-- Your html goes here -->
    <div class="sk-chase-position" style="display: none;">
        <div class="sk-chase">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>
        <div class="sk-chase-text">
            <p>Please wait, system is on process...</p>
        </div>
    </div>

    <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Campaign Creation Home</a></p>
    <div class='panel panel-default'>
        <div class='panel-heading'>Add Form</div>
        <form method='post' action='{{ route('add_campaign_ihc') }}' autocomplete="off" enctype="multipart/form-data">
            @csrf
            <input type="text" class="hide" name="id" value="{{ $qr_creation->id }}">
            <div class='panel-body'>
                <div class="for_approval">
                    <div class="for_approval_content">
                        @if($qr_creation->campaign_status)
                        <label for="" class="label_status">Status:</label>
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
                    {{-- @if ($qr_creation->campaign_status == 2 || $qr_creation->campaign_status == 3)     
                    <div class="for_approval_content" style="max-width: 200px;">
                        <label for="">Billing Number</label>
                        <input type="text" name="billing_number" placeholder="Input billing number" value="{{ $qr_creation->billing_number }}" {{ (Request::segment(3) == 'detail') ? 'disabled' : '' }} required>
                    </div>
                    @endif --}}
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-resposive">
                            <table class="table default-table-design">
                                <tr>
                                    {{-- <label for=""><span class="required">*</span> Campaign ID:</label> --}}
                                    <td class="text-center table-label" ><span class="required">*</span> Campaign ID:</td>
                                    <td style="width: 200px;">
                                        <input class="input" type="text" name="campaign_id" placeholder="Enter Campaign ID" required>
                                    </td>
                                    <td class="text-center table-label"><span class="required">*</span> GC Description:</td>
                                    <td style="width: 200px;">
                                        <input class="input" type="text" name="gc_description" placeholder="Enter GC Description" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center table-label"><span class="required">*</span> GC Value:</td>
                                    <td style="width: 200px;">
                                        <input class="input" type="number" name="gc_value" placeholder="Enter GC Value" required>
                                    </td>
                                    <td class="text-center table-label"><span class="required">*</span> Number of GCs:</td>
                                    <td style="width: 200px;">
                                        <input class="input" type="number" name="batch_number" placeholder="Number of Gcs" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center table-label"><span class="required">*</span> Start Date:</td>
                                    <td style="width: 200px;">
                                        <input class="input" type="date" name="start_date" value="{{ $qr_creation->start_date }}" required>
                                    </td>
                                    <td class="text-center table-label"><span class="required">*</span> Expiry Date:</td>
                                    <td style="width: 200px;">
                                        <input class="input" type="date" name="expiry_date" value="{{ $qr_creation->expiry_date  }}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center table-label"><span class="required">*</span> Store Logo:</td>
                                    <td style="width: 200px;">
                                        <select id="store_logo" name="store_logo" required>
                                            <option value="" disabled required selected></option>
                                            @foreach ($store_logo as $store)
                                            <option value="{{ $store->id }}" {{ $qr_creation->store_logo == $store->id ? 'selected':'' }}>{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center table-label"><span class="required">*</span> Charge To:</td>
                                    <td>
                                        <select id="charge_to" name="charge_to" required>
                                            <option value="" disabled required selected></option>
                                            @foreach ($charge_to as $ct)
                                            <option value="{{ $ct->id }}" {{ $qr_creation->charge_to == $ct->id ? 'selected':'' }}>{{ $ct->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @if (Request::segment(3) == 'getAddIhc')
                        <table class="table default-table-design">
                            <tr>
                                <td class="text-center table-label">Excluded Concept:</td>
                                <td>
                                    <select class="excluded_concept" id="excluded_concept" multiple>
                                        <option value="1">DW</option>
                                        <option value="2">BTB</option>
                                    </select>                                
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center table-label" >Excluded Stores:</td>
                                <td>
                                    <select class="store_concept" id="store_concept" name="stores[]" multiple>
                                        @if (!$qr_creation->campaign_id)
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" charge_to="{{ $store->concept }}">{{ $store->beach_name }}</option>
                                        @endforeach
                                        @else
                                        @foreach ($stores as $store)
                                        <option value="{{ $store->id }}" {{ in_array($store->id,explode(',',$qr_creation->number_of_gcs)) ? 'selected':'' }}>{{ $store->beach_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>                                
                                </td>
                            </tr>
                        </table>
                        @else
                        <div>
                            @if (Request::segment(3) == 'edit')
                            <div style="font-weight: bold; font-size: 15px;">Excluded Stores:</div>
                            @else
                            <div style="font-weight: bold; font-size: 15px;">Excluded Stores:</div>
                            @endif
                            <div style="display: flex; flex-wrap: wrap;">
                                @foreach ($stores1 as $store)
                                    <span style="margin: 5px 5px 5px 0px; background-color: #605CA8; color: white; padding: 5px; ">{{ $store->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if (Request::segment(3) == 'getAddIhc' )
                <div class="row">
                    <div class="col-md-12">
                        <div class="file-upload-content">
                            <label for=""><span class="required">*</span>Attach Memo</label>
                            <input type="file" name="memo_attachment" accept=".pdf" multiple required>
                        </div>
                    </div>
                </div>
                <br>
                @endif


                <div class="form-inputs">
                    @if($errors->has('campaign_id'))
                    <p style="color: red;">{{ $errors->first('campaign_id') }}</p>
                    @endif
                </div>
                @if ($qr_creation->memo_attachment)
                
                <div class="text-center">
                <br>
                    <div class="file_title">
                        Memo Attachment
                    </div>
                    <iframe src="{{ asset("uploaded_memo/file/$qr_creation->memo_attachment") }}" width='100%' height='650' frameborder='0'></iframe>
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
        
    
    <script type="text/javascript">

        // $('.store_concept').select2({
        //     placeholder: "Select a Store",
        //     dropdownAutoWidth: true,
        //     width: '100%',
        //     ajax: {
        //         url: '{{ route('getStores') }}',
        //         dataType: 'json',
        //         delay: 250,
        //         type: 'POST',
        //         data: function (params) {
        //             console.log(params.term)
        //             return {
        //                 term: params.term,
        //                 _token: '{!! csrf_token() !!}'
        //             };
        //         },
                
        //         processResults: function (data) {
        //             console.log(data)

        //             return {
        //                 results: $.map(data, function (item) {
                            
        //                     return {
        //                         text: item.name,
        //                         id: item.id
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     },
        //     id: 'id'
        // })

            $('.store_concept').select2({
                width: '100%',
                placeholder: 'Select Stores'
            });

            $('.excluded_concept').select2({
                width: '100%',
                placeholder: 'Select Store Concept'
            })

            $('#store_logo').select2({
                width: '100%',
                placeholder: 'Select Store Logo'
            });
        
            $('#charge_to').select2({
                width: '100%',
                placeholder: 'Select Charge To'
            });
        
    
        
            // $('#store_concept option[charge_to="2"]').attr('selected', true);
            // $('#store_concept').trigger('change');
            // $('#excluded_concept').on('change', function(e){

            //     let charge_id = $(this).val();
            //     console.log(charge_id);
            //     $(`#store_concept option[charge_to="${charge_id}"]`).attr('selected', true);
            //     // $('#store_concept').trigger('change');
            // })

            $('#excluded_concept').on('change', function (e) {

                const options = $(this).find('option').get();
                options.forEach(option => {
                    const value = $(option).text();
                    console.log(value);
                    if ($(option).is(':selected')) {
                        $(`#store_concept option[charge_to="${value}"]`).attr('selected', true);
                    } else {
                        $(`#store_concept option[charge_to="${value}"]`).attr('selected', false);
                    }
                })

                // Trigger the 'change' event to update the Select2 display
                $('#store_concept').trigger('change');
            });


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
                // console.log($("input[name='billing_number']").val() == '');
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
                        if($("input[name='billing_number']").val() != ''){
                            $('.sk-chase-position').show();
                        }
                        $('#approve_form').click();
                    }
                })
            });

    </script>
@endsection
