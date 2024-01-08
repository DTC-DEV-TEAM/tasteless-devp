<!DOCTYPE html>
<html>
<head>
    <!-- Add the html2canvas library -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <style>
        /* Inline CSS styles */
        html{
            width: 100%;
        }

        body {
            /* font-family: Arial, sans-serif; */
            color: #333;
            
        }

        .container {
            width: 100%;
            text-align: center;
        }
        
        .container1 {
            width: 100%;
        }

        .test{
            max-height:0;
            position: relative;
            opacity: 0.999;
            width: 800px;
            text-align: center;
        }

        .test1{
            max-height:0;
            position: relative;
            opacity: 0.999;
            width: 800px;
            text-align: left;
        }

        .position{
            margin-top: 62px;
            margin-left: 427px;
            text-align: right;
            display: inline-block;
        }

        .position2{
            margin-top: 59.5px;
            margin-left: 422px;
            text-align: right;
            display: inline-block;
        }

        .position3{
            margin-top: 36.5px;
            margin-left: 401px;
            text-align: right;
            display: inline-block;
        }

        .position_campaign_value{
            margin-top: 180px;
            display: inline-block;
            width: 510px;
            text-align: center;
        }
        
        .position_campaign_value span{
            font-weight: 700;
            color: white;
            font-size: 60px; 
            color: #d85a5f;
        }

        .position_campaign_value2{
            margin-top: 180px;
            display: inline-block;
            width: 508px;
            text-align: center;
        }
        
        .position_campaign_value2 span{
            font-weight: 700;
            /* font-family: 'Lato', sans-serif; */
            color: rgb(0, 0, 0);
            font-size: 60px; 
        }

        .position_campaign_value3{
            margin-top: 180px;
            display: inline-block;
            width: 400px;
            text-align: center;
        }
        
        .position_campaign_value3 span{
            font-weight: 700;
            /* font-family: 'Lato', sans-serif; */
            color: white;
            font-size: 60px; 
            color: #3ccbd2;
        }

        @media only screen and (max-width: 600px) {
            .position_campaign_value, .position_campaign_value3, .position_campaign_value3{
                margin-top: 200px;
            }
        }

    </style>
</head>
<body> 
    @if ($html_email_img)
    <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 800px; margin: auto;">
        @if($store_logo == 1)
        {{-- <tr>
            <td align="center">
                <div class="test" style="margin: auto;">
                    <div class="position">
                        {!! $qr_code !!}
                    </div>
                </div>
                <div class="test1" style="margin: auto;">
                    <div class="position_campaign_value">
                        <span>₱{{ $gc_value }}</span>
                    </div>
                </div>
            </td>
        </tr> --}}
        <tr>
            <td align="center">
                <div style="width: 100%; text-align:center;">
                    <img src="{{ $message->embed(public_path() . "/$qr_code_generated") }}" width="800" style="height: 300px; min-width: 800px; object-fit: contain; text-align: center; pointer-events: none;" />
                    {{-- <img src="{{ $message->embed(public_path() . '/store_logo/img/digital_walker.png' ) }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" /> --}}
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">
                <div class="container">
                    <img src="{{ $message->embed(public_path() . '/store_logo/img/digital_walker_claiming.png' ) }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                </div>
            </td>
        </tr>
        @endif
        @if($store_logo == 2)
        {{-- <tr>
            <td align="center">
                <div class="test" style="margin: auto;">
                    <div class="position2">
                        {!! $qr_code !!}
                    </div>
                </div>
                <div class="test1" style="margin: auto;">
                    <div class="position_campaign_value2">
                        <span style="color: black;">₱{{ $gc_value }}</span>
                    </div>
                </div>
            </td>
        </tr> --}}
        <tr>
            <td align="center">
                <div style="width: 100%; text-align:center;">
                    <img src="{{ $message->embed(public_path() . "/$qr_code_generated") }}" width="800" style="height: 300px; min-width: 800px; object-fit: contain; text-align: center; pointer-events: none;" />
                    {{-- <img src="{{ $message->embed(public_path() . '/store_logo/img/beyond_the_box.png') }}" width="800" style="height: 300px; min-width: 800px; object-fit: contain; text-align: center; pointer-events: none;" /> --}}
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">
                <div class="container">
                    <img src="{{ $message->embed(public_path() . '/store_logo/img/beyond_the_box_claiming.png' ) }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                </div>
            </td>
        </tr>
        @endif
        @if($store_logo == 3)
        {{-- <tr>
            <td align="center">
                <div class="test" style="margin: auto;">
                    <div class="position3">
                        {!! $qr_code !!}
                    </div>
                </div>
                <div class="test1" style="margin: auto;">
                    <div class="position_campaign_value3">
                        <span>₱{{ $gc_value }}</span>
                    </div>
                </div>
            </td>
        </tr> --}}
        <tr>
            <td align="center">
                <div style="width: 100%; text-align:center;">
                    <img src="{{ $message->embed(public_path() . "/$qr_code_generated") }}" width="800" style="height: 300px; min-width: 800px; object-fit: contain; text-align: center; pointer-events: none;" />
                    {{-- <img src="{{ $message->embed(public_path() . '/store_logo/img/btb_and_dw.png') }}" width="800" style="height: 300px; min-width: 800px; object-fit: contain; text-align: center; pointer-events: none;" /> --}}
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">
                <div class="container">
                    <img src="{{ $message->embed(public_path() . '/store_logo/img/beyond_the_box_claiming.png' ) }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                </div>
            </td>
        </tr>
        @endif
        <tr>
            <td align="center">
                <div class="container">
                    <img src="{{ $message->embed(public_path() . '/uploaded_item/email_img/' . $html_email_img) }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                </div>
            </td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" width="800" style="border-collapse: collapse; min-width: 800px; margin: auto;">
        <tr>
            <td align="center" style="background-color: #EBEBEC;">
                <div>
                    <p style="font-weight: bold">This is an auto-generated email. Please do not reply to this message.</p>
                    <p style="font-weight: bold">&copy; digitsph</p>
                </div>
            </td>
        </tr>
    </table>

    @else
    <div class="container1">
        {!! html_entity_decode($html_email) !!}
    </div>
    @endif

    <script>
        $(document).ready(function() {

            $('#download').on('click', function() {
                var divToConvert = $('#download_qr');
                
                html2canvas(divToConvert[0]).then(function(canvas) {
                    var imageDataURL = canvas.toDataURL('image/png');
                    
                    // Create a temporary link element
                    var link = document.createElement('a');
                    link.href = imageDataURL;
                    link.download = 'qr_image.png'; // Set the desired file name
                    
                    // Trigger a click event on the link to start the download
                    link.click();
                    
                });
            });
        });  

    </script>


</body>
</html>
