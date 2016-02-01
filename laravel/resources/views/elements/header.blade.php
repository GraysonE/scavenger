<!-- start: NAVBAR HEADER -->
<div class="navbar-header">
  <a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg" class="btn btn-navbar sidebar-toggle" data-toggle-class="app-slide-off" data-toggle-target="#app" data-toggle-click-outside="#sidebar">
    <i class="ti-align-justify"></i>
  </a>
  <img src="{{ asset('/assets/images/ghost-loading.gif') }}" alt="Scavenger"/>
  <a class="navbar-brand" href="{{ url('/') }}">
   
   	<h1>Scavenger</h1>
<!--     <h5>Social Media Management System</h5> -->
  </a>

  <a class="pull-right menu-toggler visible-xs-block" id="menu-toggler" data-toggle="collapse" href=".navbar-collapse">
    <span class="sr-only">Toggle navigation</span>
    <i class="ti-view-grid"></i>
  </a>
</div>
<!-- end: NAVBAR HEADER -->
<!-- start: NAVBAR COLLAPSE -->
<div class="navbar-collapse collapse">
  <ul class="nav navbar-right">
    <!-- start: USER OPTIONS DROPDOWN -->
    <li class="dropdown current-user">
      <a href class="dropdown-toggle" data-toggle="dropdown">
        <span class="username">
          @if (isset($currentAccount)) 
          	{{ $currentAccount }}
          @endif
        </span><i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-dark">
        <li>
          <a href="{{url('auth/logout')}}">
            Log Out
          </a>
        </li>
      </ul>
    </li>
    <!-- end: USER OPTIONS DROPDOWN -->
  </ul>
  <!-- start: MENU TOGGLER FOR MOBILE DEVICES -->
  <div class="close-handle visible-xs-block menu-toggler" data-toggle="collapse" href=".navbar-collapse">
    <div class="arrow-left"></div>
    <div class="arrow-right"></div>
  </div>
  <!-- end: MENU TOGGLER FOR MOBILE DEVICES -->
</div>
<!-- end: NAVBAR COLLAPSE -->