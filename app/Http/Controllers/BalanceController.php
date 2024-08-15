<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BalanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $payments = Payment::where('user_id', $user->id)->paginate(10);
        return view('balance.index', compact('payments'));
    }
}
