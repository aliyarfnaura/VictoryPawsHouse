<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $booking->id_booking }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #6b4423; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items-table th { background-color: #f8f8f8; }
        .total { text-align: right; font-size: 18px; font-weight: bold; margin-top: 20px; color: #6b4423; }
        .status { font-weight: bold; text-transform: uppercase; }
        .status.selesai { color: green; }
        .status.pending { color: orange; }
        .status.ditolak { color: red; }
    </style>
</head>
<body>

    <div class="header">
        <h1>VICTORY PAWS HOUSE</h1>
        <p>Jl. Veteran no.11, Banjarmasin | WA: 08111511050</p>
        <hr>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>No. Booking</strong></td>
            <td width="35%">: #{{ $booking->id_booking }}</td>
            <td width="15%"><strong>Tanggal</strong></td>
            <td width="35%">: {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>Customer</strong></td>
            <td>: {{ $booking->nama }} ({{ $booking->nomor_hp }})</td>
            <td><strong>Hewan</strong></td>
            <td>: {{ $booking->nama_hewan }} ({{ $booking->jenis_hewan }})</td>
        </tr>
        <tr>
            <td><strong>Metode Bayar</strong></td>
            <td>: {{ $booking->metode_pembayaran }}</td>
            <td><strong>Status</strong></td>
            <td class="status {{ strtolower($booking->status) }}">: {{ $booking->status }}</td>
        </tr>
    </table>

    <h3 style="margin-bottom: 5px;">Rincian Layanan</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Layanan</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->details as $index => $detail)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    {{ $detail->layanan->nama_layanan ?? 'Layanan dihapus' }}
                </td>
                <td style="text-align: right;">Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Tagihan: Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #777;">
        <p>Terima kasih telah mempercayakan hewan kesayangan Anda kepada kami.</p>
    </div>

</body>
</html>