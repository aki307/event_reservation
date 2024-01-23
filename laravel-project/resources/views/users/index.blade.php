@extends('layouts.app')

@section('content')
<div class="text-left">
    <h1>ユーザ一覧</h1>
</div>
@if (count($users) > 0)
{{ $users->links('pagination::bootstrap-4') }}
<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">氏名</th>
            <th scope="col">年齢
                <a href="{{ route('users.index', ['age_sort' => $ageSort == 'none' ? 'asc' : ($ageSort == 'asc' ? 'desc' : 'none')]) }}">
                    @if ($ageSort == 'desc')
                    <i class="fa-solid fa-up-long" style="color: #ff0033;"></i>
                    @elseif ($ageSort == 'asc')
                    <i class="fa-solid fa-down-long" style="color: #1ca6e7;"></i>
                    @else
                    <i class="fa-solid fa-up-down" style="color:#6cb47c;"></i>
                    @endif
                </a>
            </th>
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