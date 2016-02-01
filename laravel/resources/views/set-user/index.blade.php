@extends('master')

@section('content')

    <div class="container-fluid container-fullw page-login">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel-heading">
                    <h5 class="panel-title">SELECT WHICH ACCOUNT TO CONTROL:</h5>
                </div>

                @foreach($socialMediaAccounts as $sma)
                    <a class="panel user_accounts" href="{{ url('set-user/'.$sma->id) }}" {{($sma->account_type == 'twitter') ? "style=background:#0084b4;" : ''}}>
                        <h4>{{$sma->screen_name}}</h4>
                    </a>
                @endforeach

            </div>
        </div>
    </div>
@stop