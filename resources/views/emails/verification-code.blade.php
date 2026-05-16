<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Verify your email – SmarTasker</title>
    {{--
    ══════════════════════════════════════════════════════════════
    ROOT-CAUSE FIX: Email-client CSS compatibility
    ──────────────────────────────────────────────────────────────
    The original template used:
      • rgba() colours         → stripped by Outlook / Yahoo Mail
      • CSS flexbox            → ignored by most email clients
      • box-shadow             → ignored everywhere in email
      • `display:inline-flex`  → ignored by most email clients
      • External/web fonts     → blocked by Gmail

    Email clients do not render web CSS. The rules are:
      • Use table-based layout (not divs/flex/grid).
      • Inline every style or use a <style> block (Gmail strips <style>
        from <head> but keeps it in <body>; best practice: do both).
      • Use only hex colours — rgba() is stripped.
      • Avoid box-shadow, text-shadow, border-radius > simple px values.
      • Use web-safe fonts only.

    This rewrite keeps the same dark-purple SmarTasker visual identity
    but encodes it in table-based, inline-safe HTML so it renders
    correctly in Gmail, Outlook, Apple Mail, and Yahoo Mail.
    ══════════════════════════════════════════════════════════════
    --}}
    <style type="text/css">
        /* Fallback reset — some clients honour <head> styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* Outlook link colour override */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* Code digits — large monospace */
        .code-digits {
            font-family: 'Courier New', Courier, monospace !important;
            font-size: 34px !important;
            font-weight: 900 !important;
            letter-spacing: 10px !important;
            color: #c7d2fe !important;
        }

        /* Mobile responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper  { width: 100% !important; padding: 20px 12px !important; }
            .email-card     { padding: 28px 20px !important; }
            .code-digits    { font-size: 26px !important; letter-spacing: 6px !important; }
        }
    </style>
</head>

{{--
    IMPORTANT: <body> background must be a solid hex colour.
    The original #0f0e1a is kept — it renders correctly because it's
    a simple hex value (not rgba).
--}}
<body style="margin:0; padding:0; background-color:#0f0e1a;">

    {{-- ── Outer wrapper table ──────────────────────────────── --}}
    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
           width="100%" style="background-color:#0f0e1a;">
        <tr>
            <td align="center" style="padding: 40px 20px;">

                {{-- ── Inner constrained table ─────────────────────── --}}
                <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                       width="520" class="email-wrapper"
                       style="max-width:520px; width:100%;">

                    {{-- ── Logo / brand header ─────────────────────── --}}
                    <tr>
                        <td align="center" style="padding-bottom: 28px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" valign="middle"
                                        width="40" height="40"
                                        style="width:40px; height:40px;
                                               background-color:#4f46e5;
                                               border-radius:10px;
                                               font-size:20px;
                                               text-align:center;
                                               vertical-align:middle;
                                               mso-padding-alt:0;">
                                        &#9889;
                                    </td>
                                    <td style="padding-left:10px;
                                               font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                               font-size:22px;
                                               font-weight:800;
                                               color:#a5b4fc;
                                               letter-spacing:-0.02em;
                                               white-space:nowrap;">
                                        SmarTasker
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- ── Main card ───────────────────────────────── --}}
                    <tr>
                        <td class="email-card"
                            style="background-color:#1a1740;
                                   border:1px solid #3730a3;
                                   border-radius:16px;
                                   padding:40px 36px;">

                            {{-- Greeting --}}
                            <p style="margin:0 0 8px;
                                      font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                      font-size:15px;
                                      color:#a5b4fc;">
                                Hello, {{ $userName }} &#128075;
                            </p>

                            {{-- Headline --}}
                            <h1 style="margin:0 0 16px;
                                       font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                       font-size:26px;
                                       font-weight:800;
                                       color:#ffffff;
                                       line-height:1.2;">
                                Verify your <span style="color:#818cf8;">email address</span>
                            </h1>

                            {{-- Body text --}}
                            <p style="margin:0 0 28px;
                                      font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                      font-size:14px;
                                      color:#94a3b8;
                                      line-height:1.7;">
                                Thanks for signing up! Enter the verification code below
                                on the verification page. The code is valid for
                                <strong style="color:#e2e8f0;">{{ $expiresIn }} minutes</strong>.
                            </p>

                            {{-- Code label --}}
                            <p style="margin:0 0 12px;
                                      font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                      font-size:11px;
                                      font-weight:700;
                                      letter-spacing:0.12em;
                                      text-transform:uppercase;
                                      color:#6b7280;">
                                &#9881; Your verification code
                            </p>

                            {{-- ── Code block ────────────────────────── --}}
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                   width="100%" style="margin-bottom:20px;">
                                <tr>
                                    <td align="center"
                                        style="background-color:#1e1b4b;
                                               border:2px solid #4338ca;
                                               border-radius:14px;
                                               padding:24px 20px;">

                                        {{-- The code itself — most important element --}}
                                        <div class="code-digits"
                                             style="font-family:'Courier New',Courier,monospace;
                                                    font-size:34px;
                                                    font-weight:900;
                                                    letter-spacing:10px;
                                                    color:#c7d2fe;
                                                    mso-line-height-rule:exactly;">
                                            {{ $code }}
                                        </div>

                                        <p style="margin:10px 0 0;
                                                  font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                                  font-size:12px;
                                                  color:#6b7280;">
                                            Copy or type this code exactly as shown
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- ── Expiry badge (table-based, no flexbox) ── --}}
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                   style="margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#2d2106;
                                               border:1px solid #854d0e;
                                               border-radius:8px;
                                               padding:8px 14px;
                                               font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                               font-size:12px;
                                               font-weight:600;
                                               color:#fbbf24;
                                               white-space:nowrap;">
                                        &#9203; Expires in {{ $expiresIn }} minutes
                                    </td>
                                </tr>
                            </table>

                            {{-- Divider --}}
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                   width="100%" style="margin:0 0 24px;">
                                <tr>
                                    <td height="1" style="height:1px;
                                                          background-color:#2d2b55;
                                                          font-size:1px;
                                                          line-height:1px;">
                                        &nbsp;
                                    </td>
                                </tr>
                            </table>

                            {{-- ── Security notice ────────────────────── --}}
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                   width="100%">
                                <tr>
                                    <td style="background-color:#2a0f0f;
                                               border:1px solid #7f1d1d;
                                               border-radius:10px;
                                               padding:14px 16px;
                                               font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                               font-size:12px;
                                               color:#9ca3af;
                                               line-height:1.6;">
                                        <strong style="color:#d1d5db;">Security notice:</strong>
                                        If you did not create a SmarTasker account, you can safely
                                        ignore this email. Never share this code with anyone —
                                        our team will never ask for it.
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- ── Footer ──────────────────────────────────── --}}
                    <tr>
                        <td align="center" style="padding-top:28px;">
                            <p style="margin:0;
                                      font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                      font-size:12px;
                                      color:#374151;
                                      line-height:1.7;">
                                &copy; {{ date('Y') }} SmarTasker. All rights reserved.
                            </p>
                            <p style="margin:6px 0 0;
                                      font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                      font-size:12px;
                                      color:#374151;">
                                This is an automated message — please do not reply to this email.
                            </p>
                            {{-- Show the recipient address so they can confirm it went to the right inbox --}}
                            <p style="margin:6px 0 0;
                                      font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
                                      font-size:11px;
                                      color:#2d2b55;">
                                Sent to {{ $userEmail }}
                            </p>
                        </td>
                    </tr>

                </table>
                {{-- /inner constrained table --}}

            </td>
        </tr>
    </table>
    {{-- /outer wrapper table --}}

</body>
</html>
