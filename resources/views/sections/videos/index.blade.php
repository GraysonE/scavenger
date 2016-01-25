<!-- VIDEOS FORM -->
<div class="panel-body">
  <table class="table-sortable expandable">
    <thead>
      <tr>
        <th class="col-sm-4">Video Title</th>
        <th class="col-sm-3">YouTube ID</th>
        <th class="col-sm-2"></th>
        <th class="col-sm-3"></th>
      </tr>
    </thead>
    <tbody class="ui-sortable" data-type="videos">
      @foreach ($videos as $video)
      <tr class="" data-id="{{ $video->id }}">
        <td class="col-sm-4">
          <i class="ti-move handle"></i>
          <input class="on form-control" type="text" class="sectionTitle" placeholder="Video Title" value="{{ $video->title }}" name="title-{{ $video->id }}"/>
        </td>
        <td class="col-sm-3">
          <input class="on form-control" type="text" class="sectionTitle" placeholder="YouTube ID" value="{{ $video->youtube_id }}" name="youtube_id-{{ $video->id }}"/>
        </td>
        <td class="col-sm-2">
          <a href="//www.youtube.com/watch?v={{ $video->youtube_id }}" target=_blank><img src="//img.youtube.com/vi/{{ $video->youtube_id }}/default.jpg" width="120"/></a>
        </td>
        <td class="col-sm-3 actions">
          <a href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/videos/'.$video->id.'/duplicate') }}"><i class="fa fa-plus icon-actions pull-left"></i></a>
          <a class="delete-confirm" data-object="video" href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/videos/'.$video->id.'/delete') }}"><i class="fa fa-minus icon-actions pull-left"></i></a>        
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<br/>
