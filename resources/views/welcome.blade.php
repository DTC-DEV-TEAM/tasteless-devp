<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            {{-- <?php

                $url = 'https://devp.digitstrading.ph/api/get-token';
                $body = [
                    'secret' => '9f56aa110c022b17fc1c7cec3fca2016'
                ];
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
                $response = curl_exec($curl);
                curl_close($curl);
                $data = json_decode($response, true);

                $access_token = $data['data']['access_token'];

                // DEVP URL
                $devp_url = 'https://devp.digitstrading.ph/api/redemption_code';

                $headers = array(
                    'Authorization: Bearer ' . $access_token,
                );

                $curl = curl_init($devp_url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($curl);
                curl_close($curl);

                $data = json_decode($response, true);

                dd($data);
            ?> --}}
        </div>
    </body>
</html>
