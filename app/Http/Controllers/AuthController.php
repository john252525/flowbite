<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function signup(): View
    {
        return view('auth.signup');
    }

    public function forgot(): View
    {
        return view('auth.forgot');
    }

    public function reset(): View
    {
        return view('auth.reset');
    }

    public function action(string $type, Request $request)
    {
        switch($type)
        {
            case 'login':
                return $this->__login($request);

            case 'signup':
                return $this->__signup($request);

            case 'forgot':
                $request->validate(['email' => 'required|email']);
    
                $status = Password::sendResetLink(
                    $request->only('email')
                );
            
                if($status === Password::RESET_LINK_SENT)
                    return back()->with([
                        'success' => __($status)
                    ]);
        
                return back()->with([
                    'error' => __($status)
                ]);

            case 'reset':
                return $this->__reset();
        }
    }

    protected function __login(Request $request): RedirectResponse
    {
        $data = $request->all();
        $validator = $this->validator('login', $data);

        if($validator->fails())
            return back()->with([
                'errors' => $validator->errors()
            ]);

        $user = User::query()->where('email', $data['email'])->first();
        
        if(!$user)
            return back()->withErrors(__('Неправильный логин или пароль'));
        
            // ([
            //     'error' => __('Неправильный логин или пароль')
            // ]);

        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']]))
            return redirect()->route('home');

        return back()->with([
            'error' => __('Эти учетные данные не соответствуют нашим записям')
        ]);
    } 

    protected function __signup(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator('signup', $data);

        if($validator->fails())
            return back()->with([
                'errors' => $validator->errors()
            ]);

        $user = User::create($data);

        event(new Registered($user));

        $response = Http::get('https://api25.apitter.com/touchapi.php?key=d7ab72bcac837d010f443a271202e788&user_id='.$user->id);

        if(isset($response['value'])) {
            $user->token = $response['value'];
            $user->save();
        }

        if(!$user)
            return back()->with([
                'error' => __('Не удалось зарегистрироваться, пожалуйста, попробуйте ещё раз позже')
            ]);

        Auth::login($user);

        return redirect('/');
    }

    protected function __reset(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator('reset', $data);

        if($validator->fails())
            return [
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ];
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
    
                event(new PasswordReset($user));
            }
        );
    
        if($status === Password::RESET_LINK_SENT)
            return back()->with([
                'error' => __($status)
            ]);

        return redirect()->route('login');
    }

    protected function validator(string $type, array $data)
    {
        $validator = [
            'login' => [
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
                '_token' => 'required',
            ],
            'signup' => [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                '_token' => 'required',
            ],
            'reset' => [
                'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                '_token' => 'required',
            ]
        ];

        return Validator::make($data, $validator[$type], [], [
                'email' => __('почта'),
                'password' => __('пароль')
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}
