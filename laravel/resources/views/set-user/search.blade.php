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
                        <ul class="ui-sortable expandable" data-type="model_users">
                    @foreach($modelAccounts as $model)
	                        <li class="subpanel form-inline" data-id="{{ $model->id }}">
	                        	
	                            <h4 class="screen_name pull-left">
		                            <i class="ti-move handle"></i>
		                            {{ '@'.$model->screen_name }} 
		                            <span>{{ ($model->api_cursor) ? '- Queued' : '- Finished'}}</span>
		                        </h4>
	                            
	                            <a class=" pull-right" href="{{ url('set-user/'.$socialMediaAccount->id.'/'.$model->id.'/destroy') }}">
		                            <i class="fa fa-minus-circle handle fa-lg"></i>
		                        </a>
	                        </li>
                        
                    @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>
@stop