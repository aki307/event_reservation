@extends('layouts.app')

@section('content')

<div class="text-left">
    <h1>ユーザ削除</h1>
    <p>ユーザの削除が完了しました</p>
    <a href="{{ route('users.index')}}">ユーザ一覧に戻る</a>
</div>

@endsection