@extends('layouts.app')

@section('content')

<div class="text-left">
    <h1>ログアウト</h1>
    <p>ログアウト完了しました</p>
    <a href="{{ route('login') }}">ログイン画面に戻る</a>
</div>

@endsection