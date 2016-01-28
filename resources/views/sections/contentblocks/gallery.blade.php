@extends ('master')

@section ('content')

@include ('movies.title')

<div class="container-fluid container-fullw block-gallery">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">Upload image as JPEG saved at approx 60-70% compression with a maximum width of 1280 pixels:</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>


  <div class="row">
    <div class="col-sm-8 col-lg-9">
      <div class="panel panel-main">

        <form method="post" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/gallery') }}" id="main-form">
	        {{ csrf_field() }}
	        <input type="hidden" name="gallery" value='1' />
	        
          <div class="panel-body no-padding">
            <table class="table-sortable expandable">
              <tbody class="ui-sortable" data-type="images">


                  @if(count($images) === 0)

                  <tr class="row-preview">
                    <td style="width:25%">
                      <!-- EMPTY FILE PREVIEW -->
                      <div class="file-uploader margin-left-10">
                        <div class="file-preview-empty"><i class="ti-image"></i></div>
                      </div>
                    </td>
                    <td style="width:75%">
                        Upload images with the file DropZone.
                    </td>                      
                  </tr>

                @else

                  @foreach ($images as $image)

                    <tr class="" data-id="{{ $image->id }}">
                      <td class="gallery-thumb">
                        <i class="ti-move handle"></i>
                        <img src="{{ asset($image->path)  }}" class="imagePreview"/>
                      </td>
                      <td class="description">
                        <input name="title-{{ $image->id }}" class="on form-control" type="text" placeholder="Image description (used as alt tag)" value="{{ $image->title }}"/>
                      </td>
                      <td class="col-actions">
                        <div>
                          <!-- Comment: maybe disallow editing image to maintain form scope??? -->
                          <!--a><i class="ti-pencil icon-actions pull-left"></i></a-->
                          <a class="delete-confirm" data-object="image" href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/image/'.$image->id.'/delete') }}">
                            <i class="fa fa-close icon-actions pull-left"></i>
                          </a>
                        </div>
                      </td>
                    </tr>

                  @endforeach

                @endif
                
              </tbody>
            </table>
          </div>
        </form>

      </div>
	
    </div>

    <div class="col-sm-4 col-lg-3">
      <div class="panel panel-main">
        
        <div class="file-uploader upload-only">
          @include('sections.images.create', array('gallery' => true))
        </div>
      </div>
    </div>
  </div>
</div>


@stop