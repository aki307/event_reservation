@extends('layouts.app')

@section('content')

<div class="text-left">
    <h1>イベント登録</h1>
    <p>イベント登録が完了しました</p>
    <a href="{{ route('events.index
        ')}}">イベント一覧に戻る</a>
</div>

@endsection