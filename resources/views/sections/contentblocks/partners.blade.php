@extends ('master')

@section ('content')

@include ('movies.title')

<div class="container-fluid container-fullw block-partners">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">Add partner logos as transparent PNG, links and optional text description below:</p>
    </div>	
	
    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>


  <div class="row">
    <div class="col-sm-12">

    <form method="post" action="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/featured') }}" enctype="multipart/form-data" id="main-form">
      {{ csrf_field() }}

      <div class="panel panel-white panel-main">
        <div class="panel-body no-padding">
          <table class="table-sortable expandable">
            <thead>
              <tr>
                <th class="">Partner Name</th>
                <th class="">External URL</th>
                <th class="col-actions"></th>
              </tr>
            </thead>
            <tbody class="ui-sortable" data-type="featured">
				@foreach ($featuredContents as $featuredContent)
	              <tr class="subpanel" data-id="{{$featuredContent->id}}">
	                <td class="">
	                  <i class="ti-move handle"></i>
	                  <input class="on" name="name-{{$featuredContent->id}}" type="text" placeholder="Partner Name" data-orig-value="{{ $featuredContent->name }}" value="{{ $featuredContent->name }}"/>
	                </td>
	                <td class="">
	                  <input class="on" name="url-{{$featuredContent->id}}" type="text" placeholder="External URL" data-orig-value="{{ $featuredContent->url }}" value="{{ $featuredContent->url }}"/>
	                </td>
	                <td class="col-actions">
	                  <div>
	                    <a><i class="ti-pencil icon-actions pull-left"></i></a>
	                    <a class="delete-confirm" data-object="partner" href="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/featured/'.$featuredContent->id.'/delete') }}">
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
	                        
		                        
		                        @foreach ($featuredImages as $featuredImage) 
		                        	@if ($featuredImage->site_location == $featuredContent->id) 
		                        		<img src="{{ asset($featuredImage->path)  }}" class="imagePreview"/>
		                        	@else
<!-- 		                        		<i class="ti-image"></i> -->
		                        	@endif
		                        @endforeach
		                        
<!--
	                        <div class="file-preview-empty file-banner">
	                        </div>
-->
	                      </div>
	                      <div class="col-sm-2">
	                        <div class="panel-file-upload-btns">
	                          <span class="pull-left btn-file icon-actions-clear">
	                            <i class="ti-pencil-alt"></i>
	                            <input type="file" nv-file-select="" uploader="uploader" name="image-{{$featuredContent->id}}" />
	                          </span>
	                          @foreach ($featuredImages as $featuredImage) 
		                        	@if ($featuredImage->site_location == $featuredContent->id) 
		                        		<a href="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/image/'.$featuredImage->id.'/delete') }}" class="delete-confirm pull-left icon-actions fa fa-close" data-object="image"></a>
		                        	@endif
		                      @endforeach
	                        </div>
	                      </div>
	
	                    </div>
	                  </div>
	                  <!-- end: FILE UPLOADER WIDGET -->
	                  
	                  
	                  
	                  <div class="on">
	                    <h5>Description&nbsp;&nbsp;<span class="text-red text-small">*optional</span></h5>
	                    
	                    <pre class="hidden orig-textarea">{{$featuredContent->body}}</pre>
	                    <textarea id="description_{{$featuredContent->id}}" name="body-{{$featuredContent->id}}" class="ckeditor form-control" rows="3">{{$featuredContent->body}}</textarea>
	                    
	                    
	                    <button type="button" class="btn btn-cancel btn-grey pull-right">CANCEL</button>
	                    <button type="submit" class="btn btn-orange pull-right btn-save">SAVE CHANGES</button>
	                  </div>
	                </td>
	              </tr>
            @endforeach

            <tr class="nosort">
	              <td>
		              <a href="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/featured/create?type=partner') }}" class="btn btn-success">Add New Partner</a>
	              </td>				
	          </tr> 
            </tbody>
          </table>
        </div>

<!--
        <div class="panel-heading">
          <h5 class="panel-title">Add a Partner</h5>
        </div>
        <div class="panel-body">
          <form name="" role="form" class="form-inline">
            <div class="form-group">
              <input type="text" name="actor" placeholder="Name" class="form-control">
            </div>
            <div class="form-group">
              <input type="text" name="character" placeholder="External URL" class="form-control">
            </div>
            <button class="btn btn-success" type="submit">Add Partner</button>
          </form>
        </div>
-->
      </div>
      </form>
      


      <!-- BACKGROUND CONTENT PANEL -->
      <div class="panel panel-white panel-file-upload">
        <div class="panel-heading">
          <h5 class="panel-title">Partners Block Background Content</h5>
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
          @include('sections.colorpicker.edit')

        </div>
      </div>


    </div>
  </div>
</div>
@stop