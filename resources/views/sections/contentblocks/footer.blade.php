@extends ('master')

@section ('content')

@include ('movies.title')


<div class="container-fluid container-fullw block-moviefooter">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">Add ratings image, links, and production company logos below:</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>


  <div class="row">
    <div class="col-sm-12">

	<form method="post" id="main-form" action="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/featured') }}" enctype="multipart/form-data">
  {{ csrf_field() }}
      <!-- LEGAL LINE PANEL -->
      <div class="panel panel-white panel-legalline">
        <div class="panel-heading">
          <h5 class="panel-title">Movie Specific Legal Line</h5>
        </div>

        <div class="panel-body subpanel form-inline">
          <input class="on form-control" type="text" name="legal_line" placeholder="&copy; 2015 STX Entertainment. All Rights Reserved." value="{{ $movie->legal_line }}"/>
          <i class="ti-pencil-alt icon-actions-clear" style="cursor:auto"></i>
        </div>
      </div>
      
      
      <!-- LOGO PANEL -->
      <div class="panel panel-white panel-main">
        <div class="panel-body no-padding">
          <table class="table-sortable expandable">
            <thead>
              <tr>
                <th class="">Item</th>
                <th class="">External URL</th>
                <th class="col-actions"></th>
              </tr>
            </thead>
            <tbody class="ui-sortable" data-type="featured">
              
              @foreach ($featuredContents as $featuredContent)
	              <tr class="subpanel" data-id="{{$featuredContent->id}}">
	                <td class="">
	                  <i class="ti-move handle"></i>
	                  <input class="on" name="name-{{$featuredContent->id}}" type="text" placeholder="Rating" data-orig-value="{{ $featuredContent->name }}" value="{{ $featuredContent->name }}"/>
	                </td>
	                <td class="">
	                  <input class="on" name="url-{{$featuredContent->id}}" type="text" placeholder="http://example.com" data-orig-value="{{ $featuredContent->url }}" value="{{ $featuredContent->url }}"/>
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
                  <a href="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/featured/create?type=rating') }}" class="btn btn-success">Add Rating</a>
                </td>				
              </tr> 
            </tbody>
          </table>
        </div>
      </div>
</form>
      


      <!-- CREDIT IMAGE PANEL -->
      <div class="panel panel-white panel-file-upload">
        <div class="panel-heading">
          <h5 class="panel-title">Credit Image</h5>
        </div>

        <div class="panel-body">
            <form method="post" class="image-upload-form" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/credit/upload') }}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="file-uploader">
                <div class="row">
                  <div class="col-sm-9">
                    <div>
                      <div class="form-group form-group-image">
                        @if (isset($creditImage))
                        <img src="{{ asset($creditImage->path)  }}" class="imagePreview"/>
                        <a class="delete-confirm" data-object="image" href="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/image/'.$creditImage->id.'/delete') }}">
                          <i class="icon-actions-clear fa fa-times-circle"></i>
                        </a>
                        @else
                          <!-- EMPTY FILE PREVIEW -->
                          <div class="file-preview-empty file-banner">
                            <i class="ti-image"></i>
                          </div>
                        @endif
                      </div>
                    </div>                      
                  </div>
                  <div class="col-sm-3">
                    <div class="btn-file file-drop-zone">
                      <i class="fa fa-cloud-download"></i>
                      DropZone
                      <div>Drag files to upload (or click)</div>
                      <input type="file" name="image" id="imageUpload"/>
                      <input type="hidden" name="site_location" value="credit"/>
                    </div>
                  </div>
                </div>
              </div>
            </form>
        </div>
      </div>


      <!-- BACKGROUND CONTENT PANEL -->
      <div class="panel panel-white panel-file-upload">
        <div class="panel-heading">
          <h5 class="panel-title">Footer Block Background Content</h5>
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