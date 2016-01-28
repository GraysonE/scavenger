
<div role="tabpanel" id="social" class="tab-pane active">
	<div class="panel panel-white">
	    <div class="panel-body">
	
	
		@if ($globalSocialMedia->isEmpty()) 
		<div class="form-group form-inline">
			<label for="facebook">Facebook</label>
			<input class="form-control" type="text" value="http://facebook.com/stx" name="facebook"/>
		</div>
			<div class="form-group form-inline">
			<label for="twitter">Twitter</label>
			<input class="form-control" type="text" value="http://twitter.com/stx" name="twitter"/>
		</div>
		<div class="form-group form-inline">
			<label for="tumblr">Tumblr</label>
			<input class="form-control" type="text" value="http://tumblr.com/stx" name="tumblr"/>
		</div>
		<div class="form-group form-inline">
			<label for="instagram">Instagram</label>
			<input class="form-control" type="text" value="http://instagram.com/stx" name="instagram"/>
		</div>
		
		
		@endif
	
	
        @foreach ($globalSocialMedia as $social)
	
        <div class="form-group form-inline">
        <!-- <input class="form-control" type="text" value="{{ $social->tag }}" name="tag-{{ $social->id }}"/> -->
	
          @if ($social->tag == 'facebook')
          <label for="facebook">Facebook</label>
          @elseif ($social->tag == 'twitter')
          <label for="twitter">Twitter</label>
          @elseif ($social->tag == 'tumblr')
          <label for="tumblr">Tumblr</label>
          @elseif ($social->tag == 'instagram')
          <label for="instagram">Instagram</label>
          @elseif ($social->tag == 'custom')
          <label for="custom">Custom</label>
          @endif

          @if (($social->tag == 'og_title') || ($social->tag == 'og_type') || ($social->tag == 'og_image') || ($social->tag == 'og_url'))
          <!-- Do nothing -->
          @else

            <input class="form-control" type="text" value="{{ $social->url }}" name="socialURL-{{ $social->id }}"/>
            <a class="delete-confirm" data-object="social media" href="{{ url('settings/'.$social->id.'/delete?type=social') }}"><i class="fa fa-minus icon-actions"></i></a>
			
          @endif
	        
	      </div>
	      
	  @endforeach
	  		<a href="{{ url('settings/new/social') }}" class="btn btn-success">Add New Social Media</a>
		</div>
	</div>
</div>


<!-- OG TAGS TAB -->
          <div role="tabpanel" id="ogtags" class="tab-pane">
            <div class="panel panel-white">
              <div class="panel-body">


			  	@if ($globalSocialMedia->isEmpty()) 
					<div class="form-group form-inline">
						<label for="og_title">og:title</label>
						<input class="form-control" type="text" value="" name="og_title"/>
					</div>
						<div class="form-group form-inline">
						<label for="og_type">og:type</label>
						<input class="form-control" type="text" value="" name="og_type"/>
					</div>
					<div class="form-group form-inline">
						<label for="og_image">og:image</label>
						<input class="form-control" type="text" value="" name="og_image"/>
					</div>
					<div class="form-group form-inline">
						<label for="og_url">og:url</label>
						<input class="form-control" type="text" value="" name="og_url"/>
					</div>
					
					<input type="hidden" value="1" name="pre-edit"/>
				
				@endif



                @foreach ($globalSocialMedia as $social)

                @if (($social->tag == 'og_title') || ($social->tag == 'og_type') || ($social->tag == 'og_image') || ($social->tag == 'og_url'))
                
                  <div class="form-group form-inline">
                    @if ($social->tag == 'og_title')
                    <label for="og_title">og:title</label>
                    @elseif ($social->tag == 'og_type')
                    <label for="og_type">og:type</label>
                    @elseif ($social->tag == 'og_image')
                    <label for="og_image">og:image</label>
                    @elseif ($social->tag == 'og_url')
                    <label for="og_url">og:url</label>
                    @endif

					@if ((isset($globalSocialMediaImage)) && ($social->tag == 'og_image'))
                    	<input class="form-control" type="text" value="{{ $globalSocialMediaImage->path }}" name="{{ $social->tag }}"/>
                    @else
                    	<input class="form-control" type="text" value="{{ $social->url }}" name="{{ $social->tag }}"/>
                    @endif
                    
                    <input class="form-control" type="hidden" value="{{ $social->id }}" name="id-{{ $social->id }}"/>
<!--
                    <a href="{{ url('settings/'.$social->id.'/duplicate?type=social') }}"><i class="fa fa-plus icon-actions"></i></a> 
                    <a class="delete-confirm" data-object="OG tag" href="{{ url('settings/'.$social->id.'/delete?type=social') }}"><i class="fa fa-minus icon-actions"></i></a>
-->
    
                  </div>

                @endif
                @endforeach
				
              </div>
            </div>
          </div>
          <!-- SHARE IMAGES TAB -->
          <div role="tabpanel" id="share" class="tab-pane">
            <div class="panel panel-white">
              <div class="panel-body">
                <!-- FILE UPLOADER WIDGET -->
                <div class="file-uploader">
                  <div class="row">
                    <div class="col-sm-7 ui-sortable" data-type="global_social_media">


                      <div class="row file-thumbnail" data-id="SOMEID">
                        <div class="col col-sm-10">
	                        @if (isset($globalSocialMediaImage))
	                        	<img src="{{ asset($globalSocialMediaImage->path) }}" width="180"/>
	                        @else
	                        	<img src="{{ asset('/admin-files/assets/images/image-empty.png') }}" width="180"/>
	                        @endif
                        </div>
                        <div class="col col-sm-2 col-actions">
	                        @if (isset($globalSocialMediaImage))
                          <a class="delete-confirm" data-object="image" href="{{ url('/settings/'.$globalSocialMediaImage->id.'/delete/image') }}">
	                          <i class="fa fa-close icon-actions pull-left"></i>
	                      </a>
                          @endif
                        </div>
                      </div>


                    </div>

                    <div class="col-sm-5">
                      <div class="btn-file file-drop-zone" nv-file-over uploader="uploader">
                        <i class="fa fa-cloud-download"></i>
                        DropZone
                        <div>Drag files to upload (or click)</div>
                        <input type="file" name="image" nv-file-select="" uploader="uploader"  />
                      </div>
                    </div>

                  </div>
                </div>
                <!-- end: FILE UPLOADER WIDGET -->
              </div>
            </div>
          </div>