<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/358f25eac2.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .qrcode-container {
            height: 100vh;
            width: 100%;
            display: flex;
            justify-content: center;
            flex-direction: column;
            padding: 10px;
        }

        .qr-code{
            position: relative;
            background-color: rgb(255, 255, 255);
            max-height: 450px;
            max-width: 450px;
            padding: 25px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            border-radius: 10px;
        }

        .text-center{
            text-align: center;
        }

        .qr-header{
            max-height: 450px;
            max-width: 450px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 10px;
            margin: auto;
            border-radius: 10px;
            padding: 15px 5px;
            font-weight: 600;
            font-family: sans-serif;
            font-size: 17px;
            background: #fff;
        }

        .qr-title{
            font-weight: bold;
            margin: 15px 0;
            font-size: 1.5rem;
        }

    </style>
</head>
<body>
    <div class="qrcode-container">
        <div class="qr-content">
            <div class="text-center qr-title">{{ str_replace('_', ' ', Request::segment(3)) }}</div>
            <div class="qr-code text-center">
                {!! QrCode::size(400)->gradient(55, 0, 0, 0, 0, 125, 'vertical')->generate(url("customer_registration/".Request::segment(2)."/".Request::segment(3)."/".Request::segment(4))); !!}
            </div>
            <br>
            <div class="qr-header text-center">
                <i class="fa-solid fa-qrcode" style="color: rgb(14, 81, 147); font-size: 20px;"></i> Scan Me
            </div>
        </div>
    </div>

    <script>

        const url = "{{ Request::segment(2) }}";

        function customerUrl(url) {
            let bgColor = '';

            if (url == 'beyond_the_box') {
                bgColor = 'linear-gradient(to right, #d0d2d4, #f4f4f4)';
            } else if (url == 'digital_walker') {
                bgColor = 'linear-gradient(to right, #fff8dc, #F3DD3E)';
            } else if (url == 'dw_and_btb') {
                bgColor = 'linear-gradient(to right, #359D9D, #4FD3D3)';
            } else if (url == 'open_source') {
                bgColor = 'linear-gradient(to right, #359D9D, #4FD3D3)';
            }

            $('body').css('background', bgColor);
        }

        // Call the function with the initial URL value
        customerUrl(url);

    </script>
</body>
</html>