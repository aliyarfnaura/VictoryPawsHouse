@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden md:grid md:grid-cols-4">
            
            <!-- KOLOM KIRI: SIDEBAR NAVIGASI -->
            <div class="md:col-span-1 p-8 bg-[#fcf8f0] border-r border-gray-100 flex flex-col items-center">
                
                {{-- User Info --}}
                <div class="text-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mt-3">{{ $user->username }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <p class="text-xs text-gray-400">Status: {{ $user->role }}</p>
                </div>
                
                {{-- Navigasi Tab --}}
                <nav class="w-full space-y-2">
                    @php
                        $navTabs = ['profile' => 'Profile', 'riwayat' => 'Riwayat', 'ulasan' => 'Ulasan'];
                    @endphp

                    @foreach ($navTabs as $key => $label)
                        <a href="{{ route('profile.index', ['tab' => $key]) }}"
                           class="flex items-center space-x-3 p-3 rounded-xl transition duration-150 
                           @if ($tab === $key) bg-[#f5e8d0] text-[#6b4423] font-bold shadow-inner @else bg-white hover:bg-gray-50 text-gray-700 @endif">
                            <span class="text-lg">
                                {{ $key === 'profile' ? '👤' : ($key === 'riwayat' ? '📦' : '⭐') }}
                            </span>
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach
                </nav>
                
                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="w-full mt-6">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-semibold">
                        Log Out
                    </button>
                </form>
            </div>
            
            <!-- KOLOM KANAN: KONTEN DINAMIS -->
            <div class="md:col-span-3 p-8 md:p-12 bg-white">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                {{-- Menginclude Konten Sesuai Tab --}}
                {{-- Memanggil file dari resources/views/customer/profile/partials/<tab>.blade.php --}}
                @include('customer.profile.partials.' . $tab) 
            </div>
            
        </div>
    </div>
@endsection