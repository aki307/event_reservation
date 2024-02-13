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

    public function getAllUsers($request)
    {

        $users = User::query();
        $searchTerm = $request->query('search');
        if (!empty($searchTerm)) {
            $users = $users->where('user_name', 'LIKE', '%' . $searchTerm . '%');
        }

        $ageSort = $request->age_sort;
        if ($ageSort === 'asc') {
            $users = $users->orderBy('dob', 'asc');
        } elseif ($ageSort === 'desc') {
            $users = $users->orderBy('dob', 'desc');
        }

        $genderSort = $request->gender;
        if (!empty($genderSort)) {
            $users = $users->where('gender', $genderSort);
        }

        $groupSort = $request->group_ids;
        if (!empty($groupSort) && is_array($groupSort)) {
            $users = $users->where('group_id', $groupSort);
        }

        if (empty($searchTerm) && empty($ageSort) && empty($genderSort) && empty($groupSort)) {
            $users = $users->orderBy('id');
        }
        $users = $users->paginate(5);
        return $users;
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
