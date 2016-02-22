@foreach ($globalCTAs as $globalCTA)
	@if ($globalCTA->column == $column) 
	
  <tr class="" data-id="{{ $globalCTA->id }}">
      
      <td class="col-sm-4">
        <i class="ti-move handle"></i>
        <input class="on form-control" type="text" value="{{ $globalCTA->text }}" name="text-{{ $globalCTA->id }}"/>
      </td>
      
      <td class="col-sm-4">
        <input class="on form-control" type="text" value="{{ $globalCTA->url }}" name="url-{{ $globalCTA->id }}"/>
      </td>
      
      <td class="col-sm-2">
        <div class="form-group form-group-role on">
           <div class="input-group input-group-select">

             <select name="target-{{ $globalCTA->id }}" class="form-control">
               <option value="">- Target -</option>
               <?php 
             if ($globalCTA->target == '_self') {
                 $selected = 'selected';
               } else {
                 $selected = '';
               }
               ?>
               <option value="_self" {{ $selected }}>_self</option>
               <?php 
             if ($globalCTA->target == '_blank') {
                 $selected = 'selected';
               } else {
                 $selected = '';
               }
               ?>
               <option value="_blank" {{ $selected }}>_blank</option>
             </select>
             <input type="hidden" value="{{ $column }}-{{ $globalCTA->id }}"/>

           </div>
        </div>
      </td>
      
      <td class="col-sm-2 actions">
        <a href="{{ url('settings/'.$globalCTA->id.'/duplicate?type=cta') }}"><i class="fa fa-plus icon-actions pull-left"></i></a> 
        <a class="delete-confirm" data-object="link" href="{{ url('settings/'.$globalCTA->id.'/delete?type=cta') }}"><i class="fa fa-minus icon-actions pull-left"></i></a>
      </td>
      
		</tr>
    
	@endif
@endforeach


