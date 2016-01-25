<div class="panel-body">
    <table class="table-sortable expandable occupation-{{ $occupation }}">
      <thead>
        <tr>
          @if($occupation == 'cast')
          
            <th class="person-name">Actor Name</th>
            <th class="person-role">Character Played</th>
          
          @else
          
            <th class="person-name">Crew Member</th>
            <th class="person-role">Title</th>
            <th class="col-checkbox">Display w/Synopsis</th>

          @endif
          
          <th class="col-actions"></th>
          
        </tr>
      </thead>
      <tbody class="ui-sortable" data-type="people">

        @foreach ($people as $person)

        @if (($person->occupation) == $occupation )
        <tr class="subpanel" data-id="person-{{ $person->id }}">
          <td class="person-name">
            <i class="ti-move handle"></i>
            <input type="hidden" name="occupation-{{ $person->id }}" value="{{ $person->occupation }}" />
            <input class="on form-control" type="text" class="sectionTitle" placeholder="First Name" 
                   data-orig-value="{{ $person->first_name }}" value="{{ $person->first_name }}" name="first_name-{{ $person->id }}"/>
            <input class="on form-control" type="text" class="sectionTitle" placeholder="Last Name" 
                   data-orig-value="{{ $person->last_name }}" value="{{ $person->last_name }}" name="last_name-{{ $person->id }}"/>
          </td>

          <td class="person-role">
            <input class="on form-control" type="text" class="sectionTitle" placeholder="Role" 
                   data-orig-value="{{ $person->role }}-{{ $person->id }}" value="{{ $person->role }}" name="role-{{ $person->id }}"/>
          </td>

          @if($occupation == 'crew')
          <td class="col-checkbox">
            <div class="checkbox clip-check check-primary">
	            
	            <?php
		            if ($person->display) {
			            $checked = 'checked';
		            } else {
			            $checked = '';
		            }
		        ?>
	            
              <input type="checkbox" id="checkbox_{{ $person->id }}" name="display-{{ $person->id }}" 
                     data-orig-checkbox="{{ $person->display }}" value="1" {{ $checked }} />
              <label for="checkbox_{{ $person->id }}"></label>
            </div>
          </td>
          @endif

          <td class="col-actions">
            <div>
              <a class="wysiwygShow">
                <i class="ti-pencil icon-actions pull-left"></i>
              </a>
              <a class="delete-confirm" data-object="member" href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/about/'.$person->id.'/delete#'.$tab) }}">
                <i class="fa fa-close icon-actions pull-left"></i>
              </a>
            </div>
          </td>
          
          
          <td class="fullspan">
	          <!-- FILE UPLOADER WIDGET -->
	          <div class="file-uploader featured-uploader">
	            <div class="row">
	              <div class="col-sm-10">
	                <!-- EMPTY FILE PREVIEW -->
	                
	                    
	                    @foreach ($personImages as $personImage) 
	                    	@if ($personImage->site_location == $person->id) 
	                    		<img src="{{ asset($personImage->path)  }}" class="imagePreview"/>
	                    	@endif
	                    @endforeach
	              </div>
	              <div class="col-sm-2">
	                <div class="panel-file-upload-btns">
	                  <span class="pull-left btn-file icon-actions-clear">
	                    <i class="ti-pencil-alt"></i>
	                    <input type="file" nv-file-select="" uploader="uploader" name="image-{{$person->id}}" />
	                  </span>
	                  @foreach ($personImages as $personImage) 
	                    	@if ($personImage->site_location == $person->id) 
	                    		<a href="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/image/'.$personImage->id.'/delete') }}" class="delete-confirm pull-left icon-actions fa fa-close" data-object="image"></a>
	                    	@endif
	                  @endforeach
	                  
	                </div>
	              </div>
	
	            </div>
	          </div>
            
            <div class="on">
              <h5>Add or Edit Bio&nbsp;&nbsp;<span class="text-red text-small">*optional</span></h5>
              <!--
                IMPORTANT: UNIQUE TEXTAREA ID REQUIRED FOR EACH INSTANCE
                IMPORTANT2: Duplicate the textarea db data in <pre> so that data can be reverted when canceling edit.
              -->
              <pre class="hidden orig-textarea">{{ $person->biography }}</pre>
              <textarea id="biography_{{ $person->id }}" name="biography-{{ $person->id }}" class="ckeditor form-control wysiwyg">{{ $person->biography }}</textarea>
              <button type="button" class="btn btn-cancel btn-grey pull-right">CANCEL</button>
              <button type="submit" class="btn btn-save btn-orange pull-right">SAVE CHANGES</button>
            </div>
          </td>
        </tr>
        @endif

        @endforeach
      </tbody>
    </table>
  </div>
  <br/>