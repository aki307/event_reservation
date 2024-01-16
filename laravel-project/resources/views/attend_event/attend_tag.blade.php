@if(Auth::check())
  @if($userAttendance)
    <span class="badge text-bg-danger">参加</span>
  @endif
@endif