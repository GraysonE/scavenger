@extends ('master')

@section ('content')

<!-- start: DASHBOARD TITLE -->
<section id="page-title" class="">
  <div class="row">
    <div class="col-sm-12">
      <h1>{{ $movie -> title}}</h1>
      <span class="breadcrumb">
        Content Blocks > Design
      </span>
    </div>
  </div>
</section>
<!-- end: DASHBOARD TITLE -->


<!-- MARQUEE SECTION -->
<div class="container-fluid container-fullw block-design">
  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">Edit movie-specific designs here!</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>


  <div class="row">
    <div class="col-sm-12">

      <form method="post" id="main-form" action="{{ url('/admin/movies/'.$movie->id.'/edit/design') }}">
	      <input type="hidden" value="{{ $design->id }}" name="id"/>
        {{ csrf_field()}}

        <div class="panel panel-white">
          <div class="panel-heading">
            <div class="panel-title">Site Colors</div>
            <p class="text-small padding-top-10">Enter a hex code or click the color swatches to define colors:</p>
          </div>

          @include ('designs.colors.edit')

        </div>

        <!-- SITE FONTS -->
        <!-- need to include all the fonts into this page? or use image thumbnails? -->
        <div class="panel panel-white panel-font-select">	
          <div class="panel-heading">
            <div class="panel-title">Site Fonts</div>
          </div>

          @include ('designs.fonts.edit')

          <!--button type="submit">Update Designs</button-->
        </div>

      </form>
      
      
      <!-- POSTER IMAGE UPLOADER WIDGET -->
      <div class="panel panel-white panel-file-upload">
        <div class="panel-heading">
          <h5 class="panel-title">Poster Image</h5>
          <small>Single image for dropdown header menu</small>
        </div>

        <div class="panel-body">
          <form method="post" class="image-upload-form" action="{{ url('/admin/movies/'.$movie->id.'/edit/design/upload') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="file-uploader">
              <div class="row">
                <div class="col-sm-8">
                  <div>
                    <div class="form-group form-group-image">
                      @if (isset($poster))
                      <img src="{{ asset($poster->path)  }}" class="imagePreview"/>
                      <a class="delete-confirm" data-object="image" href="{{ url('admin/movies/'.$movie->id.'/edit/design/'.$poster->id.'/delete') }}">
                        <i class="icon-actions-clear fa fa-times-circle"></i>
                      </a>
                      @else
                        <!-- EMPTY FILE PREVIEW -->
                        <div class="file-preview-empty file-banner">
                          <i class="ti-image"></i>
                        </div>
                      @endif
                    </div>
                  </div>                      
                </div>
                <div class="col-sm-4">
                  <div class="btn-file file-drop-zone">
                    <i class="fa fa-cloud-download"></i>
                    DropZone
                    <div>Drag files to upload (or click)</div>
                    <input type="file" name="image" id="imageUpload"/>
                    <input type="hidden" name="site_location" value="poster"/>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- end: FILE UPLOADER WIDGET -->
		
      <!-- COMING SOON PREVIEW IMAGE UPLOADER WIDGET -->
      <div class="panel panel-white panel-file-upload">
        <div class="panel-heading">
          <h5 class="panel-title">Listing Image</h5>
          <small>Single image for the In Theaters, At Home, Coming Soon listing pages.</small>
        </div>

        <div class="panel-body">
          <form method="post" class="image-upload-form" action="{{ url('/admin/movies/'.$movie->id.'/edit/design/upload') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="file-uploader">
              <div class="row">
                <div class="col-sm-8">
                  <div>
                    <div class="form-group form-group-image">
                      @if (isset($comingSoon))
                      <img src="{{ asset($comingSoon->path)  }}" class="imagePreview"/>
                      <a class="delete-confirm" data-object="image" href="{{ url('admin/movies/'.$movie->id.'/edit/design/'.$comingSoon->id.'/delete') }}">
                        <i class="icon-actions-clear fa fa-times-circle"></i>
                      </a>
                      @else
                        <!-- EMPTY FILE PREVIEW -->
                        <div class="file-preview-empty file-banner">
                          <i class="ti-image"></i>
                        </div>
                      @endif
                    </div>
                  </div>                      
                </div>
                <div class="col-sm-4">
                  <div class="btn-file file-drop-zone">
                    <i class="fa fa-cloud-download"></i>
                    DropZone
                    <div>Drag files to upload (or click)</div>
                    <input type="file" name="image" id="imageUpload"/>
                    <input type="hidden" name="site_location" value="coming-soon"/>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- end: FILE UPLOADER WIDGET -->
      
      
      
    </div>
  </div>
</div>

<!-- load fonts dynamically on this page for preview purposes only -->
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js"></script>
<script>
  WebFont.load({
    google: {
      families: ['Lato', 'Cinzel', 'Abel', 'Open Sans Condensed', 'Fjord One', 'Oswald', 'Montserrat', 'Merriweather', 'Fjalla One', 'Playfair Display']
    }
  });
</script>
@stop

