@extends('layouts.app')

@section('content')
<div class="text-left">
    <h1>イベント一覧</h1>
</div>
@if (count($events) > 0)
<form action="{{ route('events.index') }}" method="GET" class="mb-4">
    @csrf
    @method('PUT')

    <!-- イベント名-->
    <div class="mt-4 form-group">
        <x-input-label for="title" :value="__('イベント名で検索する')" />
        <x-text-input id="title" class="form-control" type="text" name="title"  autofocus autocomplete="title" placeholder="イベント名" />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>
    <!-- 詳細検索 -->
    <div class="mt-4 form-group">
        <x-input-label for="description" :value="__('詳細検索')" />
        <x-text-input id="description" class="form-control" type="text" name="description"  autofocus autocomplete="description" placeholder="詳細" />
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="mt-4 form-group">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">検索</button>
        </div>
    </div>
</form>
{{ $events->links('pagination::bootstrap-4') }}
<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th scope="col">タイトル</th>
            <th scope="col">開始日時</th>
            <th scope="col">場所</th>
            <th scope="col">対象グループ</th>
            <th scope="col">詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($events as $event)
        <tr>
            <th scope="row">{{ $event->title }} @if(in_array($event->id, $userAttendance))
                @include('attend_event.attend_tag')
                @endif
                <p class="view-index-count">閲覧数: {{ $event->views->views_count ?? 0 }}</p>
            </th>
            <td>{{ \Carbon\Carbon::parse($event->start_date_and_time)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($event->start_date_and_time)->locale('ja')->isoFormat('ddd') }}) {{ \Carbon\Carbon::parse($event->start_date_and_time)->format('H時i分') }}</td>
            <td>{{ $event->location }}</td>
            <td>
                {{ config('groups.types.' .$groups->firstWhere('id', $event->group_id)->name ) }}
            </td>
            <td>
                <a href="{{ route('events.show', ['event' => $event->id]) }}" class="btn btn-outline-dark">詳細</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>本日の開始のイベントはありません</p>
@endif
<div class="flex items-center justify-end mt-4">
    <a class="btn btn-primary btn-block" href="{{ route('events.create') }}">イベントの登録</a>
</div>
@endsection