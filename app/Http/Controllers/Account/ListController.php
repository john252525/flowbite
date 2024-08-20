<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ListController extends Controller
{   
    public function accounts(Request $request) {
        $user = $request->user();

        if(!in_array($request->platform, ['telegram', 'whatsapp']))
            return [];

        $response = Http::post('https://cloud.controller.touch-api.com/api/getInfoByToken', [
            'source' => $request->platform,
            'token' => $user->token
        ]);

        $clients = [];

        foreach($response['clients'] as $client) {
            $clients[$client['login']] = [
                'state' => $client['state'] ? true : false,
            ];
        }

        return $clients;
    }
}