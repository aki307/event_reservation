@if (session('success'))
<div class="alert alert-success">
    <ul>
        @foreach (session('success') as $successMessage)
        <li>{{ $successMessage }}</li>
        @endforeach
    </ul>
</div>
@endif
