<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Group;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(5);
        $groups = Group::get();

        return view('users.index', [
            'users' => $users,
            'groups' => $groups,
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        $groups = Group::get();
        return view('users.show', [
            'user' => $user,
            'groups' => $groups,
        ]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        $groups = Group::get();
        $userTypes = UserType::get();
        return view('users.edit', [
            'user' => $user,
            'groups' => $groups,
            'userTypes' => $userTypes,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 入力内容が変わっていないか確認
        $input = $request->only(['user_name', 'login_id', 'user_type_id', 'group_id']);
        if ($this->matchesAttributes($input, $user)) {
            return redirect()->back()->withErrors(['custom_error' => '入力内容が変わっていません。']);
        }

        $request->validate([
            'login_id' => ['required', 'string','min:6', 'max:22', 'unique:users,login_id,' . $id],
            'user_name' => ['required', 'string','min:6', 'max:40'],
            'user_type_id' => ['required', 'exists:user_types,id'],
            'group_id' => ['required', 'exists:groups,id'],
        ]);


        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $input['password'] = Hash::make($request->password);
        }

        // ユーザー情報の更新
        $user->fill($input)->save();

        // 更新イベントを発火させる用
        // event(new Updated($user));

        return redirect()->route('users.show', ['user' => $user->id]);
    }

    /**
     * 属性が既存のモデルと一致しているかどうかを確認します。
     * @param array $attributes
     * @return bool
     */
    protected function matchesAttributes(array $attributes, User $user): bool
    {
        foreach ($attributes as $key => $value) {
            if ($user->$key !== $value) {
                return false;
            }
        }
        return true;
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return view('users.delete');
    }
}
