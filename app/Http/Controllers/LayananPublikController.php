<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Produk;
use App\Models\Event;
use Illuminate\Http\Request;

class LayananPublikController extends Controller
{
    public function index()
    {
        $layanans = Layanan::all();
        $grooming = $layanans->where('jenis', 'Grooming')->first();
        $hotel = $layanans->where('jenis', 'Pet Hotel')->first();
        $home_service = $layanans->where('jenis', 'Home Service')->first();
        $produk_display = Produk::latest()->limit(1)->first(); 
        $event_display = Event::where('tanggal', '>=', now())
                            ->orderBy('tanggal', 'asc')
                            ->limit(1)
                            ->first();

        return view('layanan_publik.index', compact(
            'grooming', 
            'hotel', 
            'home_service', 
            'produk_display', 
            'event_display'
        ));
    }
}