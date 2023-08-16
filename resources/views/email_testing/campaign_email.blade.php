<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        /* Add your CSS styles here */
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">

    <table class="campaign-email-section" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; padding: 20px;">
        <tr>
            <td align="center" valign="top">
                <table class="campaign-email-whole-box" width="100%" cellpadding="0" cellspacing="0" border="0" style=" max-width: 800px; border-radius: 10px; margin: 0 auto; background-color: #ffffff;">
                    <tr>
                        <td align="center" valign="top" style="background-color: rgb(124, 58, 237); border: 1px solid rgb(124, 58, 237); text-align: center; padding: 30px 0; width: 100%;">
                            <h1 style="color: white; margin: 0;">DEVP System</h1>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="height: 100%; width: 100%; padding: 47px 40px; font-size: 15px; border: 1px solid #ddd;">
                            @if ($campaign_status == 1)
                            <p style="margin: 0;"><b>Please log in to your account to review and approve the campaign</b></p>
                            <br>
                            <p style="margin: 0;"><b>Campaign Status: </b><span style="background-color: #FB923C; color: white; padding: 2px 15px; border-radius: 5px;">FOR ACCOUNTING APPROVAL</span></p>
                            @elseif($campaign_status == 2)
                            <p style="margin: 0;"><b>We are pleased to inform you that the status of your campaign has been updated</b></p>
                            <br>
                            <p style="margin: 0;"><b>Campaign Status: </b><span style="background-color: #4ADE80; color: white; padding: 2px 15px; border-radius: 5px;">APPROVED</span></p>
                            @else
                            <p style="margin: 0;"><b>New campaign created</b></p>
                            <p style="margin: 0;"><b>Status: </b><span style="background-color: rgb(34, 211, 238); color: white; padding: 2px 15px; border-radius: 5px;">FOR ADMIN APPROVAL</span></p>
                            @endif
                            <br>
                            <p style="margin: 0;"><b>Campaign Information:</b></p>
                            <p style="margin: 0;"><b>Campaign ID: {{ $campaign_id }}</b></p>
                            <p style="margin: 0;"><b>GC Description: {{ $gc_description }}</b></p>
                            <p style="margin: 0;"><b>GC Value: {{ $gc_value }}</b></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
