@extends ('master')

@section ('content')

@include ('movies.title')
<form action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/release-date') }}" method="post" id="main-form">
    {{ csrf_field() }}
    
<div class="container-fluid container-fullw block-release-dates">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">Set date announcements below. Announcement dates determine <i>when the announcement will appear</i> on the website and in the global menu.</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>


  <div class="row">
    <div class="col-sm-12">

      <!-- RELEASE DATES FORM -->
      <div class="panel panel-white">
        <div class="panel-body">

          <table class="expandable">
            <thead>
              <tr>
                <th class="col-sm-4">Release Date Text</th>
                <th class="col-sm-3">Category</th>
                <th class="col-sm-3">Announcement Date</th>
                <th class="col-sm-2"></th>
              </tr>
            </thead>
            <tbody>

        @foreach($releaseDates as $releaseDate)

              @if($releaseDate->actual)
                <tr class="form-inline" style="font-weight:bold">
              @else
                <tr class="form-inline">
              @endif
                <td class="col-sm-4 announcement">
                  @if($releaseDate->actual)
                  <span class="text-red" style="margin-left:-10px;font-size:20px; line-height:20px">*</span>
                  @endif
                  <input class="on form-control" type="text" name="text-{{ $releaseDate->id }}" placeholder="Announcement Text (e.g. In Theaters This August)" value="{{ $releaseDate->text }}"/>
                </td>
                <td class="col-sm-3">
                  <div class="on input-group input-group-select">
                    <select type="text" class="form-control" name="type-{{ $releaseDate->id }}">
	                    <option value="in_theaters" {{ ($releaseDate->type == 'in_theaters') ? 'selected' : ''}}>In Theaters</option>
	                    <option value="at_home" {{ ($releaseDate->type == 'at_home') ? 'selected' : ''}}>At Home</option>
	                    <option value="coming_soon" {{ ($releaseDate->type == 'coming_soon') ? 'selected' : ''}}>Coming Soon</option>
                    </select>
                  </div>
                </td>
                <td class="col-sm-3">
                  <div class="on input-group input-append datepicker date">
                    <input type="text" class="form-control" name="date-{{ $releaseDate->id }}" value="{{ $releaseDate->date }}">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">
                        <i class="glyphicon glyphicon-calendar"></i>
                      </button> 
                    </span>
                  </div>
                </td>
                <td class="col-sm-2 actions">
                  <a href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/release-date/'.$releaseDate->id.'/duplicate') }}"><i class="fa fa-plus icon-actions pull-left"></i></a>
                  <a class="delete-confirm" data-object="announcement" href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/release-date/'.$releaseDate->id.'/delete') }}"><i class="fa fa-minus icon-actions pull-left"></i></a>
                </td>
              </tr>

        @endforeach
            </tbody>
          </table>
          <br/><br/><br/>
          <a href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/release-date/create') }}" class="btn btn-success">Add New Date</a>
          <br/><br/><br/>
          <small class="text-red">*&nbsp;Official release date</small>
        </div>

        <br/>

      </div>


    </div>
  </div>
</div>
</form>
@stop
