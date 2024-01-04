<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/customer_registration.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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

    </style>
</head>
<body>
    @if((session('success') && is_array(session('success'))))
        <div class="modal-success">
            <div class="modal-box">
                <div class="modal-header">Customer Information</div>
                <div class='modal-content'>
                    <table class="custom_normal_table">
                        <tbody>
                            <tr>
                                <td>
                                    <p>Name</p>
                                    <input class="inputs m-input" type="text" value="{{ session('success')['first_name'] }}" readonly>
                                </td>
                                <td>
                                    <p>Contact Number</p>
                                    <input class="inputs m-input" type="text" value="{{ session('success')['phone'] }}" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Email</p>
                                    <input class="inputs m-input" type="text" value="{{ session('success')['email'] }}" readonly>
                                </td>
                                <td>
                                    <p>Concept</p>
                                    <input class="inputs m-input" type="text" value="{{ session('success')['store_concept'] }}" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="modal-box-footer">
                    <span style="font-size: 14px; color: green; font-weight: 600; margin: 5px 5px 5px 0">Your request has been succesfully submitted.</span>
                    <button class="btn" id="modal-close" type="submit">Close</button>
                </div>
            </div>
        </div>
    @endif
    <section class="customer-section">
        <div class="customer-box">
            <form action="{{ route('store_ui') }}" method="POST" autocomplete="off" id="registration-form">
                @csrf
                <div class="customer-box-logos">
                    @if (Request::segment(2) == 'digital_walker')
                        <img src="{{ asset('img/digital_walker1.png') }}" alt="">
                    @elseif (Request::segment(2) == 'beyond_the_box')
                        <img src="{{ asset('img/btb1.png') }}" alt="">
                    @elseif (Request::segment(2) == 'btb_x_open_source' || Request::segment(2) == 'open_source')
                        <img src="{{ asset('img/btb_dw_os.png') }}" alt="">
                    @endif
                </div>
                <div class="customer-box-content">
                    <div class="customer-box-header-container">                    
                        <div class="customer-box-header">Customer Form</div>
                        <div class="customer-box-header-select"><select class="search-select" id="existing-customer">
                        </select>
                        <p id="select-note">Search For Existing Customer:</p>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <table class="custom_normal_table" >
                        <tbody>
                            <tr>
                                <td>
                                    <p>First name</p>
                                    <input class="inputs validate" type="text" id="first_name" name="first_name" required placeholder="Enter your first name">
                                </td>
                                <td>
                                    <p>Last Name</p>
                                    <input class="inputs validate" type="text"  id="last_name" name="last_name" required placeholder="Enter your last name">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Email</p>
                                    <input class="inputs validate" type="email" id="email" name="email" required placeholder="Enter your email">
                                </td>
                                <td>
                                    <p>Contact Number</p>
                                    <input class="inputs validate" type="text" id="contact_number" name="contact_number" required placeholder="Enter your contact number">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Concept</p>
                                    <input class="inputs" id="concept" name="concept" type="text" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="customer-box-footer">
                    <button class="btn" id="btn-submit" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </section>

    <script>

        const urlSegment = '{{ Request::segment(2) }}';

        customerUrl(urlSegment);
                
        $(document).ready(function() {

            if ("{{ (session('success') && is_array(session('success'))) }}"){
                $('.modal-success').css('visibility', 'visible');
            }

            $('#modal-close').on('click', function(){
                $('.modal-success').css('display', 'none');
            })

            $("#registration-form").submit(function(event) {

                event.preventDefault();

                const btnSubmit = $('#btn-submit');

                // Check if all required fields are valid
                if ($(this).find('.validate:invalid').length === 0) {
                    
                    const form = this;

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
                            form.submit();
                        }
                    });                
                } 
            });

            $('#existing-customer').select2({
                ajax: {
                    url: "{{ route('suggest_existing_customer') }}",
                    dataType: 'json',
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                templateResult: formatResult,
                templateSelection: formatSelection, 
            });

            $('#existing-customer').on('change',function(){
                const selectedValue = $(this).val();
                getInfo(selectedValue);
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
                bgColor = '#FED440';
                concept = convertToTitleCase(url)
            }else if (url == 'btb_x_open_source'){
                concept = 'BTB x open_source';
                bgColor = '#359D9D';
            }else if (url == 'open_source'){
                concept = url;
                bgColor = '#359D9D';
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
            console.log(selectedValue);
            $.ajax({
                url:"{{ route('viewCustomerInfo')}}",
                type:'POST',
                dataType:'json',
                data:{
                    _token:"{{ csrf_token()}}",
                    customerEmail: selectedValue,
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

    </script>
</body>
</html>