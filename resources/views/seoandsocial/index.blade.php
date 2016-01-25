@extends ('master')

@section ('content')

<!-- start: DASHBOARD TITLE -->
<section id="page-title" class="">
  <div class="row">
    <div class="col-sm-12">
      <h1>Secret In Their Eyes</h1>
      <span class="breadcrumb">
        Content Blocks > SEO &amp; Social
      </span>
    </div>
</section>
<!-- end: DASHBOARD TITLE -->

<!-- MARQUEE SECTION -->
<div class="container-fluid container-fullw block-seo">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">[ text here ]</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>

  <div class="row">
    <div class="col-sm-12">
      <form method="post" action="{{ url('/admin/movies/'.$movie->id.'/edit/seo-social') }}" id="main-form" enctype="multipart/form-data">
        {{ csrf_field()}}

        <!-- META TAGS FORM -->
        <div class="panel panel-white">
          <div class="panel-heading">
            <div class="panel-title">Meta tags</div>
          </div>

          @include ('seoandsocial.metatags.edit')



        </div>

        <!-- SOCIAL FORM -->

        @include ('seoandsocial.socialmedia.edit')

        <!-- ANALYTICS FORM -->
        <div class="panel panel-white">
          <div class="panel-heading">
            <div class="panel-title">Analytics</div>
          </div>

          @include ('seoandsocial.googleanalytics.edit')

        </div>

        <!--button type="submit">Save All Changes</button-->
      </form>
    </div>

  </div>
</div>



@stop
