@extends('layouts.app')

@section('content')
<div class="text-left">
    <h1>本日のイベント</h1>
</div>
@if (count($events) > 0)
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
@endsection