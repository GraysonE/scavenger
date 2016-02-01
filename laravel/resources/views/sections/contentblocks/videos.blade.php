@extends ('master')

@section ('content')

@include ('movies.title')

<!-- VIDEOS SECTION -->
<div class="container-fluid container-fullw block-videos">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">To add a video enter in the YouTube ID below:</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>

  <div class="row">
    <div class="col-sm-12"> 
      <div class="panel panel-white">
        <form id="main-form" method="post" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/videos') }}">
          {{ csrf_field()}}
          @include ('sections.videos.index')
        </form>
        
        @include ('sections.videos.create')

      </div>
      
      <div class="panel panel-white">
        <div class="panel-heading">
          <h5 class="panel-title">Video Block Background Content</h5>
        </div>

        <div class="panel-body">
          <div class="file-uploader">
            <div class="row">
              @include('sections.images.edit')
              @include('sections.images.create')
            </div>
          </div>

          <!-- COLOR PICKER -->
          <div class="row padding-top-15">
            <div class="col-sm-12">
              @include ('sections.colorpicker.edit')
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>

@stop