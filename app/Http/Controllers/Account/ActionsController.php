<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ActionsController extends Controller
{
    private string $pathApi = 'https://cloud.controller.touch-api.com/api/';

    public function screenshot(Request $request)
    {
        $user = $request->user();

        $response = Http::get($this->__api('screenshot').'?token='.$user->token.'&login='.$request->login.'&source='.$request->platform);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'image' => $response
            ];
            
        return [
            'status' => 'error',
            'message' => __('Не удалось выполнить операцию')
        ];
    }

    public function setState(Request $request)
    {
        $response = $this->__post('setState', [
            'setState' => true
        ], $request);
        
        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('Операция успешно выполнена')
            ];

        return [
            'status' => 'error',
            'message' => __('Не удалось выполнить операцию')
        ];
    }

    public function forceStop(Request $request)
    {
        $response = $this->__post('forceStop', [], $request);
        
        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('Операция успешно выполнена')
            ];

        return [
            'status' => 'error',
            'message' => __('Не удалось выполнить операцию')
        ];
    }

    public function getNewProxy(Request $request)
    {
        $response = $this->__post('getNewProxy', [], $request);
        
        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('Операция успешно выполнена')
            ];

        return [
            'status' => 'error',
            'message' => __('Не удалось выполнить операцию')
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
            'message' => __('Операция успешно выполнена')
        ];
    }

    public function disablePhoneAuth(Request $request)
    {
        $response = $this->__post('disablePhoneAuth', [], $request);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('Операция успешно выполнена')
            ];

        return [
            'status' => 'error',
            'message' => __('Не удалось выполнить операцию')
        ];
    }

    public function enablePhoneAuth(Request $request)
    {
        $response = $this->__post('enablePhoneAuth', [], $request);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('Операция успешно выполнена')
            ];

        return [
            'status' => 'error',
            'message' => __('Не удалось выполнить операцию')
        ];
    }

    public function getAuthCode(Request $request)
    {
        $response = $this->__post('getAuthCode', [], $request);

        if(isset($response['status']) && $response['status'] == 'ok')
            return [
                'status' => 'success',
                'message' => __('Операция успешно выполнена')
            ];

        return [
            'status' => 'error',
            'message' => __('Не удалось выполнить операцию')
        ];
    }

    private function __post($method, array $data, Request $request)
    {
        $user = $request->user();

        $response = Http::post($this->__api($method), [
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
