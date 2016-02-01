@extends('master')

@section('content')

    

    <div class="container-fluid container-fullw page-login">
        <div class="row">
            <div class="col-sm-12">

                <form id="main-form" method="POST" action="{{ url('accounts') }}">
                    {!! csrf_field() !!}
                    @foreach($socialMediaAccounts as $sma)
                        <div class="panel user_accounts" {{($sma->account_type == 'twitter') ? "style=background:#0084b4;" : ''}}>
	                        <h4><input type="text" {{($sma->account_type == 'twitter') ? "style=background:#0084b4;" : ''}} name="screen_name-{{$sma->id}}" value="{{$sma->screen_name}}" placeholder="Screen Name"/></h4>
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
                    
                    @include('admin.publish')
                </form>


                @include('accounts.create')

            </div>
        </div>
    </div>
@stop