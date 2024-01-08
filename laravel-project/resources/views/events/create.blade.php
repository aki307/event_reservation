@extends('layouts.app')

@section('content')

<div class="text-left">
    <h1>イベント登録</h1>
</div>
<div class="row justify-content-left">
    <div class="col-sm-12">
    <form method="POST" action="{{ route('events.store') }}">
    @csrf
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            <!-- タイトル(必須) -->
            <div class="mt-4 form-group">
                <x-input-label for="title" :value="__('タイトル(必須)')" />
                <x-text-input id="title" class="form-control" type="text" name="title" :value="old('title')" required autofocus autocomplete="title" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>
            <!-- 開始日時(必須) -->
            <div class="mt-4">
                <x-input-label for="start_date_and_time" :value="__('開始日時(必須)')" />
                <x-text-input id="start_date_and_time" class="form-control" type="text" name="start_date_and_time" :value="old('start_date_and_time')" required autofocus autocomplete="start_date_and_time" placeholder="0000-00-00 00:00:00" />
                <x-input-error :messages="$errors->get('start_date_and_time')" class="mt-2" />
            </div>

            <!-- 終了日時 -->
            <div class="mt-4">
                <x-input-label for="end_date_and_time" :value="__('終了日時')" />
                <x-text-input id="end_date_and_time" class="form-control" type="end_date_and_time" name="end_date_and_time"  autocomplete="new-end_date_and_time" placeholder="0000-00-00 00:00:00" />
                <x-input-error :messages="$errors->get('end_date_and_time')" class="mt-2" />
            </div>
            <!-- 場所(必須) -->
            <div class="mt-4">
                <x-input-label for="location" :value="__('場所(必須)')" />
                <x-text-input id="location" class="form-control" type="location" name="location" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('location')" class="mt-2" />
            </div>
            <!-- 対象グループ選択 -->
            <div class="mt-4">
                <x-input-label for="groups_id" :value="__('対象グループ(必須)')" />
                <select name="group_id" id="group_id" class="form-control" required>
                    <option value="">{{ __('選択してください') }}</option>
                    @foreach ($groups as $type)
                    <option value="{{ $type->id }}">{{ config('groups.types.' . $type->name) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('group_id')" class="mt-2" />
            </div>
            <!-- 詳細 -->
            <div class="mt-4">
                <x-input-label for="description" :value="__('詳細')" />
                <textarea id="description" class="form-control" name="description" rows="8" placeholder="{{ __('詳細を入力してください') }}">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="btn btn-light btn-block " href="{{ route('users.index')}}">キャンセル</a>

                <x-primary-button class="btn btn-primary btn-block">
                    {{ __('登録') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection