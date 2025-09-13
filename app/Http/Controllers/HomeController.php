<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $appName = 'Crew Inventory';
        $user = Auth::user();


        $features = [
            "Upload clothing items",
            "Organize By categories",
            "Share with your crew",
            "Manage permissions",
            
        ];

        return view('home', compact('appName', 'features', 'user'));
    }
}
