@extends('master')

@section('content')

    <div class="container-fluid container-fullw page-login">
        <div class="row">
            <div class="col-sm-12">

                <form id="main-form" method="POST" action="{{url('set-user/'.$socialMediaAccount->id.'/search')}}">
                    {!! csrf_field() !!}
                        <input name="model_user" placeholder="Search a user to follow. . ." />
                        <br>
                        <input class="btn btn-success" type="submit" value="Search Users"/>
                </form>

                @if (isset($modelAccounts))
                    @foreach($modelAccounts as $model)
                        <ul class="ui-sortable expandable" data-type="model_users">
                        <li class="subpanel form-inline" data-id="{{ $model->id }}">
                            <h3 class="screen_name col-lg-6">{{ '@'.$model->screen_name }}</h3>
                            <a class="btn alert-danger pull-right" href="{{ url('set-user/'.$socialMediaAccount->id.'/'.$model->id.'/destroy') }}">Delete User</a>
                        </li>
                        </ul>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
@stop