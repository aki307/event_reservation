@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">ユーザ詳細</h1>
    <table class="table">
        <tbody>
            <tr>
                <th scope="row">ID</th>
                <td>{{ $user->id }}</td>
            </tr>
            <tr>
                <th scope="row">氏名</th>
                <td>{{ $user->user_name }}</td>
            </tr>
            <tr>
                <th scope="row">ログインID</th>
                <td>{{ $user->login_id }}</td>
            </tr>
            <tr>
                <th scope="row">所属グループ</th>
                <td>{{ config('groups.types.' .$groups->firstWhere('id', $user->group_id)->name ) }}</td>
            </tr>
        </tbody>
    </table>
    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('users.index')}}" class="btn btn-primary">一覧に戻る</a>
        <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-outline-dark">編集</a>
        <a href="#" onclick="event.preventDefault(); if(confirm('本当に削除してよろしいですか？')) document.getElementById('delete-form').submit();" class="btn btn-danger">削除</a>

        <form id="delete-form" action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection