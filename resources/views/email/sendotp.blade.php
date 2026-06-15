<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
</head>

<body style="margin:0;padding:0;background:#F5F4FC;font-family:Segoe UI,Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#F5F4FC">
        <tr>
            <td align="center" style="padding:40px 15px;">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#FFFFFF;border-radius:24px;overflow:hidden;box-shadow:0 10px 35px rgba(61,47,160,0.12);">

                    <!-- Header -->
                    <tr>
                        <td align="center"
                            style="background:linear-gradient(135deg,#3D2FA0,#5243C2);padding:40px 30px;">

                            <div
                                style="
                                width:70px;
                                height:70px;
                                line-height:70px;
                                background:rgba(255,255,255,0.15);
                                border-radius:50%;
                                font-size:32px;
                                color:#ffffff;
                                margin:auto;">
                                🔐
                            </div>

                            <h1
                                style="
                                margin:20px 0 10px;
                                color:#ffffff;
                                font-size:28px;
                                font-weight:700;">
                                Email Verification
                            </h1>

                            <p
                                style="
                                margin:0;
                                color:rgba(255,255,255,0.85);
                                font-size:15px;">
                                Secure OTP Verification
                            </p>

                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:40px;">

                            <h2
                                style="
                                margin:0 0 15px;
                                color:#111827;
                                font-size:24px;">
                                Hello, {{ $name }}
                            </h2>

                            <p
                                style="
                                color:#6B7280;
                                line-height:1.7;
                                font-size:15px;
                                margin-bottom:30px;">
                                Thank you for registering. Use the One Time Password below to verify your email address.
                            </p>

                            <!-- OTP Box -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">

                                        <div
                                            style="
                                            background:#EEF0FF;
                                            border:2px dashed #7B6FD8;
                                            border-radius:16px;
                                            padding:25px;
                                            display:inline-block;">

                                            <span
                                                style="
                                                font-size:38px;
                                                letter-spacing:8px;
                                                font-weight:700;
                                                color:#3D2FA0;">
                                                {{ $otp }}
                                            </span>

                                        </div>

                                    </td>
                                </tr>
                            </table>

                            <p
                                style="
                                margin-top:30px;
                                color:#4A4670;
                                text-align:center;
                                font-size:14px;">
                                This OTP will expire in
                                <strong style="color:#FF6B4A;">1 minutes</strong>
                            </p>

                            <!-- Security Note -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="margin-top:30px;background:#FFF0ED;border-radius:16px;">
                                <tr>
                                    <td style="padding:20px;">

                                        <h4
                                            style="
                                            margin:0 0 10px;
                                            color:#FF6B4A;">
                                            Security Notice
                                        </h4>

                                        <p
                                            style="
                                            margin:0;
                                            color:#6B7280;
                                            font-size:14px;
                                            line-height:1.6;">
                                            Never share this OTP with anyone. Our team will never ask for your
                                            verification code.
                                        </p>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="
                            background:#F8FAFC;
                            padding:25px;
                            text-align:center;
                            border-top:1px solid #E5E7EB;">

                            <p
                                style="
                                margin-top:8px;
                                color:#8F8CB0;
                                font-size:12px;">
                                This is an automated email. Please do not reply.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
