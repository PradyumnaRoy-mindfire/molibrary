<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP for Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="20" cellspacing="0" style="background-color: #ffffff; margin-top: 50px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="text-align: center;">
                            <h2 style="color: #2c3e50;">Reset Your Password</h2>
                            <p style="font-size: 16px; color: #555;">
                                We received a request to reset the password for your account.
                            </p>
                            <p style="font-size: 18px; font-weight: bold; color: #000;">
                                Your OTP is:
                            </p>
                            <p style="font-size: 32px; font-weight: bold; color: #007bff; letter-spacing: 4px;">
                                {{ $otp }}
                            </p>
                            <p style="font-size: 14px; color: #888;">
                                This OTP is valid for a limited time. Please do not share it with anyone.
                            </p>
                            <p style="font-size: 16px; color: #555; margin-top: 30px;">
                                If you didn't request a password reset, you can ignore this email.
                            </p>
                            <hr style="margin: 30px 0;">
                            <p style="font-size: 12px; color: #aaa;">
                                &copy; {{ now()->year }} Molibrary.in. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
