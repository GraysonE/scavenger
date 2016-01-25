@extends('master')

@section('content')

    <div class="container-fluid container-fullw page-login">
        <div class="row">
            <div class="col-sm-12">

                <form id="main-form" method="POST" action="{{url('set-user/'.$socialMediaAccount->id.'/search')}}">
                    {!! csrf_field() !!}

                        <div class="form-group">
                            <input name="model_user" placeholder="Search a user to follow. . ." />
                        </div>
                        <div class="form-group">
                            <input class="btn btn-success" type="submit" value="Search Users"/>
                        </div>
                    </div>
                </form>

                @if (isset($modelAccounts))
                    <ul class="ui-sortable expandable search_users" data-type="model_users">
                        @foreach($modelAccounts as $model)
                            <li class="subpanel form-inline" data-id="{{ $model->id }}">
                                <h3 class="screen_name col-lg-6">{{ '@'.$model->screen_name }} - {{ ($model->api_cursor == 0) ? 'Finished.' : 'Queued.' }}</h3>
                                <a class="btn alert-danger pull-right" href="{{ url('set-user/'.$socialMediaAccount->id.'/'.$model->id.'/destroy') }}">Delete User</a>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>
@stop