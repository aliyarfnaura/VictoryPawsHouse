@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
        <span class="text-sm text-gray-500">Update Terakhir: {{ now()->format('d M Y') }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm font-medium">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm font-medium">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm font-medium">Perlu Diproses</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPending }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm font-medium">Dibatalkan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalCanceled }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-lg lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Grafik Pendapatan (Tahun Ini)</h3>
            <canvas id="revenueChart" height="150"></canvas>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Status Pesanan</h3>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Transaksi Terbaru</h3>
            <a href="{{ route('admin.booking.index') }}" class="text-sm text-[#6b4423] hover:underline font-medium">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-semibold">ID Booking</th>
                        <th class="px-6 py-3 font-semibold">Customer</th>
                        <th class="px-6 py-3 font-semibold">Total</th>
                        <th class="px-6 py-3 font-semibold">Tanggal</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($latestTransactions as $book)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900">#{{ $book->id_booking }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $book->nama }}</td>
                        <td class="px-6 py-4 font-bold text-[#6b4423]">Rp {{ number_format($book->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $book->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $color = match(strtolower($book->status)) {
                                    'dibayar' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'ditolak' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $color }}">
                                {{ ucfirst($book->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- 1. Grafik Pendapatan (Line Chart) ---
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    // Gunakan JSON.parse agar VS Code tidak error merah
    const revenueData = JSON.parse('{!! json_encode($monthlyEarnings) !!}');
    
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueData, 
                borderColor: '#6b4423',
                backgroundColor: 'rgba(107, 68, 35, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // --- 2. Grafik Status (Doughnut Chart) ---
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    // Gunakan JSON.parse juga di sini
    const statusData = JSON.parse('{!! json_encode($pieData) !!}');

    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            // Pastikan Label ini urutannya SAMA dengan Controller
            labels: ['Pending', 'Sukses (Dibayar)', 'Ditolak'], 
            datasets: [{
                data: statusData, 
                backgroundColor: [
                    '#f59e0b', // Index 0: Pending (Kuning)
                    '#10b981', // Index 1: Dibayar (Hijau)
                    '#ef4444'  // Index 2: Ditolak (Merah)
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endsection