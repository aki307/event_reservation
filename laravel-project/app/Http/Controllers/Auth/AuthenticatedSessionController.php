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
use App\Models\GoogleUser;

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
        session(['is_google_login' => true]);

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
        return Socialite::driver('google')
        ->scopes(['https://www.googleapis.com/auth/calendar', 'https://www.googleapis.com/auth/calendar.events'])
        ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {

        if (Auth::check()) {
            $googleUser = Socialite::driver('google')->user();
            // ユーザーがログインしている場合の処理
            $linkedAccount = GoogleUser::where('user_id', Auth::id())->first();
            if ($linkedAccount) {
                // Google情報の更新処理
                $linkedAccount->update([
                    'google_id' => $googleUser->id,
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                    'refresh_token' => $googleUser->refresh_token,
                ]);
                return redirect(RouteServiceProvider::HOME)->with('success', ['Googleアカウントの認証の更新に成功しました。']);
            } else {
                // Google情報の新規作成処理
                GoogleUser::create([
                    'user_id' => Auth::id(),
                    'google_id' => $googleUser->id,
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                    'refresh_token' => $googleUser->refresh_token,
                ]);
                return redirect(RouteServiceProvider::HOME)->with('success', ['Googleアカウントの認証に成功しました。次回からはGoogleアカウントでログインすることが可能です']);
            }
        } else {
            try {
                $googleUser = Socialite::driver('google')->user();
                $googleUserGet = GoogleUser::where('google_id', $googleUser->id)->first();
                if ($googleUserGet) {
                    // ログイン処理
                    $user = User::where('id', $googleUserGet->user_id)->first();
                    Auth::login($user, true);
                    if ($googlehjUser->token) {
                        $googleUserGet->token = $googleUser->token;
                        $googleUserGet->save();
                    }
                    $request->session()->regenerate();
                    session(['is_google_login' => true]);
                    return redirect()->intended(RouteServiceProvider::HOME);
                } else {
                    return redirect()->route('login')->withErrors(['アプリ側でGoogleアカウントの登録がないため、Googleアカウントでのログインできません。ユーザー名とパスワードを入力してログイン後にメニューバーからGoogleアカウントの認証をうけてください。']);
                }
            } catch (\Exception $e) {
                return redirect()->route('login')->withErrors(['msg' => 'Googleログインに失敗しました。']);
            }
        }
    }
}
