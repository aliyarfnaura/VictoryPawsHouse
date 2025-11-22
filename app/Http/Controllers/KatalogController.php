<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index()
    {
        $products = Produk::orderBy('created_at', 'desc')->get();
        
        return view('katalog.index', compact('products'));
    }

    public function show($id_produk)
    {
        $product = Produk::findOrFail($id_produk);
        return view('katalog.show', compact('product'));
    }
}