<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collaboration invitation</title>
</head>
<body style="margin:0;padding:0;background:#0f0e1a;font-family:Segoe UI,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#0f0e1a;padding:40px 16px;">
    <tr>
        <td align="center">
            <table width="540" cellpadding="0" cellspacing="0" style="max-width:540px;width:100%;background:#1a1740;border:1px solid #3730a3;border-radius:16px;overflow:hidden;">
                <tr>
                    <td style="padding:34px 38px;text-align:center;background:#4f46e5;">
                        <div style="font-size:34px;line-height:1;margin-bottom:10px;">&#128101;</div>
                        <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:800;">Collaboration invitation</h1>
                        <p style="margin:8px 0 0;color:#dbeafe;font-size:14px;">{{ config('app.name') }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:36px 38px;">
                        <p style="margin:0 0 16px;color:#a5b4fc;font-size:15px;">
                            Hi,
                        </p>
                        <p style="margin:0 0 20px;color:#e5e7eb;font-size:15px;line-height:1.7;">
                            <strong>{{ $inviterName }}</strong> invited you to join the group
                            <strong style="color:#ffffff;">{{ $groupName }}</strong>.
                        </p>

                        @if($groupDescription)
                            <p style="margin:0 0 24px;color:#94a3b8;font-size:14px;line-height:1.7;">
                                {{ $groupDescription }}
                            </p>
                        @endif

                        <table cellpadding="0" cellspacing="0" align="center" style="margin:28px auto;">
                            <tr>
                                <td align="center" style="background:#6366f1;border-radius:10px;">
                                    <a href="{{ $acceptUrl }}" style="display:inline-block;padding:13px 24px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;">
                                        Accept invitation
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 12px;color:#9ca3af;font-size:12px;line-height:1.6;">
                            If the button does not work, copy this link into your browser:
                        </p>
                        <p style="margin:0 0 22px;color:#a5b4fc;font-size:12px;line-height:1.6;word-break:break-all;">
                            {{ $acceptUrl }}
                        </p>

                        @if($expiresAt)
                            <p style="margin:0;color:#fbbf24;font-size:12px;">
                                This invitation expires on {{ $expiresAt->format('M d, Y H:i') }}.
                            </p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="border-top:1px solid #2d2b55;padding:20px 38px;text-align:center;">
                        <p style="margin:0;color:#6b7280;font-size:12px;">
                            Sent to {{ $invitedEmail }} by {{ config('app.name') }}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
