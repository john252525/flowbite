<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('accounts.index', compact('user'));
    }

    public function add(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'platform' => 'required|in:telegram,whatsapp',
            'login' => 'required|max:30'
        ], [
            'platform' => __('Необходимо выбрать платформу'),
            'login' => __('Необходимо указать логин или номер телефона')
        ]);

        $response = Http::post('https://cloud.controller.touch-api.com/api/addAccount', [
            'token' => $user->token,
            'login' => $request->login,
            'source' => $request->platform
        ]);

        if(isset($response['status']) && $response['status'] == 'ok')
            return redirect(route('account.home'));

        return back()->withErrors(__('Не удалось добавить аккаунт'));
    }

    public function list(Request $request, string $type)
    {
        $user = $request->user();

        if(!in_array($type, ['telegram', 'whatsapp']))
            return [];

        $response = Http::post('https://cloud.controller.touch-api.com/api/getInfoByToken', [
            'source' => $type,
            'token' => $user->token
        ]);

        $clients = [];

        foreach($response['clients'] as $client) {
            $clients[] = $client['login'];
        }

        return $clients;
    }

    public function delete(Request $request)
    {
        $user = $request->user();

        if(!$request->platform || !in_array($request->platform, ['telegram', 'whatsapp']))
            return [
                'status' => 'error',
                'message' => 'Необходимо передать платформу'
            ];

        $response = Http::post('https://cloud.controller.touch-api.com/api/deleteAccount', [
            'token' => $user->token,
            'login' => $request->login,
            'source' => $request->platform
        ]);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success'
            ];
        else
            return [
                'status' => 'error',
                'message' => 'Не удалось удалить аккаунт'
            ];
    }
}