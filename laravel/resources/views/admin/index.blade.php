@extends('master')
@section('content')
<!-- start: DASHBOARD TITLE -->
<section id="page-title" class="">
  <div class="row">
    <div class="col-sm-12">
      <h1>All Movies</h1>
      	<span class="breadcrumb">&nbsp;</span>
    </div>
  </div>
</section>
<!-- end: DASHBOARD TITLE -->
	@include('movies.show')
@stop