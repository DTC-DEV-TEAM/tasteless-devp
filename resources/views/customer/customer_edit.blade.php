<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    {{-- Summernote --}}
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/summernote/summernote.css')}}">
    {{-- Jquery --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> --}}
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
            /* margin-bottom: 15px; */
            margin-right: 15px;
            font-weight: bold;
            font-size: 2rem;
        }

        body.swal2-height-auto{
            height: 100% !important;
        }

        .inputs:read-only{
            background-color: #fff !important;
        }

        #swal_table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        #swal_table th,
        #swal_table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        @media (max-width: 767px) {
            #swal_table {
                overflow-x: auto;
                white-space: nowrap;
            }

            #swal_table th,
            #swal_table td {
                white-space: nowrap;
            }
        }

        .container-res{
            position: relative;
            display: flex;
            flex-wrap:  wrap;
            margin-bottom: 15px;
        }

        #show-history{
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

/* The actual timeline (the vertical ruler) */
.timeline {
  position: relative;
  max-width: 1200px;
}

/* The actual timeline (the vertical ruler) */
.timeline::after {
  content: '';
  position: absolute;
  width: 6px;
  background-color: rgb(191, 188, 188);
  top: 0;
  bottom: 0;
  left: 2%;
  margin-left: -3px;
}

.timeline::before{
            width: 0 !important;
        }

/* Container around content */
.container1 {
  padding: 10px 40px;
  position: relative;
  background-color: inherit;
  width: 100%;
}

/* The circles on the timeline */
.container1::after {
  content: '';
  position: absolute;
  width: 25px;
  height: 25px;
  right: -17px;
  background-color: white;
  border: 3px solid #4D4A86;
  top: 19px;
  border-radius: 50%;
  z-index: 1;
  box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
}

/* Place the container to the left */
.left {
  left: 0;
}

/* Place the container to the right */
.right {
  left: 2%;
}

/* Add arrows to the left container (pointing right) */
.left::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  right: 30px;
  border: medium solid white;
  border-width: 10px 0 10px 10px;
  border-color: transparent transparent transparent white;
}

/* Add arrows to the right container (pointing left) */
.right::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  left: 30px;
  border: medium solid white;
  border-width: 10px 10px 10px 0;
  border-color: transparent rgb(191, 188, 188) transparent transparent;
  z-index: 1;
}

/* Fix the circle for containers on the right side */
.right::after {
  left: -13px;
}

/* The actual content */
.content1 {
  padding: 12px 30px;
  background-color: white;
  position: relative;
  border-radius: 6px;
  box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
  z-index: 2;
}

/* Media queries - Responsive timeline on screens less than 600px wide */
@media screen and (max-width: 600px) {
  /* Place the timelime to the left */
  .timeline::after {
  left: 31px;
  }
  
  /* Full-width containers */
  .container1 {
  width: 100%;
  padding-left: 70px;
  padding-right: 25px;
  }
  
  /* Make sure that all arrows are pointing leftwards */
  .container1::before {
  left: 60px;
  border: medium solid white;
  border-width: 10px 10px 10px 0;
  border-color: transparent white transparent transparent;
  }

  /* Make sure all circles are at the same spot */
  .left::after, .right::after {
  left: 15px;
  }
  
  /* Make all right containers behave like the left ones */
  .right {
  left: 0%;
  }
}
    </style>
@endpush

