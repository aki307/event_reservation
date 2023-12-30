@extends('layouts.app')

@section('content')

<div class="text-left">
    <h1>ログイン</h1>
</div>
<div class="row justify-content-left">
    <div class="col-sm-12">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- ログインID -->
            <div class="mt-4">
                <x-text-input id="login_id" class="form-control" type="text" name="login_id" :value="old('login_id')" required autofocus autocomplete="login_id" placeholder="ログインID" />
                <x-input-error :messages="$errors->get('login_id')" class="mt-2" />
            </div>

            <!-- パスワード -->
            <div class="mt-4 form-group">
                <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="パスワード" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="btn btn-primary btn-block">
                    {{ __('ログイン') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</div>
@endsection