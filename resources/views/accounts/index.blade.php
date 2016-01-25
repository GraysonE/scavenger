@extends('master')

@section('content')



    <div class="container-fluid container-fullw page-login">
        <div class="row">
            <div class="col-sm-12">

                <form id="main-form" method="POST" action="{{ url('accounts') }}">
                    {!! csrf_field() !!}
                    @include('admin.publish')
                    @foreach($socialMediaAccounts as $sma)
                        <div class="panel accounts">
                            <div class="form-group">
                                <input name="screen_name-{{$sma->id}}" value="{{$sma->screen_name}}" placeholder="Screen Name"/>
                            </div>
                            <div class="form-group">
                                <input name="account_type-{{$sma->id}}" value="{{$sma->account_type}}" placeholder="Account Type"/>
                            </div>
                            <div class="form-group">
                                <input name="account_id-{{$sma->id}}" value="{{$sma->account_id}}" placeholder="Account ID"/>
                            </div>
                            <label>Auto-Follow:</label>
                            <input type="checkbox" name="auto_follow-{{$sma->id}}" value="1" {{ ($sma->auto_follow) ? 'checked' : '' }}/>
                            <br>
                            <label>Auto-Unfollow:</label>
                            <input type="checkbox" name="auto_unfollow-{{$sma->id}}" value="1" {{ ($sma->auto_unfollow) ? 'checked' : '' }}/>
                            <br>
                            <label>Auto-Whitelist:</label>
                            <input type="checkbox" name="auto_whitelist-{{$sma->id}}" value="1" {{ ($sma->auto_whitelist) ? 'checked' : '' }}/>
                            <br>
                            <br>
                            <a class="btn btn-danger" href="{{ url('accounts/destroy/'.$sma->id) }}">Delete Account</a>
                        </div>
                    @endforeach


                </form>


            </div>

        </div>


        @include('accounts.create')



    </div>
@stop