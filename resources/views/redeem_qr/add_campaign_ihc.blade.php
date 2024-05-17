<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@push('head')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">

    <style>

        /* File upload container */
.file-upload {
    position: relative;
    overflow: hidden;
    display: inline-block;
}

/* File upload label */
.file-upload-label {
    display: inline-block;
    font-weight: bold;
}

/* File input */
.file-upload input[type="file"] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

/* File upload icon */
.file-upload-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 10px;
    color: #007bff; /* Change to your desired icon color */
    transition: color 0.3s ease;
}

/* File upload text */
.file-upload-text {
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: calc(100% - 50px); /* Adjust as needed */
}

/* File upload hover state */
.file-upload:hover .file-upload-icon {
    color: #0056b3; /* Change to your desired hover color */
}

        
    .form-input-content{
        display: flex;
        flex-wrap: wrap;
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

    .scrollable-wrapper {
        width: 100%; /* Set a fixed width or a percentage width */
        overflow-x: auto; /* Enable horizontal scrolling */
        white-space: nowrap; /* Prevent line breaks */
    }

    /* Optional: Adjust the max-width to fit the content */
    .scrollable-wrapper select {
        max-width: 100%; /* Adjust as needed */
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
        <form id="form-sbmt" method='post' action='{{ route('add_campaign_ihc') }}' autocomplete="off" enctype="multipart/form-data">
            @csrf
            <input type="text" class="hide" name="qr_creations_id" value="{{ $qr_creation->qr_creations_id }}">
            <input type="text" class="hide" name="id" value="{{ $qr_creation->id }}">
            <div class='panel-body'>
                <div class="for_approval">
                    <div class="for_approval_content u-flex">
                        <div class="u-mr-32">
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
                        <div class="{{ !$qr_creation->qr_type ? 'hide' : '' }}">
                            <p class="u-fw-b">QR Type:</p>
                            @if($qr_creation->qr_type == 1)
                                <span class="approve">GIFT CODE</span>
                                @else
                                <span class="approve">QR CODE</span>
                            @endif 
                        </div>
                    </div>
                </div>
                <table class="custom_normal_table">
                    <tbody>
                        <tr>
                            <td>
                                <p><span class="required">*</span> Campaign ID:</p>
                                <input class="u-input" type="text" name="campaign_id" placeholder="Enter Campaign ID" required>
                            </td>
                            <td>
                                <p><span class="required">*</span> GC Description:</p>
                                <input class="u-input" type="text" name="gc_description" placeholder="Enter GC Description" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><span class="required">*</span> GC Value:</p>
                                <input class="u-input" type="number" name="gc_value" placeholder="Enter GC Value" required>
                            </td>
                            <td>
                                <p><span class="required">*</span> Number of GCs:</p>
                                <input class="u-input" type="number" name="batch_number" placeholder="Number of Gcs" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><span class="required">*</span> Store Logo:</p>
                                <select class="u-input" id="store_logo" name="store_logo" required>
                                    <option value="" disabled required selected></option>
                                    @foreach ($company_id as $store)
                                    <option value="{{ $store->id }}" {{ $qr_creation->store_logo == $store->id ? 'selected':'' }}>{{ $store->company_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <p><span class="required">*</span> Charge To:</p>
                                <select id="charge_to" name="charge_to" required>
                                    <option value="" disabled required selected></option>
                                    @foreach ($charge_to as $ct)
                                    <option value="{{ $ct->id }}" {{ $qr_creation->charge_to == $ct->id ? 'selected':'' }}>{{ $ct->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>

                @if ((Request::segment(3) == 'getAddIhc' || $qr_creation->campaign_status == 1) && Request::segment(3) != 'detail')
                <div style="overflow-x: auto;">
                    <table class="custom_normal_table">
                        <tbody>
                            <tr>
                                <td>
                                    <p>Excluded Concept:</p>
                                    <select class="excluded_concept" id="excluded_concept" name="store[]" multiple required>
                                        @if (!$qr_creation->campaign_id)
                                        @foreach ($excluded_concept as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                        @else
                                        @foreach ($excluded_concept as $store)
                                            <option value="{{ $store->id }}" {{ in_array($store->id, explode(',', $qr_creation->store)) ? 'selected' : '' }}>{{ $store->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <p>GC Type:</p>
                                    <select class="u-input" name="qr_type" id="" required>
                                        <option value="" selected disabled>Select GC Type</option>
                                        @foreach ($qr_types as $qr_type)
                                            <option value="{{ $qr_type->id }}" {{ $qr_creation->qr_type == $qr_type->id ? 'selected' : ''  }}>{{ $qr_type->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="custom_normal_table">
                        <tbody>
                            <tr>
                                <td>
                                    <p>Excluded Stores:</p>
                                    <select class="store_concept" id="store_concept" name="stores[]" multiple required>
                                        @if (!$qr_creation->campaign_id)
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}" charge_to="{{ $store->concept }}">{{ $store->beach_name }}</option>
                                            @endforeach
                                            @else
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}" charge_to="{{ $store->concept }}" {{ !in_array($store->id, explode(',', $qr_creation->number_of_gcs)) ? 'selected' : '' }}>
                                                    {{ $store->beach_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>  
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <br>
                <div>
                    <div style="font-weight: bold; font-size: 15px;">Excluded Stores:</div>
                    <div style="display: flex; flex-wrap: wrap;">
                        @foreach ($stores1 as $store)
                            <span style="margin: 5px 5px 5px 0px; background-color: #605CA8; color: white; padding: 5px; ">{{ $store->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if (Request::segment(3) == 'getAddIhc' )
                <table class="custom_normal_table">
                    <tbody>
                        <tr>
                            <td>
                                <div class="input_container" style="display: relative;">
                                    <label for="files" class="u-btn u-box-shadow-default" style="position: absolute;">Attach Memo here</label>
                                    <input id="files" name="memo_attachment" accept=".pdf" multiple required style="position: absolute; z-index: -1" type="file">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <br>
                @endif

                <div class="form-inputs">
                    @if($errors->has('campaign_id'))
                    <p style="color: red; font-weight: bold;">{{ $errors->first('campaign_id') }}</p>
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
                <input type='hidden' class='btn btn-primary' name="btnVal" id="btn-val" value=''/>
                @if (!$qr_creation->campaign_status)
                <button type="submit" class='btn btn-primary' id="submit_btn">Submit</button>
                @endif
                @if (($qr_creation->campaign_status == 1 && Request::segment(3) == 'edit') && (CRUDBooster::myPrivilegeId() == 2 || CRUDBooster::myPrivilegeId() == 1))
                    <button type="submit" class='btn btn-primary' id="save_btn">Save</button>
                @endif
                @if ((Request::segment(3) != 'detail' && Request::segment(3) != 'getAddIhc') && ($qr_creation->campaign_status != 3 && $qr_creation->campaign_status != 4)) 
                    @if(CRUDBooster::myPrivilegeId() != 2)         
                    <button type="submit" class='btn btn-success' style="float: right; margin-left: 5px;" id="approve_btn">Approve</button>
                    <button type="submit" class='btn btn-danger' style="float: right;" id="reject_btn">Reject</button>
                    @endif
                @endif
            </div>
        </form>
    </div>
        
    
    <script type="text/javascript">

        $('input, select').on('keypress', function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
            }
        })

        if("{{ !$qr_creation->campaign_status }}"){
            document.querySelector("#files").onchange = function() {
                const fileName = this.files[0]?.name;
                const label = document.querySelector("label[for=files]");
                label.innerText = fileName ?? "Browse Files";
            };
        }

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

        $('#excluded_concept').on('change', function (e) {
            
            const options = $(this).find('option').get();
            options.forEach(option => {
                const value = $(option).text();
                
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
            
            $('input[name="campaign_id"]').val('{{ $qr_creation->campaign_id }}');
            $('input[name="gc_description"]').val('{{ $qr_creation->gc_description }}');
            $('input[name="gc_value"]').val('{{ $qr_creation->gc_value }}');
            $('input[name="batch_number"]').val('{{ $qr_creation->batch_number }}');
            $('input[name="batch_group"]').val('{{ $qr_creation->batch_group }}');
            $('input[name="po_number"]').val('{{ $qr_creation->po_number }}');

            if(("{{ $qr_creation->campaign_status }}" > 1) || ("{{ CRUDBooster::myPrivilegeId() == 4 }}") || ("{{ CRUDBooster::myPrivilegeId() == 5 }}") || "{{ Request::segment(3) == 'detail' }}"){
                
                $('.panel-heading').text('Details');
                $('input').css('background-color', '#eee');
                $('input').attr('readonly', true);
                $('select').css('background-color', '#eee');
                $('select').attr('disabled', true);
            }

        }

        campaignCreationDetails()

        // Create Campaign
        $('#submit_btn').click(function(){
            $('#btn-val').val($(this).text());
            $('#form-sbmt').on('submit', function(event){
                event.preventDefault();
                let form = $(this);

                swal({
                    title: 'Are you sure?',
                    type: 'info',
                    showCancelButton: true,
                    allowOutsideClick: true,
                    confirmButtonColor: '#3c8dbc',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    closeOnConfirm: false
                },
                function(isConfirmed) {
                    if (isConfirmed) {
                        form.off('submit').submit();
                    }
                });
            })
        });

        // Reject Campaign
        $('#reject_btn').click(function(){
            const btnTextName = $(this).text();
            $('#btn-val').val(btnTextName);

            $('#form-sbmt').on('submit', function(event){
                event.preventDefault();
                let form = $(this);

                swal({
                    title: `Are you sure you want to <span class="u-t-danger">${btnTextName.toLowerCase()}</span>?`,
                    type: 'info',
                    showCancelButton: true,
                    allowOutsideClick: true,
                    confirmButtonColor: '#3c8dbc',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    closeOnConfirm: false,
                    html: true,
                },
                function(isConfirmed) {
                    if (isConfirmed) {
                        form.attr("action", "{{ route('campaign_approval') }}");
                        form.off('submit').submit();
                    }
                });
            })
        });

        // Approve Campaign
        $('#approve_btn').click(function(){
            const btnTextName = $(this).text();
            $('#btn-val').val(btnTextName);

            $('#form-sbmt').on('submit', function(event){
                event.preventDefault();
                let form = $(this);

                swal({
                    title: `Are you sure you want to <span class="u-t-success">${btnTextName.toLowerCase()}</span>?`,
                    type: 'info',
                    showCancelButton: true,
                    allowOutsideClick: true,
                    confirmButtonColor: '#3c8dbc',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    closeOnConfirm: false,
                    html: true,
                },
                function(isConfirmed) {
                    if (isConfirmed) {
                        form.attr("action", "{{ route('campaign_approval') }}");
                        if($("input[name='billing_number']").val() != ''){
                            $('.sk-chase-position').show();
                        }
                        form.off('submit').submit();
                    }
                });
            })
        });

        // Save BTN
        $('#save_btn').click(function(){
            const btnTextName = $(this).text();
            $('#btn-val').val(btnTextName);

            $('#form-sbmt').on('submit', function(event){
                event.preventDefault();
                let form = $(this);

                swal({
                    title: `Are you sure you want to <span class="u-t-success">${btnTextName.toLowerCase()}</span>?`,
                    type: 'info',
                    showCancelButton: true,
                    allowOutsideClick: true,
                    confirmButtonColor: '#3c8dbc',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    closeOnConfirm: false,
                    html: true,
                },
                function(isConfirmed) {
                    if (isConfirmed) {
                        form.attr("action", "{{ route('save_campaign_ihc') }}");
                        form.off('submit').submit();
                    }
                });
            })
        });
    </script>
@endsection

