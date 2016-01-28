<form method="post" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id) }}" id="form-colorpicker">
    <input type="hidden" name="movie_id" value="{{ $movie->id }}"/>
    <input type="hidden" name="section_id" value="{{ $currentSection->id }}" />
    @if (isset($customBackgroundColor))
	<input type="hidden" value="{{ $customBackgroundColor->id }}" class="form-control" name="id"/>
	@endif
	
	
    <div class="form-group form-colorpicker padding-top-15">
      <div class="input-group">
        @if (isset($customBackgroundColor))
		        <input type="text" value="{{ $customBackgroundColor->custom_background }}" class="form-control" name="custom_background"/>
        <span class="input-group-addon"><i></i></span>

        @else

        <input type="text" value="#04c1d4" class="form-control" name="custom_background"/>
        <span class="input-group-addon"><i></i></span>

        @endif
      </div>
    </div>
  </form>
