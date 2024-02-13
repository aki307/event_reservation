@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>イベント詳細</h1>
    <a href="javascript:void(0);" id="favorite-link" data-event="{{ $event->id }}" style="text-decoration:none;">
        <i class="fa-solid fa-star" style="font-size:22px; color: {{ $event->favoritedByUsers->contains(Auth::id()) ? '#f1bf2a' : '#82888e' }};"></i>
        <span id="favorites-count" style="color: #82888e; text-decoration:none;">{{ $event->favoritesCount() }}</span>
    </a>
    <p class="view-count">閲覧数: {{ $event->views->views_count ?? 0 }}</p>
    <table class="table">
        <tbody>
            <tr>
                <th scope="row">タイトル</th>
                <td>{{ $event->title }} @include('attend_event.attend_tag')</td>
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
<div class="container mt-5">
    <h3>コメント一覧</h3>

    {{-- コメント入力フォーム --}}
    <form action="{{ route('comment.post', ['event' => $event->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="ここにコメントを入力"></textarea>
        </div>
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="btn btn-primary">コメントを投稿</button>
        </div>
    </form>

    @if($comments)
    @if($comments->isNotEmpty())
    <div class="list-group mt-3">
        @foreach ($comments as $comment)
        <div class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">{{ $comment->user->user_name }}さん</h5>
                @if (auth()->user()->id == $comment->user_id)
                <!-- 編集ボタン -->
                <small><a href="/comments/{{ $comment->id }}/edit" class="btn btn-sm btn-outline-secondary">編集</a></small>
                @endif
            </div>
            <p class="mb-1">{{ $comment->comment }}</p>
        </div>
        @endforeach
    </div>
    @else
    <p>コメントはありません。</p>
    @endif
    @endif
</div>

<script>
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.addEventListener('DOMContentLoaded', function() {
        var link = document.getElementById('favorite-link');
        var countSpan = document.getElementById('favorites-count');
        if (link) {
            link.addEventListener('click', function() {
                var eventId = this.getAttribute('data-event');
                axios.post('/favorite/' + eventId)
                    .then(function(response) {
                        var icon = link.querySelector('.fa-star');
                        if (response.data.favorited) {
                            // お気に入りに追加された
                            icon.style.color = '#f1bf2a'; // ゴールド色
                        } else {
                            // お気に入りから解除された
                            icon.style.color = '#82888e'; // グレー色
                        }
                        countSpan.textContent = response.data.favoritesCount;
                    })
                    .catch(function(error) {
                        console.error(error.response.data);
                    });
            });
        }
    });
</script>
@endsection