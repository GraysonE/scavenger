<div class="panel">
  <div class="panel-heading">
    <h5 class="panel-title">Add a new content block</h5>
  </div>
  <div class="panel-body">
    <form id="addBlockForm" method="POST" class="form-inline" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/new') }}">
      {!! csrf_field() !!}

      <div class="form-group">
        <div class="input-group input-group-select">
          <select name="content_block" class="form-control form-select-block-types">
            <option value="">Choose content block</option>

            <option value="sections.contentblocks.mainmarquee">Marquee & CTA</option>

            <option value="sections.contentblocks.ticketing">Ticketing</option>

            <option value="sections.contentblocks.videos">Videos</option>

            <option value="sections.contentblocks.aboutthefilm">About the Film</option>

            <option value="sections.contentblocks.partners">Partners</option>

            <option value="sections.contentblocks.gallery">Gallery</option>

            <option value="sections.contentblocks.featuredcontent">Featured Content</option>

            <option value="sections.contentblocks.reviewsandawards">Reviews and awards</option>

            <option value="sections.contentblocks.releasedates">Release Dates</option>
            
            <option value="sections.contentblocks.moviefooter">Movie Footer</option>

          </select>
          
          <div id="custom_block_dialog">
            <h4>Choose the layout structure of the custom content block you want to add to your site:</h4>
            <input type="radio" name="custom_type" id="image_and_text" value="image_and_text"/>
            <label for="image_and_text">
              <img src="{{ asset('/admin-files/assets/images/custom_type_1.png') }}"/>
              Image and simple text
            </label>
            <input type="radio" name="custom_type" id="complex_carousel" value="complex_carousel"/>
            <label for="complex_carousel">
              <img src="{{ asset('/admin-files/assets/images/custom_type_2.png') }}"/>
              Complex carousel
            </label>
            <input type="radio" name="custom_type" id="large_image_content_left" value="large_image_content_left"/>
            <label for="large_image_content_left">
              <img src="{{ asset('/admin-files/assets/images/custom_type_3.png') }}"/>
              Larger image, content left
            </label>
            <input type="radio" name="custom_type" id="large_image_content_right" value="large_image_content_right"/>
            <label for="large_image_content_right">
              <img src="{{ asset('/admin-files/assets/images/custom_type_4.png') }}"/>
              Larger image, content right
            </label>
          </div>
          

        </div>
      </div>

      <div class="form-group">
        <div class="input-group input-group-blockTitle">
          <input class="form-control" type="text" name="title" placeholder="Custom Header Text">
        </div>
      </div>
      <input type="hidden" name="movie_id" value="{{ $movie->id }}">
      <button class="btn btn-success" type="submit">Add Content Block</button>
    </form>
  </div>
</div>