@extends ('master')

@section ('content')



<!-- start: DASHBOARD TITLE -->
<section id="page-title" class="">
  <div class="row">
    <div class="col-sm-12">
      <h1>Global Settings</h1>
    </div>
  </div>
</section>
<!-- end: DASHBOARD TITLE -->



<!-- SETTINGS SECTION -->
<div class="container-fluid container-fullw page-settings">

  <div class="row page-description">    
    @include('admin.publish')
  </div>


  <div class="row">
    <div class="col-sm-12">

      <form method="post" action="{{ url('/settings') }}" id="main-form" enctype="multipart/form-data">
        {{ csrf_field() }}

        
      </form>



    </div>
  </div>
</div>




@stop
