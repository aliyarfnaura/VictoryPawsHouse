<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventPublikController extends Controller
{
    public function index()
    {
        $events = Event::where('tanggal', '>=', now())
                            ->orderBy('tanggal', 'asc')
                            ->get();

        return view('event.index', compact('events'));
    }
}