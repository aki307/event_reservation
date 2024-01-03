@extends('layouts.app')

@section('content')

<div class="text-left">
    <h1>ユーザ編集</h1>
</div>
<div class="row justify-content-left">
    <div class="col-sm-12">
    <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
            @csrf
            @method('PUT')

            <!-- 氏名(必須) -->
            <div class="mt-4 form-group">
                <x-input-label for="user_name" :value="__('氏名(必須)')" />
                <x-text-input id="user_name" class="form-control" type="text" name="user_name" :value="old('user_name', $user->user_name)" required autofocus autocomplete="user_name" placeholder="氏名" />
                <x-input-error :messages="$errors->get('user_name')" class="mt-2" />
            </div>

            <!-- ログインID(必須) -->
            <div class="mt-4">
                <x-input-label for="login_id" :value="__('ログインID(必須)')" />
                <x-text-input id="login_id" class="form-control" type="text" name="login_id" :value="old('login_id', $user->login_id)" required autofocus autocomplete="login_id" placeholder="ログインID" />
                <x-input-error :messages="$errors->get('login_id')" class="mt-2" />
            </div>

            <!-- パスワード(変更の場合のみ) -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('パスワード(変更の場合のみ)')" />

                <x-text-input id="password" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="パスワード" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- 確認用パスワード(変更の場合のみ) -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('確認用パスワード(変更の場合のみ)')" />

                <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation"  autocomplete="new-password" placeholder="確認用パスワード" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- ユーザー種別選択 -->
            <div class="mt-4">
                <x-input-label for="user_type_id" :value="__('ユーザー種別(必須)')" />


                <select name="user_type_id" id="user_type_id" class="form-control" required>
                    <option value="">{{ __('選択してください') }}</option>
                    @foreach ($userTypes as $type)
                    <option value="{{ $type->id }}" {{ (old('user_type_id', $user->user_type_id) == $type->id) ? 'selected' : '' }}>
                        {{ config('user_types.types.' . $type->name) }}
                    </option>
                    @endforeach

                </select>
                <x-input-error :messages="$errors->get('user_type_id')" class="mt-2" />
            </div>

            <!-- 所属グループ選択 -->
            <div class="mt-4">
                <x-input-label for="groups_id" :value="__('所属グループ(必須)')" />


                <select name="group_id" id="group_id" class="form-control" required>
                    <option value="">{{ __('選択してください') }}</option>
                    @foreach ($groups as $group)
                    <option value="{{ $group->id }}" {{ (old('group_id', $user->group_id) == $group->id) ? 'selected' : '' }}>
                    {{ config('groups.types.' . $group->name) }}
                    </option>
                    @endforeach

                </select>
                <x-input-error :messages="$errors->get('group_id')" class="mt-2" />
            </div>


            <div class="flex items-center justify-end mt-4">
                <a class="btn btn-light btn-block " href="{{ route('users.show', ['user' => $user->id]) }}">キャンセル</a>

                <x-primary-button class="btn btn-primary btn-block">
                    {{ __('登録') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</div>
@endsection