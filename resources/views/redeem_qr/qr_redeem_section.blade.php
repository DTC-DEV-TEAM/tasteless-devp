<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  {{-- Sweet Alert --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  {{-- Confetti --}}
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

  <style>
    .swal2-popup {
      width: 350px !important;
      padding: 100px 0;
      font-size: 17px !important;
      margin-right: 8px !important;
      background-color: #222D32 !important;
      color: white !important;
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
  @if ($row->uploaded_img)
    <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Redeem QR Home</a></p>
  @endif
  <div class='panel panel-default'>
    <div class='panel-heading' style="background-color: #fff;">Redeem Form</div>
    <div class='panel-body qr_redeem_section'>
      <form id="redeem-close-transaction" method='post' action='{{ route('redeem_code') }}' autocomplete="off" style="display: none;" enctype="multipart/form-data">
        @csrf
        <input class="hidden" type="text" name="user_id" id="user_id" value="{{ $row->id }}" >
        <div class="redeem_layout">
          <div class="qr-reference-card" style="display: none;">
            <div class="close-icon">
              <button type="button" id="close-qr-reference-code"><i class="fa fa-close"></i></button>
            </div>
            @if ($row->status == 'EXPIRED')
            <div class="expired-icon">
              <span><i class="fa fa-close"></i></span>
            </div>
            @else
            <div class="check-icon">
              <span><i class="fa fa-check"></i></span>
            </div>
            @endif
            <div class="redemption-success">
              @if ($row->status == 'EXPIRED')
              <span>REDEMPTION EXPIRED</span>
              @else
              <span>REDEMPTION SUCCESS</span>
              @endif
            </div>
            <div class="qr-reference-content">
              <span id="qr-reference-number">CAMPAIGN ID REFERENCE #: {{ $row->campaign_id }} - {{ $row->qr_reference_number }}</span>
            </div>
            <div class="input-invoice-notes">
              <span style="text-transform: uppercase;">Note: Please copy paste below your POS memo field</span>
            </div>
          </div>

          <div class="qr-invoice-number-card" style="display: none;">
            <div class="invoice-number-close-icon">
              <button type="button" id="close-qr-invoice_number"><i class="fa fa-close"></i></button>
            </div>
            <div class="invoice-number-check-icon">
              <span><i class="fa fa-pencil-square-o"></i></span>
            </div>
            <div class="redemption-success">
              @if ($row->invoice_number)
              <span>POS INVOCE NUMBER</span>
              @else
              <span id="inv-success">INPUT POS INVOCE NUMBER</span>
              @endif
            </div>
            <div class="input-invoice">
              <input type="number" name="invoice_number" id="pos-invoice-number" value="{{ $row->invoice_number }}" {{ $row->invoice_number ? "readonly" : '' }} required>
              <button type="button" id="submit-invoice-btn" {{ $row->invoice_number ? 'disabled' : '' }}>Save</button>
            </div>
            <div class="input-invoice-notes">
              <span style="text-transform: uppercase;">Note: Please Input POS# use in the transaction.</span>
            </div>
          </div>

          <div class="uploading-item-card" style="display: none;">
            <div class="uploading-item-close-icon">
              <button type="button" id="close-qr-uploading-item"><i class="fa fa-close"></i></button>
            </div>
            @if (!$row->uploaded_img)
            <div class="uploading-item-check-icon">
              <span><i class="fa fa-upload"></i></span>
            </div>
            <div class="uploading-item-success">
              <span>UPLOAD FILE TYPE IMAGE</span>
            </div>
            <div class="upload-image">
              <input type="file" name="item_image" accept="image/*" required>
            </div>
            <div style="display: flex; justify-content: center;">
              @if ($errors->has('item_image'))
              <span style="color: rgb(237, 66, 66); font-weight: bold; margin: auto;">Upload Image Only</span>
              @endif
            </div>
            <div class="uploading-item-close-transaction">
              <button id="uploading-item-ended" type="button">Close Transaction</button>
            </div>
            @else
              <img src="{{ asset('uploaded_item/img/'.$row->uploaded_img) }}" alt="" style="width: 100%; height: 100%; object-fit: contain;">
            @endif
          </div>
          
          <div class="user-info-content">
            <div class="user-info">
              <div class="user-element">
                <label for="">Name: </label>
                <input type="text" value="{{ $row->name }}" readonly>
              </div>
              <div class="user-element">
                <label for="">Email: </label>
                <input type="text" value="{{ $row->email }}" readonly>
              </div>
            </div>
          </div>
          <div class="user-info-content">
            <div class="user-info">
              <div class="user-element">
                <label for="">Phone Number: </label>
                <input type="text" value="{{ $row->phone }}" readonly>
              </div>
              <div class="user-element">
                <label for="">GC Description: </label>
                <input type="text" value="{{ $row->gc_description }}" readonly>
              </div>
            </div>
          </div>
          <div class="user-info-content">
            <div class="user-info">
              <div class="user-element">
                <label for="">Batch Group: </label>
                <input id="redemption_start_date" type="text" value="{{ $row->batch_group }}" readonly>
              </div>
              <div class="user-element">
                <label for="">Batch Number: </label>
                <input id="redemption_end_date" type="text" value="{{ $row->batch_number }}" readonly>
              </div>
            </div>
          </div>
          <div class="user-info-content">
            <div class="user-info">
              <div class="user-element">
                <label for="">GC Value: </label>
                <input type="text" value="{{ $row->gc_value }}" readonly>
              </div>
              <div class="user-element">
                <label for=""><span class="required">*</span> Government ID#: </label>
                <input type="text" name="id_number" 
                id="id_number"  value="{{ $row->id_number }}" {{ $row->id_number ? 'readonly' : '' }} required>
              </div>
            </div>
          </div>
          <div class="user-info-content">
            <div class="user-info">
              <div class="user-element">
                <label for=""><span class="required">*</span> ID Type: </label>
                {{ $row->id_types }}
                <select name="id_type" id="id-type" {{ $row->id_type ? 'disabled': '' }} required>
                  <option value="" disabled selected>Select Valid IDs</option>
                  @foreach ($valid_ids as $valid_id)
                    <option {{ $row->id_type == $valid_id->id ? 'selected': '' }} value="{{ $valid_id->id }}">{{ $valid_id->valid_ids }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <br>
          {{-- @php
          $qrCodeUrl = route('scan_qr', ['data' => 'your-data-goes-here']);
          $qrCodeURL = url()->current();
          @endphp
          {!! QrCode::size(200)->generate(url()->current()); !!} --}}
          <div class="text-center">
              
              <img style="height: 130px; width: 130px;" src="{{ asset('img/scan-women.jpg') }}" alt="">
              <p style="font-weight: bold; font-size: 15px;">Redeem code here and unlock exclusive benefits and rewards.</p>
          </div>
        
          <div class="redeem-btn">
            <button type='submit' class='redeem-code' id="redeem-code"><i class='fa fa-credit-card-alt '></i> Step 1 - Redeem Code</button>
            <button type='button' class='redeem-code' id="show-reference-number" disabled><i class='fa fa-sticky-note-o '></i>Step - 2 Show QR Reference #</button>
            <button type='button' class='redeem-code' id="show-input-invoice" disabled><i class='fa fa-pencil '></i>Step 3 - Input POS Invoice #</button>
          </div>
          <div class="redeem-btn" style="margin-top: 5px;">
            @if ($row->uploaded_img)
            <button type='button' class='redeem-code' id="show-upload-item" disabled><i class='fa fa-file-image-o '></i>Step 4 - Uploaded Item</button>
            @else
            <button type='button' class='redeem-code' id="show-upload-item" disabled><i class='fa fa-file-image-o '></i>Step 4 - Upload Item</button>
            @endif
          </div>
        </div>
      </form>
    </div>
    {{-- <div class='panel-footer' style="background-color: #fff;">
    </div> --}}

  </div>

  <script>

    $(document).ready(function() {
  
      $('form').css('display','block');
      $('body').addClass('sidebar-collapse');

      $('#redeem-close-transaction').submit(function(){
        $('.sk-chase-position').show();
      })
  
      // Transaction Validation 
      function transactionValidation(){

        if('{{ $row->redeem }}' && '{{ $row->invoice_number }}'){
          $('#redeem-code').attr('disabled', true);
          $('#show-reference-number').attr('disabled', false);
          $('#show-input-invoice').attr('disabled', false);
          $('#show-upload-item').attr('disabled', false);
        }else if ('{{ $row->redeem }}' && '{{ !$row->invoice_number }}'){
          $('#redeem-code').attr('disabled', true);
          $('#show-reference-number').attr('disabled', false);
          $('#show-input-invoice').attr('disabled', false);
        }
      }
      
      // Toggle and Closing
      function toggleAndClosing(){
        
        // Toggle QR Reference Card
        $('#show-reference-number').click(function(event) {
          $('.qr-reference-card').fadeToggle();
        });
        
        $('#close-qr-reference-code').click(function(){
          $('.qr-reference-card').hide();
        })

        // Upload Item Card
        $('#show-upload-item').click(function(event) {
          $('.uploading-item-card').fadeToggle();
        });

        $('.uploading-item-close-icon').click(function(){
          $('.uploading-item-card').hide();
        })

        // Input Invoice Number
        $('#show-input-invoice').click(function(event) {
          $('.qr-invoice-number-card').fadeToggle();
        });
        
        $('#close-qr-invoice_number').click(function(){
          $('.qr-invoice-number-card').hide();
        })

        // Close QR Reference Card
        $(document).click(function(event){

          if (!$(event.target).closest('.qr-invoice-number-card').length && !$('#show-input-invoice').is(event.target)){
            $('.qr-invoice-number-card').hide();
          }
          if (!$(event.target).closest('.qr-reference-card').length && !$('#show-reference-number').is(event.target)){
            $('.qr-reference-card').hide();
          }
          if (!$(event.target).closest('.uploading-item-card').length && !$('#show-upload-item').is(event.target)){
            $('.uploading-item-card').hide();
          }
        })
      }

      // Redeem Code Button
      $('#redeem-code').click(function(event){

        const id_type = $('#id-type').val();
        const id_number = $('#id_number').val();
        const user_id = $('#user_id').val();
        
        event.preventDefault();

        if(!id_number || !id_type ){
            const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            icon: 'warning',
            title: 'All required field must be filled'
          })
          return

        }

        $.ajax({
          url: "{{ route('redeem_code') }}",
          dataType: 'json',
          type: 'POST',
          data: {
            id_type: id_type,
            id_number: id_number,
            user_id: user_id,
          },
          success: function(response){

            console.log(response.test.qr_reference_number);

            confetti({
              particleCount: 100,
              spread: 70,
              origin: { y: 0.8, x: 0.57 }
            });
            
            $('#qr-reference-number').text(`CAMPAIGN ID REFERENCE #: ${response.test.campaign_id} - ${response.test.qr_reference_number}`)
            $('#redeem-code').css({'box-shadow': 'none', 'transform': 'translateY(5px)', 'opacity': '0.9'});
            $('#redeem-code').attr('disabled', true);
            $('#show-reference-number').attr('disabled', false)
            $('#show-input-invoice').attr('disabled', false)
            
            $('#id-type').attr('disabled', true);
            $('#id-type').css({'background-color': '#eeeeee'});
            $('#id_number').attr('readonly', true);

            const Toast = Swal.mixin({
              toast: true,
              position: 'bottom-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              icon: 'success',
              title: 'Code Redeemed Successfully'
            })
          },
          error: function(error){
            console.log(error)
          }
        })
        

      })
      // End of Redeem Button

      // Invoice Submit Button
      $('#submit-invoice-btn').click(function(event){

        const posInvoiceNumber = $('#pos-invoice-number').val();
        const user_id = $('#user_id').val();

        event.preventDefault();

        $.ajax({
          url: "{{ route('input_invoice') }}",
          dataType: 'json',
          type: 'POST',
          data: {
            posInvoiceNumber: posInvoiceNumber,
            userId: user_id,
          },
          success: function(response){
            console.log(response);
            $('#pos-invoice-number').attr('readonly', true);
            $('#show-upload-item').attr('disabled', false);
            $('#submit-invoice-btn').hide();
            $('#inv-success').text('POS INVOICE NUMBER');

            const Toast = Swal.mixin({
              toast: true,
              position: 'bottom-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              icon: 'success',
              title: 'Pos invoice number saved successfully'
            })

          },
          error: function(error){
            console.log(error);
          }
        })
      })
      // End of Invoice Submit Button

      function stepFour(){

        $('#uploading-item-ended').click(function(){

          $(this).parents('form').attr('action', '{{ route('close_transaction') }}')
          $(this).attr('type', 'submit');
        });        
      }

      transactionValidation();

      toggleAndClosing();

      stepFour();

    });
  </script>
@endsection