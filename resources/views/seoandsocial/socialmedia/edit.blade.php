<div class="tab-content" id="tabs">
  

  <!-- TABS -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#social" aria-controls="social" role="tab" data-toggle="tab">Social Media</a></li>
    <li role="presentation" class=""><a href="#ogtags" aria-controls="ogtags" role="tab" data-toggle="tab">Edit OG Tags</a></li>
    <li role="presentation" class=""><a href="#share" aria-controls="share" role="tab" data-toggle="tab">Upload Share Image</a></li>
  </ul>

  <!-- TAB CONTENT -->

  <!-- SOCIAL MEDIA TAB -->
  <div role="tabpanel" id="social" class="tab-pane active">
    <div class="panel panel-white">
      <div class="panel-body">
        @foreach ($socials as $social)

          @if (($social->tag == 'og_title') || ($social->tag == 'og_type') || ($social->tag == 'og_image') || ($social->tag == 'og_url'))
          <!-- Do nothing -->
          @else

            <div class="form-group form-inline">

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

			  <input class="form-control" type="text" value="{{ $social->url }}" tag="{{ $social->tag }}" name="socialURL-{{ $social->id }}"/>
              <a class="delete-confirm" data-object="social media" href="{{ url('/admin/movies/'.$movie->id.'/edit/seo-social/'.$social->id.'/delete') }}"><i class="fa fa-minus icon-actions pull-right"></i></a>
            </div>

          @endif

        





        @endforeach
		
      </div>
      <a href="{{ url('/admin/movies/'.$movie->id.'/edit/seo-social/create') }}" class="btn btn-success">Add New Social Media</a>
    </div>
  </div>





  <!-- OG TAGS TAB -->
  <div role="tabpanel" id="ogtags" class="tab-pane">
    <div class="panel panel-white">
      <div class="panel-body">
        @foreach ($socials as $social)

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

              @if ((isset($socialMediaImage)) && ($social->tag == 'og_image'))
	           	<input class="form-control" type="text" value="{{ $socialMediaImage->path }}" name="{{ $social->tag }}"/>
	          @else
	          	<input class="form-control" type="text" value="{{ $social->url }}" name="{{ $social->tag }}"/>
	          @endif

              <input type="hidden" value="{{ $social->id }}" name="id-{{ $social->id }}"/>
              <i class="ti-pencil-alt icon-actions-clear"></i>

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
	                        @if (isset($socialMediaImage))
	                        	<img src="{{ asset($socialMediaImage->path) }}" width="180"/>
	                        @else
	                        	<img src="{{ asset('/admin-files/assets/images/image-empty.png') }}" width="180"/>
	                        @endif
                        </div>
                        <div class="col col-sm-2 col-actions">
	                        @if (isset($socialMediaImage))
                          <a class="delete-confirm" data-object="image" href="{{ url('/settings/'.$socialMediaImage->id.'/delete/image?movie_id='.$movie->id) }}">
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
          </div></div>