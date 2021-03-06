<!-- start: MAIN NAVIGATION MENU -->            
<ul class="main-navigation-menu global">
  
  
  @if (Auth::user() != NULL)
  	  <li class="active">
	    <a href="{{ url('/set-user/select') }}">
	      <div class="item-content">
	        <div class="item-inner">
	          <span class="title"> Set User </span>
	        </div>
	      </div>
	    </a>
	  </li>
  
	  @if (Auth::user()->id == 1)
	  
	  <li class="active">
	    <a href="{{ url('/accounts') }}">
	      <div class="item-content">
	        <div class="item-inner">
	          <span class="title"> Accounts </span>
	        </div>
	      </div>
	    </a>
	  </li>
	  
	  <li class="active">
	    <a href="{{ url('/friends') }}">
	      <div class="item-content">
	        <div class="item-inner">
	          <span class="title"> Get Friends </span>
	        </div>
	      </div>
	    </a>
	  </li>
	  
	  <li class="active">
	    <a href="{{ url('/followers') }}">
	      <div class="item-content">
	        <div class="item-inner">
	          <span class="title"> Get Followers </span>
	        </div>
	      </div>
	    </a>
	  </li>
	  
	  <li class="active">
	    <a href="{{ url('/copy') }}">
	      <div class="item-content">
	        <div class="item-inner">
	          <span class="title"> Copy </span>
	        </div>
	      </div>
	    </a>
	  </li>
	  
	  <li class="active">
	    <a href="{{ url('/filter') }}">
	      <div class="item-content">
	        <div class="item-inner">
	          <span class="title"> Filter </span>
	        </div>
	      </div>
	    </a>
	  </li>
	  
	  <li class="active">
	    <a href="{{ url('/follow') }}">
	      <div class="item-content">
	        <div class="item-inner">
	          <span class="title"> Follow </span>
	        </div>
	      </div>
	    </a>
	  </li>
	  
	  <li class="active">
	    <a href="{{ url('/unfollow') }}">
	      <div class="item-content">
	        <div class="item-inner">
	          <span class="title"> Unfollow </span>
	        </div>
	      </div>
	    </a>
	  </li>
	  
	  @endif
  @endif
<!--
  <li class="active">
    <a href="{{ url('/settings') }}">
      <div class="item-content">
        <div class="item-inner">
          <span class="title"> Settings </span>
        </div>
      </div>
    </a>
  </li>
-->
</ul>


<!-- end: MAIN NAVIGATION MENU -->