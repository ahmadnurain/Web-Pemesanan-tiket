<?php

namespace App\Http\Controllers;

use App\Models\Destinations;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        // Ambil semua data destinasi beserta foto terkait
        $destinations = Destinations::with('photos')->get();
        // dd($destinations);
        // Kirim data ke view
        return view('welcome', compact('destinations'));
    }
}
