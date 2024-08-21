<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AddController extends Controller
{
    public function account(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'platform' => 'required|in:telegram,whatsapp',
            'login' => 'required|max:30'
        ], __('messages.account.validation.add'));

        if($validator->fails())
            return [
                'status' => 'error',
                'message' => $validator->errors()->first()
            ];

        $response = Http::post('https://cloud.controller.touch-api.com/api/addAccount', [
            'token' => $user->token,
            'login' => $request->login,
            'source' => $request->platform
        ]);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'redirect' => redirect(route('account.home'))
            ];

        return [
            'status' => 'error',
            'message' => __('messages.account.errors.action')
        ];
    }
}
