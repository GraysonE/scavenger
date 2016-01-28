<!-- start: DASHBOARD TITLE -->
<section id="page-title" class="">
  <div class="row">
    <div class="col-sm-12">
      <h1>{{ $movie->title }}</h1>
      <span class="breadcrumb">
        Content Blocks > 
        @if (isset($currentSection))

        {{ $currentSection->title }} 

        @else

        Overview

        @endif
      </span>
    </div>
  </div>
</section>
<!-- end: DASHBOARD TITLE -->