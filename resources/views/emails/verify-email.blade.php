<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Verifikasi Email</title>
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
                                <h1 style="margin:0 0 16px;font-size:26px;font-weight:bold;line-height:1.3;color:#1c1917;">Halo, {{ $user->name }}</h1>
                                <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:#57534e;">Terima kasih sudah mendaftar di <strong>{{ config('app.name', 'Look at History') }}</strong>. Verifikasi alamat email kamu agar akun bisa digunakan.</p>
                                <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#57534e;">Tautan ini berlaku selama 60 menit. Jika kamu tidak merasa mendaftar, abaikan email ini.</p>
                                <p style="margin:0;">
                                    <a href="{{ $url }}" style="display:inline-block;padding:14px 28px;background-color:#1e3a5f;color:#ffffff;font-size:15px;font-weight:bold;text-decoration:none;border-radius:8px;">Verifikasi Email</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:24px 40px;background-color:#f5f5f4;border-top:1px solid #e7e5e4;">
                                <p style="margin:0;font-size:12px;color:#a8a29e;">&copy; {{ date('Y') }} {{ config('app.name', 'Look at History') }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>