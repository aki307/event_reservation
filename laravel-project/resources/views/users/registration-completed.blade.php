@extends('layouts.app')

@section('content')

<div class="text-left">
    <h1>ユーザ登録</h1>
    <p>ユーザ登録完了しました</p>
    <a href="{{ route('users.index')}}">ユーザ登録一覧に戻る</a>
</div>

@endsection