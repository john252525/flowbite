<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Account\ActionsController;
use App\Http\Controllers\Account\AddController;
use App\Http\Controllers\Account\DeleteController;
use App\Http\Controllers\Account\ListController;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    // Главная страница
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('accounts.index', compact('user'));
    }

    // Действия с аккаунтом
    public function action(Request $request)
    {
        if(!$request->platform || !in_array($request->platform, ['telegram', 'whatsapp']))
            return [
                'status' => 'error',
                'message' => __('account.errors.required_platform')
            ];

        $actions = new ActionsController();

        switch($request->type)
        {
            // Добавление нового аккаунта
            case 'add':
                $add = new AddController();
                return $add->account($request);

            // Вывод списка аккаунтов
            case 'list':
                $list = new ListController();
                return $list->accounts($request);

            // Удаление аккаунта
            case 'delete':
                $delete = new DeleteController();
                $forceStop = $actions->forceStop($request);

                if($forceStop['status'] == 'error')
                    return $forceStop;

                return $delete->account($request);

            // Настройки, Скриншот, Вывод скриншот, Включение, Выключение, Смена прокси, Сброс, Связать через QR, Связать через код, Проверить код, Обновление WebhookURLs
            case 'settings': case 'screenshot': case 'showScreenshot': case 'setState': case 'forceStop': case 'getNewProxy': case 'Reset': case 'disablePhoneAuth': case 'enablePhoneAuth': case 'getAuthCode': case 'updateSettings':
                return $actions->{$request->type}($request);

            default:
                return [
                    'status' => 'error',
                    'message' => __('account.errors.wrong_type')
                ];
        }
    }
}