<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Apstiprinājuma kods</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f7f7f5; margin:0; padding:32px;">
    <div style="max-width:520px; margin:0 auto; background:#ffffff; border-radius:16px; padding:40px; box-shadow:0 1px 3px rgba(0,0,0,0.04);">

        <h1 style="font-family: 'Playfair Display', Georgia, serif; font-weight:500; color:#1a1a1a; margin:0 0 8px;">
            {{ config('app.name') }}
        </h1>
        <p style="color:#888; margin:0 0 32px; font-size:14px;">Apstiprini savu e-pasta adresi</p>

        <p style="color:#333; font-size:15px; line-height:1.6;">Sveiks, {{ $name }}!</p>

        <p style="color:#333; font-size:15px; line-height:1.6;">
            Paldies, ka reģistrējies. Izmanto zemāk redzamo kodu, lai apstiprinātu e-pastu un aktivizētu kontu.
            Šis kods derīgs <strong>10 minūtes</strong>.
        </p>

        <div style="margin:32px 0; padding:24px; background:#f7f7f5; border-radius:12px; text-align:center;">
            <p style="margin:0; font-family:monospace; font-size:36px; font-weight:600; letter-spacing:0.4em; color:#1a1a1a;">
                {{ $code }}
            </p>
        </div>

        <p style="color:#888; font-size:13px; line-height:1.6;">
            Ja tu nemēģināji izveidot kontu, šo e-pastu vari droši ignorēt —
            bez šī koda konts netiks izveidots.
        </p>

        <hr style="border:none; border-top:1px solid #eee; margin:32px 0;">

        <p style="color:#aaa; font-size:12px; margin:0;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Veidots ar rūpību, klusākam prātam.
        </p>

    </div>
</body>
</html>
