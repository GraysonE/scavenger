@extends('master')

@section('content')

    

    <div class="container-fluid container-fullw page-login">

		@include('accounts.create')

        <form id="main-form" method="POST" action="{{ url('accounts') }}">
	        @include('admin.publish')
            {!! csrf_field() !!}
            <div class="panel col-lg-12" id="account_accordion">
                @foreach($socialMediaAccounts as $sma)
                        <h3 class="{{($sma->account_type == 'crunch') ? 'crunch_account' : 'twitter_account'}}"><input type="text" name="screen_name-{{$sma->id}}" value="{{$sma->screen_name}}" placeholder="Screen Name"/></h3>
                    <div>
                        <br>
<!--
                        <input name="account_id-{{$sma->id}}" value="{{$sma->account_id}}" placeholder="Account ID"/>
                        <br>
-->
                        <label>Auto-Follow:</label>
                        <input type="checkbox" name="auto_follow-{{$sma->id}}" value="1" {{ ($sma->auto_follow) ? 'checked' : '' }}/>
                        <br>
                        <label>Auto-Unfollow:</label>
                        <input type="checkbox" name="auto_unfollow-{{$sma->id}}" value="1" {{ ($sma->auto_unfollow) ? 'checked' : '' }}/>
                        <br>
                        <label>Auto-Whitelist:</label>
                        <input type="checkbox" name="auto_whitelist-{{$sma->id}}" value="1" {{ ($sma->auto_whitelist) ? 'checked' : '' }}/>
                        <br>
                        <a class="btn btn-danger" href="{{ url('accounts/destroy/'.$sma->id) }}">Delete Account</a>
                    </div>
                @endforeach
        	</div>
            
        </form>


        

    </div>
@stop