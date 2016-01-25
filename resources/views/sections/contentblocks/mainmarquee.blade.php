@extends ('master')

@section ('content')

@include ('movies.title')

<!-- MARQUEE SECTION -->
<div class="container-fluid container-fullw block-marquee_cta">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">For the Main Marquee image or animation upload a video, gif, or jpeg:</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-white">
        <div class="panel-heading">
          <h5 class="panel-title">Main Marquee Background Content</h5>
        </div>

        <div class="panel-body">
          <div class="file-uploader">
            <div class="row">
              @include('sections.images.edit')
              @include('sections.images.create')
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-white">
        @include('sections.cta.index')
<!--         @include('sections.cta.create') -->
      </div>
    </div>
  </div>
</div>



@stop

