@if(Auth::check())
@if($userAttendance)
    <form id="unattend-form" action="{{ route('events.unattend', ['event' => $event->id]) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <button type="button" class="btn btn-warning" onclick="document.getElementById('unattend-form').submit();">
        {{ __('参加を取り消す') }}
    </button>
@else
    <form id="attend-form" action="{{ route('events.attend', ['event' => $event->id]) }}" method="POST" style="display: none;">
        @csrf
    </form>
    <button type="button" class="btn btn-info" onclick="document.getElementById('attend-form').submit();">
        {{ __('参加する') }}
    </button>
@endif
@endif
