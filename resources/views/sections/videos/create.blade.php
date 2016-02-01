<div class="panel-heading">
  <h5 class="panel-title">Add a Video</h5>
</div>

<div class="panel-body">
	<form name="addVideoForm" role="form" class="form-inline addVideoForm" method="post" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/videos') }}">
	{{ csrf_field()}}
	
    <div class="form-group form-group-title">
      <input type="text" class="form-control" placeholder="Video Title" name="title"/>
    </div>

    <div class="form-group form-group-link">
      <input type="text" class="form-control" placeholder="YouTube ID (eg. mENu5R62l5c)" name="youtube_id"/>
    </div>
	<input type="hidden" name="new" value="1" />
    <button class="btn btn-success" type="submit">Add Video</button>
  </form>
</div>