<?php

namespace App\Services;

use App\Models\User;
use App\Models\Group;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;


class UserService
{

    public function getAllUsers($ageSort)
    {
        
        $query = User::orderBy('id', 'desc');
    
    if ($ageSort === 'asc') {
        $query = User::orderBy('dob', 'asc');
    } elseif ($ageSort === 'desc') {
        $query =User::orderBy('dob', 'desc');
    }
        return $query->paginate(5);
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function getAllGroups()
    {
        return Group::get();
    }

    public function getAllUserTypes()
    {
        return UserType::get();
    }

    public function updateUser($data, $id)
    {
        $user = User::findOrFail($id);
        $isSame = $this->matchesAttributes($data, $user);

        if ($isSame) {
            throw new \Exception('入力内容が変わっていません。');
        }

        // パスワード以外の属性を更新
        $user->fill($data);

        // パスワードが設定されており、空でない場合のみ更新
        if (isset($data['password']) && $data['password']) {
            $user->password = Hash::make($data['password']);
        } else {
            unset($user->password); // パスワードフィールドを更新しない
        }

        $user->save();

        return $user;
    }



    public function deleteUser($id)
    {
        $currentUserId = Auth::id();
        if ($currentUserId == $id) {
            return back()->with('error', '現在使用しているアカウントを削除することはできません。別の管理ユーザーのアカウントでログインしてから削除してください');
        }

        $user = User::findOrFail($id);
        $user->delete();
    }

    protected function matchesAttributes($attributes, $user)
    {
        foreach ($attributes as $key => $value) {
            if ($key !== 'password' && $user->$key !== $value) {
                return false;
            }
        }
        return true;
    }
}
