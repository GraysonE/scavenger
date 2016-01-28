<!-- FILE UPLOADER WIDGET -->
<div class="col-sm-3">
	
	@if (isset($gallery))
    	<form class="image-upload-form" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/upload-gallery') }}" method="post" enctype="multipart/form-data">
    @else
		<form class="image-upload-form" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/upload') }}" method="post" enctype="multipart/form-data">
  	@endif
    {!! csrf_field() !!}

    <div class="btn-file file-drop-zone">
      <i class="fa fa-cloud-download"></i>
      DropZone
      <div>Drag files to upload (or click)</div>
      @if (isset($gallery))
      	<input multiple type="file"  name="image[]" id="imageUpload"/>
      @else
      	<input type="file"  name="image" id="imageUpload"/>
      @endif
      <input type="hidden" name="site_location" value=""/>
      <input type="hidden" name="section_id" value="{{ (isset($currentSection->id)) ? $currentSection->id : '' }}"/>
    </div>
    <input class="form-control hidden" type="text" name="title" placeholder="Image Title" value="No Title"/>
    @if (isset($gallery))
    <input type="hidden" name="gallery" value="1"/>
    @endif
  </form>
</div>
<!-- end: FILE UPLOADER WIDGET -->