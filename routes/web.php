<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

// Гостевая группа
Route::group(['middleware' => 'guest'], function () {

    // Авторизация
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::get('/forgot', [AuthController::class, 'forgot'])->name('forgot');
    
    Route::post('/u.{type}', [AuthController::class, 'action'])->name('action');

});

// Авторизованная группа
Route::group(['middleware' => 'auth'], function () {

    // Главная страница
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Аккаунты
    Route::name('account.')->prefix('accounts')->group(function () {

        // Список аккаунтов
        Route::get('/', [AccountController::class, 'index'])->name('home');
        Route::post('/action', [AccountController::class, 'action'])->name('action');

        Route::post('/add', [AccountController::class, 'add'])->name('add');
        Route::post('/delete', [AccountController::class, 'delete'])->name('delete');

    });

    // Пополнение баланса
    Route::get('/balance', [BalanceController::class, 'index'])->name('balance');

    // Платежи
    Route::name('payment.')->prefix('payment')->group(function() {
        Route::post('/create', [PaymentController::class, 'create'])->name('create');
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});

// Группа администратора
Route::group(['middleware' => 'admin'], function () {

    Route::name('admin.')->prefix('admin')->group(function () {
        
        // Управление пользователями
        // Route::resource('/users', UsersController::class);
        
        // Настройки
        Route::get('/settings/add', [SettingsController::class, 'showAdd'])->name('settings.add');
        Route::get('/settings/destroy/{id}', [SettingsController::class, 'destroy'])->where('id', '(\d+)')->name('settings.destroy');
        Route::post('/settings/add', [SettingsController::class, 'add']);
        Route::resource('/settings', SettingsController::class)->only(['index', 'update']);

    });

});

// Уведомления платежной системы
Route::post('notifications/{type}', [PaymentController::class, 'notifications'])->name('notifications');
 
Route::get('/greeting/{locale}', [HomeController::class, 'setLocale']);