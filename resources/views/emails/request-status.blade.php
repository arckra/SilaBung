<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #F8FAF8; padding: 40px 20px; color: #172117; }
        .container { max-width: 550px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #E3EAE3; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #16A34A; padding: 25px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; letter-spacing: 1px; }
        .content { padding: 30px; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; }
        .badge-pending { background: #FEF3C7; color: #B45309; }
        .badge-accepted { background: #DCFCE7; color: #166534; }
        .badge-rejected { background: #FEE2E2; color: #991B1B; }
        .badge-completed { background: #E0F2FE; color: #0369A1; }
        .details { background: #F8FAF8; border: 1px solid #E3EAE3; border-radius: 12px; padding: 15px; margin: 20px 0; }
        .details-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .details-row:last-child { margin-bottom: 0; }
        .details-label { color: #647164; }
        .details-value { font-weight: bold; text-align: right; }
        .btn { display: inline-block; background: #16A34A; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-weight: bold; margin-top: 10px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #647164; border-top: 1px solid #E3EAE3; background: #FBFDFB; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SILABUNG</h1>
        </div>
        <div class="content">
            
            {{-- Pesan Pembuka --}}
            <p style="font-size: 16px; margin-top: 0;">Halo <b>{{ $recipientRole === 'supplier' ? $itemRequest->item->supplier->name : $itemRequest->customer->name }}</b>,</p>

            @if($type === 'submitted')
                <span class="badge badge-pending">Permintaan Baru</span>
                <p>Ada permintaan pengambilan barang baru yang masuk ke stok kamu. Segera tinjau permintaan ini di dashboard.</p>
            @elseif($type === 'accepted')
                <span class="badge badge-accepted">Diterima</span>
                <p>Kabar baik! Supplier telah <b>MENERIMA</b> permintaanmu. Silakan cek detail dan kode pengambilan di halaman Permintaan Saya.</p>
            @elseif($type === 'rejected')
                <span class="badge badge-rejected">Ditolak</span>
                <p>Mohon maaf, supplier <b>MENOLAK</b> permintaanmu untuk saat ini. Stok mungkin sudah habis atau barang sedang tidak tersedia.</p>
            @elseif($type === 'completed')
                <span class="badge badge-completed">Selesai</span>
                <p>Transaksi telah <b>SELESAI</b>. Terima kasih telah menggunakan SILABUNG untuk menyambungkan manfaat barang bekas!</p>
            @endif

            {{-- Detail Barang --}}
            <div class="details">
                <div class="details-row">
                    <div class="details-label">Barang</div>
                    <div class="details-value">{{ $itemRequest->item->name }}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Jumlah</div>
                    <div class="details-value">{{ $itemRequest->quantity }} {{ $itemRequest->item->unit }}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Customer</div>
                    <div class="details-value">{{ $itemRequest->customer->name }}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Supplier</div>
                    <div class="details-value">{{ $itemRequest->item->supplier->name }}</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $recipientRole === 'supplier' ? route('supplier.requests.index') : route('customer.requests.index') }}" class="btn">Lihat Detail Permintaan</a>
            </div>

        </div>
        <div class="footer">
            Email ini dibuat otomatis oleh sistem SILABUNG.<br>
            &copy; {{ date('Y') }} SILABUNG - Sirkula Sambung.
        </div>
    </div>
</body>
</html>