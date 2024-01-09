@if(Auth::check())
@if($userAttendance)
<form id="delete" action="{{ route('events.unattend', ['event' => $event->id]) }}" method="POST">
    @csrf
    @method('DELETE')
    <x-primary-button class="btn btn-primary btn-block">
        {{ __('参加を取り消す') }}
    </x-primary-button>
</form>
@else
<form method="POST" action="{{ route('events.attend', ['event' => $event->id]) }}">
    @csrf
    <x-primary-button class="btn btn-primary btn-block">
        {{ __('参加する') }}
    </x-primary-button>
</form>
@endif
@endif