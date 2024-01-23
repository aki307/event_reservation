<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/logout');
    }
    /**
     * googleアカウントでのログイン用
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        
        try {
            $googleUser = Socialite::driver('google')->user();
            dd($googleUser);
            // ユーザー情報の取得と認証処理
            $user = User::where('email', $googleUser->email)->first();
            

            if (!$user) {
                // ユーザーが存在しない場合は新規作成
                $user = User::create([
                    'user_name' => $googleUser->name,
                    'user_type_id' => 1,
                    'google_account' => true,
                    'email' => $googleUser->email,
                    'google_token' => $googleUser->token,
                ]);
            }
            // ユーザーでログイン
            Auth::login($user, true);

            // セッション再生成
            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);

        } catch (\Exception $e) {
            // エラー処理
            return redirect()->route('login')->withErrors(['msg' => 'Googleログインに失敗しました。']);
        }
    }
}
