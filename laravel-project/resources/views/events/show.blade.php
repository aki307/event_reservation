@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">イベント詳細</h1>
    <table class="table">
        <tbody>
            <tr>
                <th scope="row">タイトル</th>
                <td>{{ $event->title }} @include('attend_event.attend_tag', ['event' => $event])</td>
            </tr>
            <tr>
                <th scope="row">開始日時</th>
                <td>{{ \Carbon\Carbon::parse($event->start_date_and_time)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($event->start_date_and_time)->locale('ja')->isoFormat('ddd') }}) {{ \Carbon\Carbon::parse($event->start_date_and_time)->format('H時i分') }}</td>
            </tr>
            <tr>
                <th scope="row">終了日時</th>
                <td>{{ \Carbon\Carbon::parse($event->end_date_and_time)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($event->end_date_and_time)->locale('ja')->isoFormat('ddd') }}) {{ \Carbon\Carbon::parse($event->end_date_and_time)->format('H時i分') }}</td>
            </tr>
            <tr>
                <th scope="row">場所</th>
                <td>{{ $event->location }}</td>
            </tr>
            <tr>
                <th scope="row">対象グループ</th>
                <td>{{ config('groups.types.' .$groups->firstWhere('id', $event->group_id)->name ) }}</td>
            </tr>
            <tr>
                <th scope="row">登録者</th>
                <td>{{ $event->user->user_name }}</td>
            </tr>
            <tr>
                <th scope="row">参加者</th>
                <td>@if($attendees->isNotEmpty())
                    {{ $attendees->pluck('user_name')->join(', ') }}
                    @else
                    参加者はいません。
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('events.index')}}" class="btn btn-primary mr-2">一覧に戻る</a>
        @include('attend_event.attend_button', ['event' => $event])
        <!-- 登録者本人の場合の表示 -->
        @if(Auth::check() && Auth::id() == $event->user_id)
        <a href="{{ route('events.edit', ['event' => $event->id]) }}" class="btn btn-outline-dark">編集</a>
        <a id="deleteUserButton" href="#" class="btn btn-danger">削除</a>

        <form id="delete-form" action="{{ route('events.destroy', ['event' => $event->id]) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        @endif
    </div>
</div>
@endsection