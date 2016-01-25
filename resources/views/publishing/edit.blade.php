<?php
  /*
   * Derive path from movie title
   */
   
   use App\Helpers\Helper;
   $movieDir = Helper::movieTitle($movie->title);
  
?>
@extends ('master')

@section ('content')

<!-- start: DASHBOARD TITLE -->
<section id="page-title" class="">
  <div class="row">
    <div class="col-sm-12">
      <h1>{{$movie->title}}</h1>
      <span class="breadcrumb">
        Content Blocks > Site Publishing

      </span>
    </div>
</section>
<!-- end: DASHBOARD TITLE -->

<!-- MARQUEE SECTION -->
<div class="container-fluid container-fullw bg-white block-publishing">

  <form method="post" action="{{ url('admin/movies/'.$movie->id.'/publishing/env') }}" id="main-form">

    {{ csrf_field() }}
    <div class="row page-description">
      <div class="col-sm-12">

        <p class="main-description">Please make sure you have reviewed and approved all changes prior to moving to QA and LIVE.</p>
        <p class="main-description">Drag and drop Content Blocks from left to right to promote to next server:</p>
        
        <input type="submit" class="btn btn-orange pull-right margin-bottom-30" value="SAVE ALL BLOCKS AND PUBLISH"/>
        <br/><br/><br/>
        <div class="row panel-servers">
          <div class="col col-sm-4 dev server" data-server="dev">
            <h4>Development Server</h4>
            <a class="btn-view" href="{{ url('admin/movies/'.$movie->id.'/publish/html') }}" target="stxdev">VIEW</a>


            @foreach ($sections as $section)
            <div data-position="{{ $section->sort_order }}" data-block-id="{{ $section->id }}" data-block-type="{{ $section->view }}" class="content-block">{{ $section->title }}<i class="fa fa-close icon-actions"></i></div>
            @endforeach

          </div>
          <div class="col col-sm-4 qa server" data-server="qa">
            <h4>QA Server</h4>
            @if(File::exists(public_path($movieDir.'/qa/index.html')))
            	<a class="btn-view" href="{{ url($movieDir.'/qa') }}" target="stxqa">VIEW</a>
			@endif

            @foreach ($sections as $section)
            @if ($section->qa)
            <div data-position="{{ $section->sort_order }}" data-block-id="{{ $section->id }}" data-block-type="{{ $section->view }}" class="content-block">{{ $section->title }}<i class="fa fa-close icon-actions"></i></div>
            @endif
            @endforeach

          </div>
          <div class="col col-sm-4 live server" data-server="live">
            <h4>Live Server</h4>
            @if(File::exists(public_path($movieDir.'/index.html')))
            	<a class="btn-view" href="{{ url($movieDir) }}" target="stxlive">VIEW</a>
			@endif
			
            @foreach ($sections as $section)
            @if ($section->live)
            <div data-position="{{ $section->sort_order }}" data-block-id="{{ $section->id }}" data-block-type="{{ $section->view }}" class="content-block">{{ $section->title }}<i class="fa fa-close icon-actions"></i></div>
            @endif
            @endforeach

          </div>
        </div>
      </div>
    </div>
    
    <br/><br/><br/>

    <select name="qa[]" multiple style="height:250px; width:200px; margin-left:100px;">
      @foreach($sections as $section)      
      <option value="{{$section->id}}" {{$section->qa ? 'selected' : ''}}>{{$section->title}}</option>
      @endforeach
    </select>
    <select name="live[]" multiple style="height:250px; width:200px; margin-left:100px;">
      @foreach($sections as $section)
      <option value="{{$section->id}}" {{$section->live ? 'selected' : ''}}>{{$section->title}}</option>
      @endforeach
    </select>

  </form>
  <!--a href="{{ url('admin/movies/'.$movie->id.'/publish/html') }}">Publish Link</a-->
</div>


@stop