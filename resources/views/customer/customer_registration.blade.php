<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EGC Purchase Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/customer_registration.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    {{-- Utilities CSS --}}
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">

    <style>
        body.swal2-height-auto {
            height: 100% !important;
        }

        .m-input{
            border: 1px solid #359D9D;
            background-color: #fff !important;
            /* padding: 0 */
        }

        .modal-content{
            padding: 0 20px 0 20px;
        }

        .modal-box-footer{
            padding: 0 20px 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        #modal-close{
            color: rgb(87, 87, 87) !important;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }

        #modal-close:hover{
            opacity: 0.8;
        }

        @media (max-width: 500px) {
            .modal-box-footer {
                flex-direction: column;
                align-items: flex-end; 
            }

            #modal-close {
                margin-top: 10px;
            }
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #b1b0b0 !important;
            height: 36px;
        }

        .swal-size{
            font-size: 14px
        }

        .btn-color {
            background-color: #3498db !important;
            border: none !important;
            outline: none; /* Remove the outline on focus */
        }


    </style>
</head>
<body>
    <div class="otp prohibited-center" style="display: none;">
        <div class="prohibited-box">
            <div class="prohibited-content">
                <img id="alert-img" src="{{ asset('img/one-time-password.png') }}" alt="">
                <h4 style="text-align: center; margin-top: 15px; letter-spacing: 1px;">VERIFY YOUR EMAIL ADDRESS</h4>
                <h6 class="u-tw-gray u-t-center">A Verification code has been sent to</h6>
                <h6 class="u-fw-b u-tw-gray u-t-center" id="otp-email">{{ $customer->email }}</h6>
                <form action="" method="POST" autocomplete="off" id="verify_otp">
                    <div class="otp-input" style="margin: 10px 0;">
                        <input type="text" class="otp-box" maxlength="1" required>
                        <input type="text" class="otp-box" maxlength="1" required>
                        <input type="text" class="otp-box" maxlength="1" required>
                        <input type="text" class="otp-box" maxlength="1" required>
                        <input style="display: none;" type="text" name="otp" value="">
                    </div>
                    <div style="display: flex; justify-content: space-between; max-width: 350px;">
                        <h6 class="u-tw-gray" style="text-align: center;">Please check your inbox and enter the verification code below your email address.</h6>
                    </div>
                    <div class="otp-btns">
                        <button id="close-otp" type="button">✏️ Back</button>
                        <button id="submit-otp" type="submit">➡️ Submit OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="egc-mail prohibited-center" style="display: none;">
        <div class="prohibited-box">
            <div class="prohibited-content">
                <img id="alert-img" src="{{ asset('img/gift.png') }}" alt="">
                <h4 class="u-tw-gray" style="text-align: center; margin-top: 15px;">Send E-GC</h4>
                <form action="verify-otp.php" method="post" id="send-egc-form">
                    <div class="customer-box-content1" style="padding: 0;">
                        <div class="customer-box-header-container" style="margin-bottom: 10px;">                    
                            <div class="customer-box-header">GC Recipient</div>
                        </div>
                        <hr>
                        <table class="custom_normal_table" >
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="egc-checkbox-content">
                                            <input type="checkbox" name="egc_checkbox" value="checked" id="egc-checkbox">
                                            <label for="egc-checkbox">Same as customer information</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>First name</p>
                                        <input class="inputs egc-validate" type="text" id="egc_first_name" name="egc_first_name" placeholder="Enter first name" required>
                                    </td>
                                    <td>
                                        <p>Last Name</p>
                                        <input class="inputs egc-validate" type="text"  id="egc_last_name" name="egc_last_name" placeholder="Enter last name" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Email</p>
                                        <input class="inputs egc-validate" type="email" id="egc_email" name="egc_email" placeholder="Enter email" required>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="egc-mail-btns">
                        <button id="review-form-egc" type="button" style="display: none;">⬅️ Review Form</button>
                        <button id="send-egc" type="submit">📩 Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <section class="customer-section">
        <div class="customer-box">
            <form action="" method="POST" autocomplete="off" id="registration-form">
                <input style="display: none" name="qr_reference_number" value="{{ Request::segment(4) }}" type="text">
                <div class="customer-box-logos">
                    @if (Request::segment(2) == 'digital_walker')
                        {{-- <img src="{{ asset('img/digital_walker1.png') }}" alt=""> --}}
                        <img src="{{ asset('img/btb_dw_os.png') }}" alt="">
                    @elseif (Request::segment(2) == 'beyond_the_box')
                        {{-- <img src="{{ asset('img/btb1.png') }}" alt=""> --}}
                        <img src="{{ asset('img/btb_dw_os.png') }}" alt="">
                    @elseif (Request::segment(2) == 'dw_and_btb' || Request::segment(2) == 'open_source')
                        <img src="{{ asset('img/btb_dw_os.png') }}" alt="">
                    @endif
                </div>
                <div class="customer-box-content">
                    <div class="customer-box-header-container">                    
                        <div class="customer-box-header" style="color:rgb(87, 87, 87)">📝 Please verify that the information provided below is accurate and complete before submitting the form</div>
                    </div>
                    <br>
                    <div class="customer-box-header-container">                    
                        <div class="customer-box-header">Customer Information</div>
                    </div>
                    <br>
                    <hr>
                    <table class="custom_normal_table" >
                        <tbody>
                            <tr>
                                <td>
                                    <p>First name</p>
                                    <input class="inputs validate c-info" type="text" id="first_name" name="first_name" value="{{ $customer->first_name }}" required placeholder="Enter your first name">
                                </td>
                                <td>
                                    <p>Last Name</p>
                                    <input class="inputs validate c-info" type="text"  id="last_name" name="last_name" value="{{ $customer->last_name }}" required placeholder="Enter your last name">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Email</p>
                                    <input class="inputs validate c-info" type="email" id="email" name="email" value="{{ $customer->email }}" onpaste="return false;" oncopy="return false;" oncut="return false;" required placeholder="Enter your email">
                                </td>
                                <td>
                                    <p>Confirm Email</p>
                                    <input class="inputs validate c-info" type="email" id="confirm_email" name="confirm_email" value="{{ $customer->confirmed_email }}" onpaste="return false;" oncopy="return false;" oncut="return false;" required placeholder="Enter your confirm email">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Contact Number</p>
                                    <input class="inputs validate c-info" type="text" id="contact_number" name="contact_number" value="{{ $customer->phone }}" required placeholder="Enter your contact number">
                                </td>
                                <td>
                                    <p>Store Branch</p>
                                    <select name="store_concepts_id" id="store_concepts_id" class="search-select" disabled required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($store_branches as $store_branch)
                                            @if (str_replace('_',' ',Request::segment(3)) == $store_branch->name)
                                                <option value="{{$store_branch->id}}" selected>{{$store_branch->name}}</option>
                                                @else
                                                <option value="{{$store_branch->id}}">{{$store_branch->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Concept</p>
                                    <input class="inputs" id="concept" name="concept" type="text" readonly>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="custom_normal_table">
                        <tr>
                            <td>
                                <div class="egc-checkbox-content">
                                    <input type="checkbox" name="terms_and_condition" id="terms-and-condition" required>
                                    <label for="terms-and-condition" style="font-weight: 400; font-size: 1rem;">I agree with terms and condition</label>
                                </div>
                                <br>
                                <div id="terms-and-condition-body" style="display: none;">
                                    <h6>1. e-Gift certificates are not convertible to cash.</h6>
                                    <h6>2. e-Gift certificates are transferable.</h6>
                                    <h6>3. e-Gift certificates may only be redeemed once.</h6>
                                    <h6>4. If purchase exceeds the e-Gift certificate value, the balance must be paid by the customer with other available payment options.</h6>
                                    <h6>5. If purchase is lower than the e-Gift certificate value, no change will be given.</h6>
                                    <h6>6. The store reserves the right to verify the identity of the e-Gift certificate's bearer.</h6>
                                    <h6>7. e-Gift certificate must be presented in its original and unaltered form.</h6>
                                    <h6>8. e-Gift certificate is valid in all branches of Digital Walker, Beyond the Box, and Open Source.</h6>
                                    <h6>9. This e-Gift certificate is valid on all accessories and units.</h6>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="customer-box-footer">
                    <button class="btn" id="review-form-recipient" type="button" style="display: none;">⬅️ Review Form Recipient</button>
                    <button class="btn" id="btn-submit" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </section>

    <script>

        const urlSegment = '{{ Request::segment(2) }}';
        const urlRefenrenceNumber = '{{ Request::segment(4) }}';
        let recipient = {!! json_encode($recipient) !!}

        customerUrl(urlSegment);
                
        $(document).ready(function() {

            let validEmail = false;

            if(recipient.store_status >= 2){
                $('#terms-and-condition').attr('checked', true);
                $('#terms-and-condition-body').show();
            }

            if(recipient.store_status>2){
                $('.customer-section').find('input,#btn-submit').attr('disabled', 'true');
            }

            if(recipient.store_status == 2){
                $('.otp').show();
                validEmail = true;
            }else if(recipient.store_status == 3){
                $('.egc-mail').show();
            }


            $('#store_concepts_id').select2({
                width: '100%'
            });

            if ("{{ (session('success') && is_array(session('success'))) }}"){
                $('.modal-success').css('visibility', 'visible');
                $('.select2-selection').hide();
                $('.customer-box-header-select').hide();
            }

            $('#modal-close').on('click', function(){
                $('.modal-success').css('display', 'none');
                $('.select2-selection').show();
                $('.customer-box-header-select').show();
            })
            
            // Checkbox
            $('#egc-checkbox').on('change', egcCheckBox);
            $('.c-info').on('input', egcCheckBox);

            // Terms and Condition
            $('#terms-and-condition').on('change', function(){
                if($(this).is(':checked')){
                    $('#terms-and-condition-body').show();
                }else{
                    $('#terms-and-condition-body').hide();
                }
            });

            // First Step Registration form
            $("#registration-form").submit(function(event) {

                event.preventDefault();

                const btnSubmit = $('#btn-submit');

                // Check if all required fields are valid
                if (($(this).find('.validate:invalid').length === 0 && $(this).find('#store_concepts_id').val()) && validEmail) {

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: `Yes, ${btnSubmit.text()} it!`,
                        reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#otp-email').text($('#email').val());
                                $('#store_concepts_id').attr('disabled', false);
                                // btnSubmit.attr('disabled', true);
                                let registration_form = $('#registration-form').serialize();
                                
                                $.ajax({
                                    url: "{{ route('send_otp') }}",
                                    dataType: 'json',
                                    type: 'POST',
                                    data: registration_form + '&_token={{ csrf_token() }}',
                                    success: function(response) {
                                        if(!(response.is_otp_sent)){
                                            Swal.fire({
                                                title: "Error",
                                                text: "OTP not sent",
                                                icon: "error"
                                            });
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        Swal.fire({
                                            title: "Error",
                                            text: "Something went wrong. Please contact BPG or refresh the page and try again.",
                                            icon: "error"
                                        });
                                    }
                                });

                                $('#store_concepts_id').attr('disabled', true);

                                $('.otp').show();
                            }
                        });                
                }else if(!validEmail){
                    Swal.fire({
                        title: "Error",
                        text: "The email you entered does not correspond with the confirmation email provided",
                        icon: "error"
                    });
                }
                else{
                    Swal.fire({
                        title: "Error",
                        text: "Make sure you are on the correct page, and that all inputs are filled in!",
                        icon: "error"
                    });
                }
            });

            // Second Step verify OTP
            $('#verify_otp').submit(function(event){

                event.preventDefault();

                const btnSubmit = $('#submit-otp');

                let verifyOtpForm = new URLSearchParams($(this).serialize());

                if($(this).find('.otp-box:invalid').length === 0){

                    verifyOtpForm.append('qr_reference_number', urlRefenrenceNumber);

                    $.ajax({
                        url: "{{ route('verify_otp') }}",
                        dataType: 'json',
                        type: 'POST',
                        data: verifyOtpForm + '&_token={{ csrf_token() }}',
                        success: function(response) {
                            if(response.otp){
                                $('.otp').hide();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "OTP Verification Successful",
                                    html: `
                                        <div>
                                            <h4 class="u-fw-b">EGC activated 💳</h4>
                                            <h5>Click <span class="u-fw-b">'Gift'</span> if you intend to send the EGC to a recipient.</h5>
                                        </div>
                                    `,
                                    showConfirmButton: true,
                                    showCloseButton: true,
                                    confirmButtonText: "Gift",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                }).then((result) => {
                                    $('.customer-section').find('input,#btn-submit').attr('disabled', 'true');
                                    if (result.isConfirmed) {
                                        $('.egc-mail').show();
                                    }
                                });
                            }else{
                                Swal.fire({
                                    position: "center",
                                    icon: "error",
                                    title: "OTP Verification Failed",
                                    text: "The entered OTP does not match. Please double-check and try again.",
                                    showConfirmButton: false,
                                    timer: 2000 // You may adjust the timer duration as needed
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error: ", xhr.responseText);
                        }
                    });

                }
            });

            // Third Step Send egc
            $('#send-egc-form').submit(function(event){

                event.preventDefault();

                let sendEgcForm = new URLSearchParams($(this).serialize());

                sendEgcForm.append('qr_reference_number', urlRefenrenceNumber);

                if($(this).find('.egc-validate:invalid').length === 0){
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: `Yes, send it!`,
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('send_egc') }}",
                                dataType: 'json',
                                type: 'POST',
                                data: sendEgcForm + '&_token={{ csrf_token() }}',
                                success: function(response) {
                                    if(response.same_email){
                                        Swal.fire({
                                            position: "center",
                                            icon: "success",
                                            title: "Your E-Gift Card is on its way! Expect it in your inbox shortly",
                                            showConfirmButton: false,
                                            showCloseButton: true,
                                            customClass: {
                                                popup: 'swal-size',
                                            },
                                        }).then((result) => {
                                            $('#send-egc, #btn-submit, .egc-validate, .validate').attr('disabled', true);
                                            $('#review-form-egc').show();
                                            $('#review-form-recipient').show();
                                        });
                                    }else{
                                        Swal.fire({
                                            position: "center",
                                            icon: "success",
                                            title: "Your E-Gift Card is activated",
                                            showConfirmButton: false,
                                            showCloseButton: true,
                                            customClass: {
                                                popup: 'swal-size',
                                            },
                                        }).then((result) => {
                                            $('#send-egc, #btn-submit, .egc-validate, .validate').attr('disabled', true);
                                            $('#send-egc').hide();
                                            $('#review-form-egc').show();
                                            $('#review-form-recipient').show();
                                        });
                                    }

                                },
                                error: function(xhr, status, error) {
                                    if(xhr.responseText){
                                        Swal.fire({
                                            position: "center",
                                            icon: "error",
                                            title: "Something went wrong",
                                            text: "Take a screenshot of the input form and send it to BPG.",
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                    }
                                }
                            });

                            $('#store_concepts_id').attr('disabled', true);
                        }
                    });
                }
            });

            // Confirm Email
            $('#email,#confirm_email').on('input', function () {
                
                const email = $('#email').val();
                const confirmEmail = $('#confirm_email').val();

                if(!email || !confirmEmail){
                    return;
                }

                if ((email != confirmEmail) && (email && confirmEmail)) {
                    $(this).css({
                        'border': '1px solid #CB6767',
                        'border-top': '3px solid #CB6767'
                    });
                    validEmail = false;
                }else{
                    $('#email,#confirm_email').css({
                        'border': '1px solid #b1b0b0',
                        'border-top': '3px solid #b1b0b0'
                    });
                    validEmail = true;
                }
            });

            // Modal
            // Close OTP
            $('#close-otp').on('click', function(){
                $('.otp').hide();
            });

            // Close EGC Mail
            $('#review-form-egc').on('click', function(){
                $('.egc-mail').hide();
            });

            // Open Recipient
            $('#review-form-recipient').on('click', function(){
                $('.egc-mail').show();
            })

            // OTP
            $('.otp-box').on('input', function(event) {
                
                const currentInput = $(this);
                const maxLength = parseInt(currentInput.attr('maxlength'), 10);
                const inputValue = currentInput.val();

                if (inputValue.length === maxLength) {
                    currentInput.next('.otp-box').focus();
                }

                $('input[name="otp"]').val(getOptValue());
            });
        });


        function convertToTitleCase(str) {

            // Split the string into an array of words
            let words = str.split('_');
            // Capitalize the first letter of each word
            let capitalizedWords = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
            // Join the words back together with spaces
            let titleCaseString = capitalizedWords.join(' ');

            return titleCaseString;
        }

        function customerUrl(url) {

            let concept = ''
            let bgColor = '';

            if (url == 'beyond_the_box'){
                concept = convertToTitleCase(url);
                bgColor = '#D0D2D4';
            }else if (url == 'digital_walker'){
                // bgColor = '#FED440';
                bgColor = '#D0D2D4';
                concept = convertToTitleCase(url)
            }else if (url == 'dw_and_btb'){
                concept = 'Digital Walker and Beyond the Box';
                // bgColor = '#359D9D';
                bgColor = '#D0D2D4';
            }else if (url == 'open_source'){
                concept = convertToTitleCase(url);
                // bgColor = '#359D9D';
                bgColor = '#D0D2D4';
            }
            
            $('.customer-box-logos').css('background', bgColor);
            $('#concept').val(concept);
        }

        function formatResult(result) {

            return result.text;
        }
        function formatSelection(selection) {

            return selection.text.length > 20 ? selection.text.substring(0, 20) + '...' : selection.text;
        }

        function getInfo(selectedValue){
            
            $.ajax({
                url:"{{ route('viewCustomerInfo')}}",
                type:'POST',
                dataType:'json',
                data:{
                    _token:"{{ csrf_token()}}",
                    customerID: selectedValue,
                },
                success:function(res){
                    // console.log(res);
                    populateCustomerInfo(res);
                },
                error:function(res){
                    alert('Failed')
                },
            });
        }

        function populateCustomerInfo (res){

            const customerInformation = res.customer_information;
            console.log(customerInformation);
            $('#first_name').val(customerInformation.first_name || '');
            $('#last_name').val(customerInformation.last_name || '');
            $('#email').val(customerInformation.email || '');
            $('#contact_number').val(customerInformation.phone || '');
            $('#concept').val(customerInformation.store_concept || '');
        }

        function egcCheckBox(){
            
            const firstName = $('#first_name').val();
            const lastName = $('#last_name').val();
            const email = $('#email').val();
            const contact = $('#contact_number').val();

            if($('#egc-checkbox').is(":checked")){
                $('#egc_first_name').val(firstName).attr('readonly', true);
                $('#egc_last_name').val(lastName).attr('readonly', true);
                $('#egc_email').val(email).attr('readonly', true);
                $('#egc_contact_number').val(contact).attr('readonly', true);

            }else{
                $('input[name^="egc"]').val('').attr('readonly',false);
            }
        }

        function getOptValue(){

            let optValue = '';
            $('.otp-box').each(function(){
                optValue += $(this).val();
            })

            return optValue;
        }

    </script>
</body>
</html>