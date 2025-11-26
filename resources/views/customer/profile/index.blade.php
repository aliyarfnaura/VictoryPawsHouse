@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden md:grid md:grid-cols-4">
            
            <div class="md:col-span-1 p-8 bg-[#fcf8f0] border-r border-gray-100 flex flex-col items-center">
                
                {{-- User Info --}}
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-[#6b4423] rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $user->username }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                    <span class="inline-block mt-2 px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                        {{ $user->role }}
                    </span>
                </div>
                
                {{-- Navigasi Tab --}}
                <nav class="w-full space-y-3">
                    @php
                        $navTabs = [
                            'profile' => [
                                'label' => 'Profile',
                                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'
                            ],
                            'riwayat' => [
                                'label' => 'Riwayat',
                                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'
                            ],
                            'ulasan' => [
                                'label' => 'Ulasan',
                                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>'
                            ]
                        ];
                    @endphp

                    @foreach ($navTabs as $key => $tabInfo)
                        <a href="{{ route('profile.index', ['tab' => $key]) }}"
                           class="flex items-center space-x-3 p-3 rounded-xl transition duration-150 
                           @if ($tab === $key) bg-[#f5e8d0] text-[#6b4423] font-semibold shadow-sm border border-[#e8d9bc] @else bg-white hover:bg-gray-50 text-gray-700 border border-transparent @endif">
                            <span class="text-[#6b4423]">
                                {!! $tabInfo['icon'] !!}
                            </span>
                            <span class="font-medium">{{ $tabInfo['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                
                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="w-full mt-auto pt-6">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-medium transition duration-150 border border-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
            
            <!-- KOLOM KANAN: KONTEN DINAMIS -->
            <div class="md:col-span-3 p-8 md:p-12 bg-white">
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                
                {{-- Menginclude Konten Sesuai Tab --}}
                @include('customer.profile.partials.' . $tab) 
            </div>
            
        </div>
    </div>
@endsection