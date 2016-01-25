@extends('master')

@section('content')



    <div class="container-fluid container-fullw page-login">
        <div class="row">
            <div class="col-sm-12">

                    @foreach($targetUserFollowed as $target)
                        <div class="panel">
                            <br><span>{{$target}}</span>
                        </div>
                    @endforeach

            </div>

        </div>





    </div>
@stop