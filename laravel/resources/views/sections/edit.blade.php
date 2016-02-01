<!-- OVERVIEW SECTION -->

<div class="container-fluid container-fullw">
  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">These are the content blocks in use for this movie. Drag to reorder, click to edit the content or add a new block at the bottom of this list:</p>
    </div>

    @if (Auth::user() && $editMovie)
      @include ('admin.publish')
    @endif
  </div>
</div>

<div class="container-fluid container-fullw bg-white block-overview">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel">
        <div class="panel-body">
          <form id="main-form" method="post" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/update') }}" class="sectionForm">

            {{ csrf_field() }}

            <ul class="ui-sortable expandable" data-type="sections">

              @foreach ($sections as $section)

              <li class="section_ids" data-id="{{ $section->id }}">
                <i class="ti-move handle"></i>
                <input class="on form-control" type="text" placeholder="Custom Header Text" class="sectionTitle" value="{{ $section->title }}" name="title-{{ $section->id }}"/>

              <a href="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$section->id.'/delete') }}" data-object="content block" class="delete-confirm"><i class="fa fa-close icon-actions pull-right"></i></a>

              </li>

              @endforeach

            </ul>

          </form>

        </div> <!-- .panel-body -->
      </div> <!-- .panel -->
    </div>
  </div>
  
  <div class="row">
  <div class="col-sm-12">
    @include ('sections.create')
  </div>
  </div>

</div>
