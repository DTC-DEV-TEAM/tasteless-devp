<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">

    <table role="presentation" align="center" cellspacing="0" cellpadding="0" border="0" style="width: 100%; max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 5px; border: 1px solid #d1d0d0; padding: 20px;">
        <tr>
            <td style="text-align: center;">
                <img src="{{ $message->embed(public_path() . "/img/btb_dw_os.png") }}" alt="Image Description" style="max-width: 100%; width: 300px; height: auto;">
                <h2 style="color: #656464; padding: 0; margin: 0;">One-Time Password (OTP) Verification</h2>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 10px 0; border-radius: 5px; padding: 10px; width: 100%;">
                    <tr>
                        <td style="font-size: 20px; text-align: center; letter-spacing: 2px;">
                            <strong>{{ $otp }}</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        {{-- <tr>
            <td style="text-align: center;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 5px 0; border-radius: 5px; padding: 10px; width: 100%;">
                    <tr style="text-align: center;">
                        <td style="color: #color: #656464;; font-size: 14px;">
                            <span>Link: {{ $link }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr> --}}
        <tr>
            <td style="text-align: center;">
                <span style="color: #color: #656464;; font-size: 14px;">Please use this OTP to verify your E-Gift Card. Keep it confidential and do not share your OTP with anyone.</span>
            </td>
        </tr>
    </table>

</body>
</html>
