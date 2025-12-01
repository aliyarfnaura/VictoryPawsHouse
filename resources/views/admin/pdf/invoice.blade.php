<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $booking->id_booking }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #6b4423; padding-bottom: 10px; }
        .header h1 { color: #6b4423; margin: 0; }
        .header p { margin: 5px 0; font-size: 12px; }
        
        .info-table { width: 100%; margin-bottom: 20px; font-size: 12px; }
        .info-table td { padding: 3px; }
        .label { font-weight: bold; width: 120px; }

        .items-table { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #6b4423; color: white; }
        .items-table td.price { text-align: right; }
        
        .total-section { text-align: right; margin-top: 20px; font-size: 14px; }
        .total-label { font-weight: bold; font-size: 16px; color: #6b4423; }
        
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
        
        .badge {
            padding: 2px 6px; 
            border-radius: 4px; 
            font-size: 10px; 
            font-weight: bold;
            color: white;
            text-transform: uppercase;
        }
        .bg-green { background-color: #10B981; color: white; }
        .bg-blue { background-color: #3B82F6; color: white; } 
        .bg-gray { background-color: #6B7280; color: white; }  
    </style>
</head>
<body>

    <div class="header">
        <h1>VICTORY PAWS HOUSE</h1>
        <p>Jl. Veteran no.11, Banjarmasin | WA: 08111511050</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">No. Booking</td>
            <td>: #{{ $booking->id_booking }}</td>
            <td class="label">Tanggal</td>
            {{-- Menggunakan format tanggal Indonesia --}}
            <td>: {{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Customer</td>
            <td>: {{ $booking->nama }} ({{ $booking->nomor_hp }})</td>
            <td class="label">Hewan</td>
            <td>: {{ $booking->nama_hewan }} ({{ $booking->jenis_hewan }})</td>
        </tr>
        <tr>
            <td class="label">Metode Bayar</td>
            <td>: <span style="text-transform:uppercase;">{{ $booking->metode_pembayaran }}</span></td>
            <td class="label">Status</td>
            <td>: <span style="text-transform:uppercase; font-weight:bold;">{{ $booking->status }}</span></td>
        </tr>
    </table>

    <h3>Rincian Layanan</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 65%;">Layanan</th>
                <th style="width: 30%; text-align: right;">Harga Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->details as $index => $detail)
                @php
                    $isHotel = false;
                    $subtotal = $detail->harga_saat_ini;
                    $keteranganTambahan = '';

                    if (stripos($detail->layanan->nama_layanan, 'hotel') !== false) {
                        $isHotel = true;
                        $checkin = \Carbon\Carbon::parse($booking->jadwal);
                        $checkout = \Carbon\Carbon::parse($booking->tanggal_checkout);
                        
                        $durasi = $checkin->diffInDays($checkout);
                        $durasi = $durasi < 1 ? 1 : $durasi;

                        $subtotal = $detail->harga_saat_ini * $durasi;

                        $keteranganTambahan = " (" . $durasi . " Malam x Rp " . number_format($detail->harga_saat_ini, 0, ',', '.') . ")";
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $detail->layanan->nama_layanan }}
                        <span style="font-weight: bold; color: #6b4423;">{{ $keteranganTambahan }}</span>
                    </td>
                    <td class="price">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        Total Tagihan: <span class="total-label">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
    </div>

    <div class="footer">
        <p>Terima kasih telah mempercayakan hewan kesayangan Anda kepada kami.</p>
        <p>Dokumen ini sah dan diproses oleh komputer pada {{ now()->translatedFormat('d F Y H:i') }} WITA</p>
    </div>

</body>
</html>