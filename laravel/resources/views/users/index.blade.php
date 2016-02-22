@extends ('master')

@section ('content')

<!-- start: DASHBOARD TITLE -->
<section id="page-title" class="">
  <div class="row">
    <div class="col-sm-12">
      <h1>Users</h1>
      <span class="breadcrumb"></span>
    </div>
  </div>
</section>
<!-- end: DASHBOARD TITLE -->


<!-- USERS SECTION -->
<div class="container-fluid container-fullw page-users">

  <div class="row page-description">
    <div class="col-sm-12" id="publishSideBar">
      <p class="main-description"></p>
      <button type="submit" class="pull-right btn btn-orange">SAVE SETTINGS</button>
    </div>
  </div>
  

  <div class="row">
    <div class="col-sm-12">

      <!-- USERS FORM -->
      <div class="panel panel-white">
        <div class="panel-body">
        @include ('users.edit')
        </div>

        <br/>

        <div class="panel-heading">
          <h5 class="panel-title">Add a new user</h5>
        </div>
        
        <div class="panel-body">
        @include ('users.create')
        </div>

      </div>

    </div>
  </div>
</div>
@stop