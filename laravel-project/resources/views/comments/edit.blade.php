@extends('layouts.app')

@section('content')

<div class="text-left">
    <h1>コメント編集</h1>
</div>
<div class="row justify-content-left">
    <div class="col-sm-12">
    <form method="POST" action="{{ route('comment.update', ['comment' => $comment->id]) }}">
            @csrf
            @method('PUT')

            <!-- タイトル(必須) -->
            <div class="mt-4 form-group">
                <x-text-input id="title" class="form-control" type="text" name="comment" :value="old('comment', $comment->comment)" required autofocus autocomplete="title" placeholder="コメント" />
                <x-input-error :messages="$errors->get('comment')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="btn btn-primary btn-block">
                    {{ __('登録') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</div>
@endsection