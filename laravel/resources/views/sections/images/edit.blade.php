<div class="col-sm-9">

  @if(count($images) === 0)

    <!-- EMPTY FILE PREVIEW -->
    <div class="file-preview-empty file-banner">
      <i class="ti-image"></i>
    </div>

  @else
  
    <form method="post" action="">

      @foreach ($images as $image)
      <div>
        <div class="form-group form-group-image">
          <img src="{{ asset($image->path)  }}" class="imagePreview"/>
          <a class="delete-confirm" data-object="image" href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/image/'.$image->id.'/delete') }}">
            <i class="icon-actions-clear fa fa-times-circle"></i>
          </a>
        </div>
          
      </div>
      @endforeach

    </form>

  @endif
  
</div>