@section('content')
    <!-- Your html goes here -->
    <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Home</a></p>
    <div class='panel panel-default'>
        <div class='panel-heading'>Edit Form</div>
        <form method='post' action='{{ $customer->store_status == 2 ? route('pending_invoice') : route('pending_oic') }} ' autocomplete="off">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input class="hide" type="text" name="id" value="{{ $customer->id }}">
        <div class='panel-body'>
            <div class="container-res">
                <div class="cb-header">
                    Customer Information
                </div>
                @if ($customer->store_status == 3)
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
            {{-- @if(!($customer->name == $customer->cus_name && $customer->email == $customer->cus_email)) --}}
            <br>
            <div class="container-res">                
                <div class="cb-header">
                    EGC Recipient
                </div>
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
            {{-- @endif --}}
        <br>
        <table class="custom_table">
            <tbody>
                <tr>
                    <td>Concept:</td>
                    <td><input class="form-control inputs branch" type="text" value="{{ $customer->store_concept }}" readonly></td>
                    <td>Branch</td>
                    <td><input class="form-control inputs branch" type="text" value="{{ $customer->store_branch }}" readonly></td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="custom_normal_table">
            <tbody>
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
                    <td><input class="form-control" type="text" name="store_invoice_number" value="{{ $customer->store_invoice_number }}" required readonly></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        @if(!$email_testing)
        <table class="custom_normal_table">
            <tbody>
                <tr>
                    <td style="color: red !important;">Please create email template first</td>
                </tr>
            </tbody>
        </table>
        @endif

        @if ($customer->store_status > 2)
        <hr>
        <div class="container-res">
            <div class="cb-header">
                Email Content
            </div>
        </div>
        <div class="email-content" id="email-content">
        </div>
        @endif
        <button class="hide" id="btn-submit" type="submit">submit</button>
    </div>
    </form>
    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">Cancel</a>
        <input type='button' class='btn btn-primary pull-right' id='btn-fake' value="{{ $customer->store_status == 2 ? 'Submit' : 'Approve' }}"/>
        @if (in_array(CRUDBooster::myPrivilegeId(),[1,7]))
        <a class="btn btn-danger pull-right" id="void" style="margin-right: 5px;">Void</a>
        @endif
    </div>

    <div class="store-modal-bg-dark">
        <div class="store-modal">
            <div class="store-modal-header">History</div>
            <br>
            <div class="timeline">
                <div class="container1 right">
                  <div class="content1">
                    <p>
                        <span style="font-weight: bold;">Created at: {{ \Carbon\Carbon::parse($original_history->created_at)->format('Y-m-d H:i') }}</span>
                    </p>
                    </table>
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between">
                        <div>
                            <span style="font-weight: bold;">Customer Information</span>
                            <ul>
                                <li><span style="color: #2a9bb1; font-weight: 600;">Name: </span>{{ $original_history->customer_first_name }}</li>
                                <li><span style="color: #2a9bb1; font-weight: 600;">Email: </span>{{ $original_history->customer_last_name }}</li>
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
                        @foreach ($customer_information[$i] as $key => $value)
                        {{ ucfirst(str_replace('_', ' ', $key)).": $value" }}
                        @endforeach
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

        function preventBack() {
            window.history.forward();
        }
        window.onunload = function() {
            null;
        };
        setTimeout("preventBack()",0);

        storeBrandEmail();
        historyModal();

        function storeBrandEmail(){

            const email_testing = {!! json_encode($email_testing) !!}

            if(!email_testing){
                $('#btn-fake').attr('disabled', true);
                return;
            }
            
            const token = $("#token").val();
            const campaignId = email_testing.store_logos_id;
            const selected_header = email_testing.id;

            $.ajax({
                type: 'POST',
                url: ADMIN_PATH + "/selectedHeader",
                data: {
                    "_token": token,
                    "id": selected_header,
                    "campaign_id": campaignId,
                },
                success: function(data) {
                    $('.email-content').empty().append(data.emailContent);
                },
                error: function(e) {
                    alert(e);
                    console.log(e);
                }
            });
        }

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

        $(document).ready(function() {


            if("{{ $customer->store_status == 3 }}" || "{{ $customer->store_status == 2 }}"){
                $('input[name="store_invoice_number"]').attr('readonly', true);
                $('.inputs').attr('readonly', false);
                $('.branch').attr('readonly', true);
            }
            else if ("{{ $customer->store_status > 2 && !CRUDBooster::isSuperAdmin() }}"){
                $('input,select').attr('disabled', true);
            }

            $('#btn-fake').on('click', function(){
                const btnText = $(this).val();

                const firstName = $('input[name="cus_first_name"]').val();
                const lastName = $('input[name="cus_last_name"]').val();
                const invoiceNumber = $('input[name="store_invoice_number"]').val();
                const egcValue = $('select[name="egc_value"]').val();
                let name = `${firstName} ${lastName}`

                const wrapper = $('<div>');
                const table = $('<table id="swal_table">');
                const thead = $('<thead>');
                const tbody = $('<tbody>');
                const tr = $('<tr>'); // Create a table row

                // Use th (table header) for the headers
                tr.append($('<th>').text('Name'), $('<th>').text('EGC Value'), $('<th>').text('Invoice Number'));

                thead.append(tr);

                // Create another row for the data
                const dataRow = $('<tr style="color: #2C78C1; font-weight: 600;">').append($('<td>').text(name), $('<td>').text(egcValue), $('<td>').text(invoiceNumber));
                tbody.append(dataRow);

                table.append(thead, tbody);

                wrapper.append(table);

                Swal.fire({
                    title: "Are you sure?",
                    html: wrapper,
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

            $('#void').on('click', function(event){
                event.preventDefault();
                const btnText = $(this).text();
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

                        window.location.href = "{{ route('egc_void', ['id' => ':id']) }}".replace(':id', '{{ $customer->id }}');
                    }
                });  
            })


        });


    </script>
@endsection

@push('bottom')
    <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.min.js')}}"></script>
@endpush