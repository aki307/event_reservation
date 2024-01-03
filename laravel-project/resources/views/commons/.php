@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif



@if (count($errors) > 0)
    <ul class="alert alert-danger" role="alert">
        @foreach ($errors->all() as $error)
            <li class="ml-4">{{ $error }}</li>
        @endforeach
    </ul>
@endif