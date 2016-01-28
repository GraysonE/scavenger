@extends('master')

@section('content')

    <div class="container-fluid container-fullw page-login">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel-heading">
                    <h5 class="panel-title">SELECT WHICH ACCOUNT TO CONTROL:</h5>
                </div>

                @foreach($socialMediaAccounts as $sma)
                    <a class="panel accounts" href="{{ url('set-user/'.$sma->id) }}">
                        <div>{{$sma->account_type}}</div>
                        <br>
                        <div>{{$sma->screen_name}}</div>
                        <br>
                    </a>
                @endforeach

            </div>
        </div>
    </div>
@stop