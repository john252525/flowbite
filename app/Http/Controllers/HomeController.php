<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('home.index', compact('user'));
    }

    public function setLocale(string $locale)
    {
        if (!in_array($locale, ['en', 'ru'])) {
            return redirect('/'); 
        }

        Cookie::queue(Cookie::forget('lang'));
        Cookie::queue(Cookie::make('lang', $locale, 30 * 86400, '/', $_SERVER['HTTP_HOST'], false, true));
    
        return redirect('/'); 
    }
}
