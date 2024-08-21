<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

class ActionsController extends Controller
{
    private string $pathApi = 'https://cloud.controller.touch-api.com/api/';

    public function screenshot(Request $request)
    {
        $user = $request->user();

        $response = Http::get($this->__api('screenshot').'?token='.$user->token.'&login='.$request->login.'&source='.$request->platform);

        if(isset($response['status']) && $response['status'] == 'error')
            return [
                'status' => 'error',
                'message' => __('messages.account.errors.action')
            ];

        return [
            'status' => 'success',
            'message' => __('messages.account.success.action')
        ];
    }

    public function showScreenshot(Request $request)
    {
        $user = $request->user();

        $response = Http::get($this->__api('screenshot').'?token='.$user->token.'&login='.$request->login.'&source='.$request->platform);

        return $response;
    }

    public function setState(Request $request)
    {
        $response = $this->__post('setState', [
            'setState' => true
        ], $request);
        
        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('messages.account.success.action')
            ];

        return [
            'status' => 'error',
            'message' => __('messages.account.errors.action')
        ];
    }

    public function forceStop(Request $request)
    {
        $response = $this->__post('forceStop', [], $request);
        
        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('messages.account.success.action')
            ];

        return [
            'status' => 'error',
            'message' => __('messages.account.errors.action')
        ];
    }

    public function getNewProxy(Request $request)
    {
        $response = $this->__post('getNewProxy', [], $request);
        
        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('messages.account.success.action')
            ];

        return [
            'status' => 'error',
            'message' => __('messages.account.errors.action')
        ];
    }

    public function clearSession(Request $request)
    {
        $response = $this->__post('clearSession', [], $request);

        return $response;
    }

    public function Reset(Request $request)
    {
        $forceStop = $this->forceStop($request);
        $clearSession = $this->clearSession($request);
        $getNewProxy = $this->getNewProxy($request);
        
        return [
            'status' => 'success',
            'message' => __('messages.account.success.action')
        ];
    }

    public function disablePhoneAuth(Request $request)
    {
        $response = $this->__post('disablePhoneAuth', [], $request);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('messages.account.success.action')
            ];

        return [
            'status' => 'error',
            'message' => __('messages.account.errors.action')
        ];
    }

    public function enablePhoneAuth(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'phone' => 'phone',
        ]);

        if($validation->fails())
            return [
                'status' => 'error',
                'message' => $validation->errors()->first()
            ];

        $phone = substr($request->phone, 1);

        $response = $this->__post('enablePhoneAuth', [
            'phone' => $phone
        ], $request);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('messages.account.success.action')
            ];

        return [
            'status' => 'error',
            'message' => __('messages.account.errors.action')
        ];
    }

    public function getAuthCode(Request $request)
    {
        $response = $this->__post('getAuthCode', [], $request);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('messages.account.success.action'),
                'code' => $response['authCode'] ?? ''
            ];

        return [
            'status' => 'error',
            'message' => __('messages.account.errors.action')
        ];
    }

    public function Settings(Request $request)
    {
        $response = $this->__post('getInfo', [], $request);
       
        if(isset($response['webhookUrls']))
            return [
                'status' => 'success',
                'webhooks' => $response['webhookUrls']
            ];
    
        return [
            'status' => 'error',
            'message' => __('messages.account.errors.action')
        ];
    }

    public function updateSettings(Request $request)
    {
        $webhooks = explode('\n', $request->webhooks);

        $response = $this->__post('updateAccount', [
            'webhookUrls' => $webhooks
        ], $request);
       
        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('messages.account.success.action')
            ];
    
        return [
            'status' => 'error',
            'message' => __('messages.account.errors.action')
        ];
    }

    private function __post(string $method, array $data = [], Request $request)
    {
        $user = $request->user();

        $response = Http::retry(3, 100)->post($this->__api($method), [
            'token' => $user->token,
            'login' => $request->login,
            'source' => $request->platform,
            ...$data
        ]);

        return $response;
    }
    
    private function __api(string $method)
    {
        return $this->pathApi.$method;
    }
}
