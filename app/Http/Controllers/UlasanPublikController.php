<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UlasanPublikController extends Controller
{
    public function index()
    {
        // 1. Ambil semua ulasan, diurutkan dari terbaru menggunakan created_at
        $reviews = Ulasan::orderBy('created_at', 'desc') 
                        ->with('pengguna') 
                        ->get();

        // 2. Hitung Summary Rating (Rata-rata dan Jumlah)
        $summary = Ulasan::select(
            DB::raw('COUNT(id_ulasan) as total_reviews'),
            DB::raw('AVG(rating) as average_rating')
        )->first();

        $averageRating = number_format($summary->average_rating ?? 0, 1);
        $totalReviews = $summary->total_reviews ?? 0;

        return view('review.index', compact('reviews', 'averageRating', 'totalReviews'));
    }
}