<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/358f25eac2.js" crossorigin="anonymous"></script>
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
        }

    </style>
</head>
<body>
    <div class="qrcode-container">
        <div class="qr-content">
            <div class="qr-code text-center">
                {!! QrCode::size(400)->gradient(55, 0, 0, 0, 0, 125, 'vertical')->generate(url("customer_registration/".Request::segment(2)."/".Request::segment(3))); !!}
            </div>
            <br>
            <div class="qr-header text-center"><i class="fa-solid fa-qrcode" style="color: rgb(14, 81, 147); font-size: 20px;"></i> Scan Me</div>
        </div>
    </div>
</body>
</html>