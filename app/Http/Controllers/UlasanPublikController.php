<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UlasanPublikController extends Controller
{
    public function index()
    {
        $reviews = Ulasan::orderBy('created_at', 'desc') 
                        ->with('pengguna') 
                        ->get();

        $summary = Ulasan::select(
            DB::raw('COUNT(id_ulasan) as total_reviews'),
            DB::raw('AVG(rating) as average_rating')
        )->first();

        $averageRating = number_format($summary->average_rating ?? 0, 1);
        $totalReviews = $summary->total_reviews ?? 0;

        return view('review.index', compact('reviews', 'averageRating', 'totalReviews'));
    }
}