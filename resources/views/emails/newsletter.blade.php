<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $body }}</title>
    </head>
    <body style="margin:0;padding:0;background-color:#faf9f7;font-family:Georgia,'Times New Roman',serif;color:#171717;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#faf9f7;">
            <tr>
                <td align="center" style="padding:40px 16px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#ffffff;border:1px solid #e7e5e4;border-radius:12px;overflow:hidden;">
                        <tr>
                            <td style="padding:36px 40px;border-bottom:1px solid #e7e5e4;">
                                <p style="margin:0;font-size:13px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#a8a29e;">Look at History</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:40px;">
                                <p style="margin:0 0 20px;font-size:15px;line-height:1.8;color:#57534e;white-space:pre-line;">{{ $body }}</p>
                                <p style="margin:0;font-size:15px;line-height:1.7;color:#57534e;">Selamat membaca,<br><strong style="color:#1c1917;">Redaksi Look at History</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:24px 40px;background-color:#f5f5f4;border-top:1px solid #e7e5e4;">
                                <p style="margin:0 0 12px;font-size:13px;line-height:1.6;color:#a8a29e;">
                                    Tidak ingin menerima email lagi?
                                    <a href="{{ route('newsletter.unsubscribe', $subscriber->token) }}" style="color:#1e3a5f;font-weight:bold;">Berhenti berlangganan</a>
                                </p>
                                <p style="margin:0;font-size:12px;color:#a8a29e;">&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>