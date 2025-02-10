<?php

namespace App\Http\Controllers;

use App\Models\Destinations;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        // Mengambil semua destinasi beserta relasi fotonya
        $destinations = Destinations::with('photos')->get();

        // Mengirim data ke view 'destinations'
        return view('destinations', compact('destinations'));
    }
}