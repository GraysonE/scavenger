<!-- SYNOPSIS TEXTAREA -->
  <div class="panel">
    <div class="panel-body">
      <textarea class="ckeditor form-control" name="about" rows="10">{{ $movie->about }}</textarea>
      <input name="section_id" type="hidden" value="{{ $currentSection->id }}">
      <!--button type="submit">Save Synopsis</button-->
    </div>
  </div>