<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Arial', sans-serif; background: #F8FAF8; padding: 40px; }
        .card { background: #fff; padding: 30px; border-radius: 12px; max-width: 500px; margin: 0 auto; text-align: center; border: 1px solid #E3EAE3; }
        .code { font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #16A34A; margin: 20px 0; background: #F0FDF4; padding: 15px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="color: #172117;">Verifikasi Email Kamu</h2>
        <p style="color: #647164;">Gunakan kode di bawah ini untuk menyelesaikan pendaftaran akun SILABUNG kamu:</p>
        <div class="code">{{ $otp }}</div>
        <p style="color: #647164; font-size: 12px;">Kode ini berlaku selama 15 menit. Jangan bagikan kode ini kepada siapa pun.</p>
    </div>
</body>
</html>