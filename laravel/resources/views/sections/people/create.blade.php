<div class="panel-heading">
  <h5 class="panel-title">Add a {{ ucfirst($occupation) }} Member</h5>
</div>

<form role="form" class="form-inline" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/person') }}" method="post">
  {{ csrf_field() }}
  <div class="panel-body">

    <div class="form-group">
      <input type="text" name="first_name" placeholder="First Name" class="form-control">
    </div>
    <div class="form-group">
      <input type="text" name="last_name" placeholder="Last Name" class="form-control">
    </div>
    <div class="form-group">
      @if($occupation == 'cast')
        <input type="text" name="role" placeholder="Role Played" class="form-control">
      @else
        <input type="text" name="role" placeholder="Title" class="form-control">
      @endif
    </div>
    <input type="hidden" name="tab" value="{{ $tab }}">
    <input type="hidden" name="occupation" value="{{ $occupation }}">
    <button class="btn btn-success" type="submit">Add {{ ucfirst($occupation) }} Member</button>

  </div>
</form>