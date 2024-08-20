<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeleteController extends Controller
{
    public function account(Request $request)
    {
        $user = $request->user();

        $response = Http::post('https://cloud.controller.touch-api.com/api/deleteAccount', [
            'token' => $user->token,
            'login' => $request->login,
            'source' => $request->platform
        ]);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => 'Операция успешно выполнена'
            ];

        return [
            'status' => 'error',
            'message' => 'Операция не выполнена'
        ];
    }
}
