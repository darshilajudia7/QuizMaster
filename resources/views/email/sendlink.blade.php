<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify Your Email Address</title>
</head>

<body
    style="margin:0;padding:0;background-color:#F5F4FC;font-family:'Segoe UI',Arial,sans-serif;-webkit-font-smoothing:antialiased;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#F5F4FC">
        <tr>
            <td align="center" style="padding:40px 15px;">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="background-color:#FFFFFF;border-radius:24px;overflow:hidden;box-shadow:0 10px 35px rgba(61,47,160,0.12);width:100%;max-width:600px;">

                    <tr>
                        <td align="center"
                            style="background:linear-gradient(135deg,#3D2FA0,#5243C2);padding:40px 30px;">
                            <div
                                style="width:70px;height:70px;line-height:70px;background:rgba(255,255,255,0.15);border-radius:50%;font-size:32px;color:#ffffff;margin:0 auto 20px auto;text-align:center;">
                                🔐
                            </div>
                            <h1 style="margin:0 0 10px 0;color:#ffffff;font-size:28px;font-weight:700;line-height:1.2;">
                                Email Verification
                            </h1>
                            <p style="margin:0;color:rgba(255,255,255,0.85);font-size:15px;letter-spacing: 0.5px;">
                                Secure Link Verification
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:40px;">
                            <h2 style="margin:0 0 15px 0;color:#111827;font-size:24px;font-weight:600;">
                                Hi {{ $name }},
                            </h2>
                            <p style="color:#6B7280;line-height:1.7;font-size:15px;margin:0 0 30px 0;">
                                Thank you for creating an account with <strong>QuizMaster</strong>. Please click the
                                button below to verify your email address and activate your account.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $verificationLink }}" target="_blank"
                                            style="background-color:#3D2FA0;border-radius:14px;color:#ffffff;display:inline-block;font-size:16px;font-weight:700;line-height:55px;text-align:center;text-decoration:none;width:240px;box-shadow:0 4px 12px rgba(61,47,160,0.25);-webkit-text-size-adjust:none;">
                                            Verify Email Address
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin-top:35px;background-color:#FFF0ED;border-radius:16px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <h4 style="margin:0 0 8px 0;color:#FF6B4A;font-size:15px;font-weight:600;">
                                            Security Notice
                                        </h4>
                                        <p style="margin:0;color:#6B7280;font-size:14px;line-height:1.6;">
                                            If you did not request this email, no further action is required; your email
                                            address will not be registered without verification. Please do not forward
                                            this link to anyone.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="background-color:#F8FAFC;padding:30px;text-align:center;border-top:1px solid #E5E7EB;">
                            <p style="margin:15px 0 0 0;color:#BDB9DA;font-size:11px;">
                                This is an automated security email. Please do not reply directly to this message.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
