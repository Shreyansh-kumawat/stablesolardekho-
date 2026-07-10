<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="420" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#f97316,#ea580c);padding:28px 32px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:800;">{{ config('app.name') }}</h1>
                            <p style="margin:4px 0 0;color:rgba(255,255,255,0.85);font-size:13px;">Email Verification</p>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;color:#334155;font-size:15px;line-height:1.5;">
                                Hello <strong>{{ $userName }}</strong>,
                            </p>
                            <p style="margin:0 0 24px;color:#475569;font-size:14px;line-height:1.6;">
                                Your verification code for account registration is:
                            </p>
                            <!-- OTP Box -->
                            <div style="background:#fff7ed;border:2px dashed #f97316;border-radius:10px;padding:20px;text-align:center;margin:0 0 24px;">
                                <span style="font-size:36px;font-weight:900;color:#ea580c;letter-spacing:8px;">{{ $otp }}</span>
                            </div>
                            <p style="margin:0 0 8px;color:#64748b;font-size:13px;line-height:1.5;">
                                This code is valid for <strong>10 minutes</strong>. Do not share it with anyone.
                            </p>
                            <p style="margin:0;color:#94a3b8;font-size:12px;line-height:1.5;">
                                If you did not request this code, please ignore this email.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8fafc;padding:16px 32px;border-top:1px solid #e2e8f0;text-align:center;">
                            <p style="margin:0;color:#94a3b8;font-size:11px;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
