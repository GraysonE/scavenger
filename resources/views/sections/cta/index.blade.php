<div class="panel-body">

  <form id="main-form" method="post" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/cta') }}">

  {{ csrf_field()}}
  
    <table class="table-sortable expandable" id="ctas">
      <thead>
        <tr>
          <th class="col-sm-3">CTA</th>
          <th class="col-sm-3">URL</th>
          <th class="col-sm-4">Anchor Link</th>
          <th class="col-sm-2"></th>
        </tr>
      </thead>
      <tbody class="ui-sortable" data-type="call_to_action">

      @foreach ($ctas as $cta)

        <tr class="ctas" data-id="{{ $cta->id }}">
          <td class="col-sm-3 cta-title">
            <i class="ti-move handle"></i>
            <input class="on form-control" type="text" placeholder="Button Text" value="{{ $cta->text }}" name="text-{{ $cta->id }}"/>
          </td>
          <td class="col-sm-3 cta-link">        
            <div class="on form-group form-group-link">
              <div class="input-group input-group-url">
                <input class="form-control" type="text" placeholder="External URL" value="{{ $cta->url }}" name="url-{{ $cta->id }}"/>
              </div>
            </div>
            
          </td>
          <td class="col-sm-4">
          	<div class="on input-group input-group-select">
              <select name="anchor-{{ $cta->id }}" class="form-control">
                <option value="">- Content block -</option>
                @foreach ($sections as $section)
                	@if ($cta->anchor == $section->id)
                		<option value="{{ $section->id }}" selected>{{ $section->title }}</option>
                	@else
                		<option value="{{ $section->id }}">{{ $section->title }}</option>
                	@endif
                @endforeach
              </select>
            </div>
          </td>
          <td class="col-sm-2 cta-actions">
            <a href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/cta/'.$cta->id.'/duplicate') }}"><i class="icon-actions fa fa-plus"></i></a> 
            <a href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/cta/'.$cta->id.'/delete') }}" class="delete-confirm" data-object="CTA button"><i class="icon-actions fa fa-minus"></i></a>
          </td>
        </tr>      

      @endforeach

	  	<tr class="nosort">
		  	<td>
			  	<a href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/cta/create') }}" class="btn btn-success" type="submit">Add CTA Button</a>
		  	</td>
	  	</tr>
      </tbody>
    </table>
  </form>
</div>
