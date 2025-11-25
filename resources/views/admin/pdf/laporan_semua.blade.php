<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background-color: #e0e0e0; }
    </style>
</head>
<body>

    <h2>LAPORAN TRANSAKSI VICTORY PAWS HOUSE</h2>
    <p style="text-align: center;">Tanggal Cetak: {{ date('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Layanan</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($bookings as $index => $b)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($b->jadwal)->format('d/m/Y') }}</td>
                <td>{{ $b->nama }}</td>
                <td>
                    @foreach($b->details as $d)
                        - {{ $d->layanan->nama_layanan ?? '-' }}<br>
                    @endforeach
                </td>
                <td>{{ ucfirst($b->status) }}</td>
                <td style="text-align: right;">Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>
            </tr>
            @php $grandTotal += $b->total_harga; @endphp
            @endforeach
            
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="5" style="text-align: right;">GRAND TOTAL PENDAPATAN</td>
                <td style="text-align: right;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>