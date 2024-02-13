<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" rel="stylesheet">

  <!-- Bootstrap CSSの読み込み（CDN） -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <!-- Laravelのアプリケーション固有のCSS -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
  <div class="container">
    @include('commons.error_messages')
    <div class="container my-5">
      <div class="card mx-auto" style="border-radius: 0;">
        <div class="card-header text-left">
          Event Manager
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- ログインID -->
            <div class="mb-3">
              <input id="login_id" class="form-control" type="text" name="login_id" :value="old('login_id')" required autofocus autocomplete="login_id" placeholder="ログインID" />
              <x-input-error :messages="$errors->get('login_id')" class="mt-2" />
            </div>
            <!-- パスワード -->
            <div class="mb-4">
              <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="パスワード" />
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="d-grid gap-2">
              <button class="btn btn-primary" type="submit">
                {{ __('ログイン') }}
              </button>
              <a href="{{ route('google.login') }}" class="btn btn-danger mt-2">
                Googleアカウントでログインする
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JSと依存ファイルの読み込み（CDN） -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <!-- Laravelのアプリケーション固有のJS -->
  <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>