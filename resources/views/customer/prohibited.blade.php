<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap');

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Quicksand', sans-serif;
        }

        .prohibited-center{
            height: 100vh;
            width: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            padding: 10px;
        }

        .prohibited-box{
            min-height: auto;
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 1rem rgba(0,0,0,0.3);
            animation: fadeIn 1.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .prohibited-content{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
        }

        #alert-img{
            object-fit: content;
            width: 120px;
        }

        .pb{
            * {
                margin-bottom: 20px;
            }
        }

        .custom_normal_table td{
            padding: 10px;
        }

        .custom_normal_table td p{
            font-weight: bold;
            color: #4d4b4b !important;
            font-size: 1rem;
        }

        .custom_table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f9f9f9;
            border-radius: 5px !important;
        }

        .custom_table th, .custom_table td {
            border: 1px solid #ddd;
            text-align: left;
            width: 300px;
            font-size: 14px;
        }

        .custom_table td{
            padding: 10px;
        }

        .custom_table td p{
            font-weight: bold;
            color: #4d4b4b !important;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        /* Use a media query to apply styles for smaller screens */
        @media only screen and (max-width: 500px) {
            /* Set the table row (tr) to be displayed as a block */
            .custom_table tr, .custom_normal_table tr, .custom_sm_normal_table tr {
                display: block;
            }

            /* Hide table headers on smaller screens */
            .custom_table th, .custom_normal_table th, .custom_sm_normal_table th{
                display: none;
            }

            /* Style table data (td) for smaller screens */
            .custom_table td, .custom_normal_table td, .custom_sm_normal_table{
                display: block;
                text-align: left;
                width: 100%;
            }

            .customer-box-header-container{
                flex-direction: column;
                gap: 30px;
            }
        }

        .inputs{
            margin-top: 5px;
            height: 37px;
            width: 100%;
            border: 1px solid #b1b0b0;
            border-top: 3px solid #b1b0b0;
            padding: 0 10px;
            border-radius: 5px;
            color: #3c3b3b;
            font-size: .9rem;
            font-weight: bold;
        }

    </style>
</head>
<body>
    @if((session('success') && is_array(session('success'))))
    <div class="prohibited-center">
        <div class="prohibited-box">
            <div class="prohibited-content">
                <img id="alert-img" src="{{ asset('img/checked.png') }}" alt="">
                <h2 style="text-align: center; margin-top: 15px;">Successfully Submitted</h2>
                <table class="custom_normal_table" style="margin-top: 15px; width: 100%; max-width: 500px;">
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
                <br>
                {{-- <h4 style="text-align: center; color: #8f8f91;">For more information, please contact the BPG department.</h4> --}}
            </div>
        </div>
    </div>
    @else
    <div class="prohibited-center">
        <div class="prohibited-box pb">
            <div class="prohibited-content">
                <img id="alert-img" src="{{ asset('img/alert.png') }}" alt="">
                <h1 style="text-align: center;">Access Denied</h1>
                <h3 style="font-weight: 500; text-align: center; max-width: 550px;">You cannot access this area. Please make sure you have the necessary permissions to view this content.</h3>
                <br>
                {{-- <h4 style="text-align: center; color: #8f8f91;">For more information, please contact the BPG department.</h4> --}}
            </div>
        </div>
    </div>
    @endif

    <script>
        function preventBack() {
            window.history.forward();
        }
        window.onunload = function() {
            null;
        };
        setTimeout("preventBack()",0);
    </script>

</body>
</html>