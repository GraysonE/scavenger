@extends ('master')

@section ('content')

@include ('movies.title')


<div class="container-fluid container-fullw block-reviews">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">Add Awards images as PNG depending on the front-end design. Reviews images are SVG files and can be added to the system <a href="#">here</a>:</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>


  <div class="row">
    <div class="col-sm-12">

      <form method="post" id="main-form" action="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/featured') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="panel panel-white panel-main">
          <div class="panel-body no-padding">
            <table class="table-sortable expandable">
              <tbody class="ui-sortable" data-type="featured">

                @foreach ($featuredContents as $featuredContent)

                <tr class="subpanel subpanel-review" id="{{$featuredContent->id}}" data-id="{{$featuredContent->id}}">
                  <td class="form-inline">
                    @if ($featuredContent->type == 'review')
                    <h4>Review Media Outlet</h4>

                    <?php
                      $outlets = array(
                        'aarp' => 'AARP',
                        'access-hollywood' => 'Access Hollywood',
                        'cbs-radio' => 'CBS Radio',
                        'chicago-sun-times' => 'Chicago Sun-Times',
                        'deadline' => 'Deadline',
                        'elle' => 'Elle',
                        'entertainment-weekly' => 'Entertainment Weekly',
                        'forbes' => 'Forbes',
                        'hitfix' => 'Hitfix',
                        'indiewire' => 'Indiewire',
                        'intouch-weekly' => 'In Touch Weekly',
                        'la-times' => 'Los Angeles Times',
                        'larry-king-now' => 'Larry King Now',
                        'new-york-daily-news' => 'New York Daily News',
                        'new-york-magazine' => 'New York Magazine',
                        'ny-observer' => 'New York Observer',
                        'ny-times' => 'New York Times',
                        'people-magazine' => 'People Magazine',
                        'people-pick' => 'People Pick',
                        'roger-ebert' => 'Roger Ebert',
                        'rolling-stone' => 'Rolling Stone',
                        'star' => 'Star',
                        'the-boston-globe' => 'The Boston Globe',
                        'the-examiner' => 'The Examiner',
                        'the-guardian' => 'The Guardian',
                        'the-hollywood-reporter' => 'The Hollywood Reporter',
                        'the-huffington-post' => 'The Huffington Post',
                        'the-new-york-times-critics-pick' => 'The New York Times Critics Pick',
                        'the-new-yorker' => 'The New Yorker',
                        'the-playlist' => 'The Playlist',
                        'the-village-voice' => 'The Village Voice',
                        'the-washington-post' => 'The Washington Post',
                        'time' => 'Time',
                        'usa-today' => 'USA Today',
                        'vanity-fair' => 'Vanity Fair',
                        'variety' => 'Variety',
                        'zeal-nyc' => 'Zeal NYC'
                      );
                    ?>
                    
                    <i class="ti-move handle"></i>
                    <div class="on input-group input-group-select">
                      <select class="form-control" name="name-{{$featuredContent->id}}" type="text" placehold="Outlet Name" data-orig-value="{{ $featuredContent->name }}"">
                        <option value="">- Select media outlet -</option>
                        @foreach ($outlets as $key=>$outlet)
                          @if ($featuredContent->name == $key)
                            <option value="{{ $key }}" selected>{{ $outlet }}</option>
                          @else
                            <option value="{{ $key }}">{{ $outlet }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                    
                    @elseif ($featuredContent->type == 'award')
                    
                      <h4>Award Name</h4>
                      <i class="ti-move handle"></i>
                      <input class="on" name="name-{{$featuredContent->id}}" type="text" placeholder="Outlet Name" data-orig-value="{{ $featuredContent->name }}" value="{{ $featuredContent->name }}"/>
                      
                    @endif
                    
                  </td>
                  <td class="">

                    @if ($featuredContent->type == 'review')
                    <h4>Author Credit</h4>
                    <input class="on form-control" name="url-{{$featuredContent->id}}" type="text" placeholder="John Doe" data-orig-value="{{ $featuredContent->url }}" value="{{ $featuredContent->url }}"/>
                    @endif


                  </td>

                  <td class="col-actions">
                    <br/>
                    <a><i class="ti-pencil icon-actions pull-left"></i></a>
                    <a class="delete-confirm" data-object="{{$featuredContent->type}}" href="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/featured/'.$featuredContent->id.'/delete') }}">
                      <i class="fa fa-close icon-actions pull-left"></i>
                    </a>
                  </td>




                  <td class="fullspan">
                    <!-- FILE UPLOADER WIDGET -->
                    <div class="file-uploader featured-uploader">
                      <div class="row">
                        
                        @if ($featuredContent->type == 'review')
                        
                          @if(!empty($featuredContent->name) && stripos($featuredContent->name, 'Example Name') === FALSE)
                            <img class="svg outlet-logo" data-path="{{ url('assets/images/logos') }}" data-src="{{ url('assets/images/logos/'.$featuredContent->name.'.svg') }}">
                          @else
                            <div class="outlet-logo" data-path="{{ url('assets/images/logos') }}"></div>
                          @endif
                        
                        @elseif ($featuredContent->type == 'award')

                          <div class="col-sm-10">
                            <!-- EMPTY FILE PREVIEW -->

                            @foreach ($featuredImages as $featuredImage)
                            @if ($featuredImage->site_location == $featuredContent->id)
                            <img src="{{ asset($featuredImage->path)  }}" class="imagePreview"/>
                            @endif
                            @endforeach

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
                        
                        @endif

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
                    <br/>
                    <a href="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/featured/create?type=review') }}" class="btn btn-success">Add New Review</a>
                    <br/>
                    <a href="{{ url('admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/featured/create?type=award') }}" class="btn btn-success">Add New Award</a>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>
      </form>

    </div>





    <!-- BACKGROUND CONTENT PANEL -->
    <div class="panel panel-white panel-file-upload">
      <div class="panel-heading">
        <h5 class="panel-title">Reviews &amp; Awards Block Background Content</h5>
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
        @include ('sections.colorpicker.edit')
      </div>
    </div>

  </div>
</div>
</div>
@stop