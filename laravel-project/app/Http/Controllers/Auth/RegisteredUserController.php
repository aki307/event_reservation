<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;



class RegisteredUserController extends Controller
{
    
    /**
     * Display the registration view.
     */
    public function create()
    {
        
        $userTypes = DB::table('user_types')->get();
        $groups = DB::table('groups')->get();
        $study = Config::get('category.$language');
        return view('auth.register', ['userTypes' => $userTypes, 'groups' => $groups]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login_id' => ['required', 'string', 'min:6', 'max:22', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_name' => ['required', 'string', 'min:6', 'max:40'],
            'user_type_id' => ['required', 'exists:user_types,id'], // user_typesテーブルのidカラムに存在するかチェック
            'group_id' => ['required', 'exists:groups,id'], // groupsテーブルのidカラムに存在するかチェック

        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'login_id' => $request->login_id,
            'password' => $request->password,
            'password' => Hash::make($request->password),
            'user_type_id' => $request->user_type_id,
            'group_id' => $request->group_id,
        ]);

        event(new Registered($user));
        return redirect('/user/registration-completed');
    }
}
