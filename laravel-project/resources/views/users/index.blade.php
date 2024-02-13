@extends('layouts.app')

@section('content')
<div class="text-left">
    <h1>ユーザ一覧</h1>
</div>
@if (count($users) > 0)
<form action="{{ route('users.index') }}" method="GET" class="mb-4">
    <div class="input-group mb-1">
        <input type="text" class="form-control" name="search" placeholder="名前検索する" aria-label="検索キーワード" aria-describedby="basic-addon1" value="{{ request('search') }}">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">検索</button>
            <a href="javascript:void(0);" onclick="toggleSearchForm()">詳細検索する▼</a>
        </div>
    </div>

    <table class="table" id="detailedSearchForm" style="display: none;">
        <tbody>
            <tr>
                <td class="table-secondary">年齢:</td>
                <td>
                    <input type="radio" name="age_sort" value="none" {{ request('age_sort') == 'none' ? 'checked' : '' }}> 未選択
                    <input type="radio" name="age_sort" value="asc" {{ request('age_sort') == 'asc' ? 'checked' : '' }}> 昇順
                    <input type="radio" name="age_sort" value="desc" {{ request('age_sort') == 'desc' ? 'checked' : '' }}> 降順
                </td>
            </tr>
            <tr>
                <td class="table-secondary">性別:</td>
                <td>
                    <input type="radio" name="gender" value="" {{ request('gender') == '' ? 'checked' : '' }}> 未選択
                    <input type="radio" name="gender" value="M" {{ request('gender') == 'male' ? 'checked' : '' }}> 男性
                    <input type="radio" name="gender" value="F" {{ request('gender') == 'female' ? 'checked' : '' }}> 女性
                </td>
            </tr>
            <tr>
                <td class="table-secondary">所属グループ:</td>
                <td>
                    @foreach($groups as $group)
                    <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" {{ in_array($group->id, request('group_ids', [])) ? 'checked' : '' }}>{{ config('groups.types.' . $group->name) }}<br>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">検索する</button>
                        <button type="submit" class="btn btn-secondary">キャンセル</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="flex items-center justify-end mt-4">
        <a class="btn btn-primary btn-block" href="{{ route('users.export') }}">CSV出力する</a>
    </div>
</form>
{{ $users->links('pagination::bootstrap-4') }}

<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">氏名</th>
            <th scope="col">年齢</th>
            <th scope="col">性別</th>
            <th scope="col">所属グループ</th>
            <th scope="col">操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <th scope="row">{{ $user->id }}</th>
            <td>{{ $user->user_name }}</td>
            <td>{{ $user->age }}</td>
            <td>{{ config('gender.types.' . $user->gender) }}</td>
            <td>
                {{ config('groups.types.' .$groups->firstWhere('id', $user->group_id)->name ) }}
            </td>
            <td>
                <a href="{{ route('users.show', ['user' => $user->id]) }}" class="btn btn-outline-dark">詳細</a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>ユーザー登録がまだされていません</p>
@endif
<div class="flex items-center justify-end mt-4">
    <a class="btn btn-primary btn-block" href="{{ route('register') }}">ユーザ登録</a>
</div>

@endsection