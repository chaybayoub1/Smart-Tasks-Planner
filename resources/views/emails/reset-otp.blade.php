<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Verification Code</title>
</head>
<body style="margin:0;padding:0;background:#0f0c2e;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#0f0c2e;padding:40px 16px;">
    <tr>
        <td align="center">

            <!-- Card -->
            <table width="520" cellpadding="0" cellspacing="0" style="max-width:520px;width:100%;background:#1a1740;border:1px solid rgba(99,102,241,.25);border-radius:20px;overflow:hidden;">

                <!-- Header band -->
                <tr>
                    <td style="background:linear-gradient(135deg,#6366f1 0%,#4f46e5 100%);padding:32px 40px;text-align:center;">
                        <div style="font-size:2.2rem;margin-bottom:8px;">🔐</div>
                        <p style="margin:0;font-size:1.3rem;font-weight:700;color:#fff;letter-spacing:-.02em;">
                            Verification Code
                        </p>
                        <p style="margin:6px 0 0;font-size:.85rem;color:rgba(255,255,255,.75);">
                            {{ config('app.name') }} — Password Reset
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:40px 40px 32px;">

                        <p style="margin:0 0 20px;font-size:.95rem;color:rgba(255,255,255,.6);line-height:1.6;">
                            Hi there,<br><br>
                            You requested a password reset for your account associated with
                            <strong style="color:#a5b4fc;">{{ $email }}</strong>.
                            Use the code below to verify your identity.
                        </p>

                        <!-- OTP box -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin:28px 0;">
                            <tr>
                                <td align="center">
                                    <div style="display:inline-block;background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.35);border-radius:14px;padding:22px 44px;">
                                        <p style="margin:0;font-size:.7rem;font-weight:700;color:rgba(255,255,255,.4);letter-spacing:.1em;text-transform:uppercase;margin-bottom:10px;">
                                            Your verification code
                                        </p>
                                        <p style="margin:0;font-size:2.8rem;font-weight:800;color:#fff;letter-spacing:.35em;font-family:'Courier New',monospace;">
                                            {{ $otp }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- Expiry notice -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);border-radius:10px;padding:12px 16px;">
                                    <p style="margin:0;font-size:.82rem;color:#fcd34d;display:flex;align-items:center;gap:6px;">
                                        ⏱ &nbsp;This code expires in <strong>10 minutes</strong>.
                                        Do not share it with anyone.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 6px;font-size:.85rem;color:rgba(255,255,255,.35);line-height:1.6;">
                            If you did not request a password reset, you can safely ignore this email.
                            Your password will remain unchanged.
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="border-top:1px solid rgba(255,255,255,.08);padding:20px 40px;text-align:center;">
                        <p style="margin:0;font-size:.75rem;color:rgba(255,255,255,.25);">
                            © {{ date('Y') }} {{ config('app.name') }} · This is an automated message, please do not reply.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
