<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Booking</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #6b4423; }
        .header p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #6b4423; color: white; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { padding: 2px 5px; border-radius: 3px; color: white; font-weight: bold; text-transform: uppercase; font-size: 8px; }
        .bg-green { background-color: green; }
        .bg-red { background-color: red; }
        .bg-yellow { background-color: #eab308; color: black; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN TRANSAKSI VICTORY PAWS HOUSE</h1>
        <p>Jl. Veteran no.11, Banjarmasin | WA: 08111511050</p>
        <p>Tanggal Cetak: {{ now()->translatedFormat('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">Customer</th>
                <th style="width: 30%">Layanan</th>
                <th style="width: 15%">Status</th>
                <th style="width: 15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $index => $booking)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $booking->nama }}</strong><br>
                    <span style="font-size: 8px; color: #555;">{{ $booking->nomor_hp }}</span>
                </td>
                <td>
                    <ul style="margin: 0; padding-left: 15px;">
                        @foreach($booking->details as $detail)
                            @php
                                $namaLayanan = $detail->layanan->nama_layanan;
                                
                                if (stripos($namaLayanan, 'hotel') !== false) {
                                    $checkin = \Carbon\Carbon::parse($booking->jadwal);
                                    $checkout = \Carbon\Carbon::parse($booking->tanggal_checkout);
                                    
                                    $durasi = $checkin->diffInDays($checkout);
                                    $durasi = $durasi < 1 ? 1 : $durasi;
                                    $namaLayanan .= " (" . $durasi . " Malam)";
                                }
                            @endphp
                            <li>{{ $namaLayanan }}</li>
                        @endforeach
                    </ul>
                </td>
                <td class="text-center">
                    @php
                        $color = 'bg-yellow';
                        if($booking->status == 'dibayar') $color = 'bg-green';
                        if($booking->status == 'ditolak') $color = 'bg-red';
                    @endphp
                    <span class="badge {{ $color }}">{{ $booking->status }}</span>
                </td>
                <td class="text-right">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right; font-size: 11px;">
        <p>Banjarmasin, {{ now()->translatedFormat('d F Y') }}</p>
        <p style="margin-top: 50px;"><strong>( Admin Victory PawsHouse )</strong></p>
    </div>

</body>
</html>