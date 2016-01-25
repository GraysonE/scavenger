<?php
  /*
   * Derive path from movie title
   */
   use App\Helpers\Helper;  
?>
<!-- ALL MOVIES SECTION -->
<div class="container-fluid container-fullw bg-white page-movies">
  <div class="row">
    <div class="col-sm-12">

      <div class="panel">
        <div class="panel-body">
          <ul class="ui-sortable expandable" data-type="movies">
          
            @foreach($movies as $movie)
            
            <?php $movieDir = Helper::movieTitle($movie->title); ?>
            <li class="subpanel form-inline" data-id="{{ $movie->id }}">
              <form action="{{ url('/admin/movies/'.$movie->id) }}" method="post">
                <input name="_method" type="hidden" value="PUT">
                {{ csrf_field() }}
	            
	              <i class="ti-move handle"></i>
	              <a class="off" href="{{ action('MovieController@edit', $movie['id']) }}">{{ $movie->title }}</a>
	              <input class="on form-control" type="text" placeholder="Movie Title" value="{{ $movie->title }}" name="title"/>
	              <button type="submit" name="saveMovie" class="on form-control btn btn-orange">PUBLISH CHANGES TO DEV</button>
	
                <a href="{{ url('/admin/movies/'.$movie->id.'/delete') }}" data-object="movie" class="delete-confirm"><i class="icon-actions fa fa-minus pull-right"></i></a>
	              <i class="ti-pencil icon-actions pull-right"></i>
                <span class="pull-right" style="font-size:13px; padding-top:6px; padding-right:20px;">

                  <a href="{{ url('admin/movies/'.$movie->id.'/publish/html') }}" target="dev-{{$movie->id}}" class="text-primary">DEV</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                  
                  @if(File::exists(public_path($movieDir.'/qa/index.html')))
                    <a href="{{ url($movieDir.'/qa') }}" target="qa-{{$movie->id}}" class="text-primary">QA</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                  @else
                    <span class="text-light" title="Not yet published" alt="Not yet published">QA</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                  @endif
                  
                  @if(File::exists(public_path($movieDir.'/index.html')))
                    <a href="{{ url($movieDir) }}" target="live-{{$movie->id}}" class="text-primary">LIVE</a>
                  @else
                    <span class="text-light" title="Not yet published" alt="Not yet published">LIVE</span>
                  @endif

                </span>
                </form>
	            </li>
			
            @endforeach
          </ul>

        </div>
      </div>
      
      @include ('movies.create')
    </div>
  </div>
</div>

