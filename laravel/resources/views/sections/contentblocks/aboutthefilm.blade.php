@extends ('master')

@section ('content')

@include ('movies.title')


<!-- VIDEOS SECTION -->
<div class="container-fluid container-fullw block-about">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">Select the area below you want to edit:</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>



  <div class="row">
    <div class="col-sm-12">

      <form method="POST" action="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/about') }}" id="main-form" enctype="multipart/form-data">
        {{ csrf_field() }}  
        
        <input type="hidden" name="tab" value=""/>
         
        <!-- TAB CONTENT -->
        <div class="tab-content" id="aboutTabs">

          <!-- TABS -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#synopsis" aria-controls="synopsis" role="tab" data-toggle="tab">Synopsis</a></li>
            <li role="presentation" class=""><a href="#cast" aria-controls="cast" role="tab" data-toggle="tab">Cast</a></li>
            <li role="presentation" class=""><a href="#crew" aria-controls="crew" role="tab" data-toggle="tab">Crew</a></li>
          </ul>



          <div role="tabpanel" id="synopsis" class="tab-pane active">
            @include('movies.about')
          </div>

          <div role="tabpanel" id="cast" class="tab-pane">
            <div class="panel">
              @include ('sections.people.index', array('occupation' => 'cast', 'tab' => 'cast'))
              <a href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/about/create?occ=cast') }}" class="btn btn-success" type="submit">Add Cast Member</a>
            </div>
          </div>

          <div role="tabpanel" id="crew" class="tab-pane">
            <div class="panel">
              @include ('sections.people.index', array('occupation' => 'crew', 'tab' => 'crew'))
              <a href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/about/create?occ=crew') }}" class="btn btn-success" type="submit">Add Crew Member</a>
            </div>
          </div>
        </div>
      </form>


      <!-- SYNOPSIS IMAGE UPLOAD -->
      <div class="panel panel-white panel-file-upload">
        <div class="panel-heading">
          <h5 class="panel-title">About the Film Block Background Content</h5>
        </div>

        <div class="panel-body">

          <!-- FILE UPLOADER WIDGET -->
          <div class="file-uploader">
            <div class="row">
              @include('sections.images.edit')
              @include('sections.images.create')
            </div>
          </div>
          <!-- end: FILE UPLOADER WIDGET -->

          <!-- COLOR PICKER -->
          @include ('sections.colorpicker.edit')
        </div>
      </div>

    </div>
  </div>

</div>


@stop