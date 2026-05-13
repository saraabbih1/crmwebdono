<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Subscription reminder</title>
</head>
<body style="margin:0;background:#f6f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#0f172a;color:#ffffff;padding:22px 28px;">
                            <h1 style="margin:0;font-size:20px;">Subscription reminder</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin-top:0;">Hello {{ $notification->client->name }},</p>

                            <p>{{ $notification->message }}</p>

                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin:24px 0;border-collapse:collapse;">
                                <tr>
                                    <td style="padding:10px;border:1px solid #e5e7eb;background:#f9fafb;">Service</td>
                                    <td style="padding:10px;border:1px solid #e5e7eb;">{{ strtoupper($notification->subscription->service_type) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px;border:1px solid #e5e7eb;background:#f9fafb;">End date</td>
                                    <td style="padding:10px;border:1px solid #e5e7eb;">{{ $notification->subscription->end_date->format('Y-m-d') }}</td>
                                </tr>
                            </table>

                            <p style="margin-bottom:0;">Thank you.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
