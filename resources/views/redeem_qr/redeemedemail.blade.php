<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
        {{-- {!! html_entity_decode($htmlEmail) !!} --}}
        <h3>QR Code Redemption Successful</h3>

        <p>Dear {{ $row->name }},</p>
        <br>
        <p>We are delighted to inform you that your QR code has been successfully redeemed.</p>

        <p>Redemption Details:</p>

        <p>Campaign ID: {{ $row->campaign_id }}</p>
        <p>Redeemed On: {{ date('F d, Y', strtotime($row->cashier_date_transact)) }}</p>

        <p>If you have any questions or need further assistance, please don't hesitate to contact our customer support team.</p>

        <p>Thank you again for choosing our service.</p>
        <br>
        <p>Best regards,</p> 
        <p>BPG Department</p>
        <p>Digits Trading Corp.</p>        
    </div>
    <script>
        console.log('yes');
    </script>
</body>
</html>