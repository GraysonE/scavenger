@extends('master')

@section('content')

    <div class="container-fluid container-fullw page-login">
        <div class="row">
            <div class="col-sm-12">

                @if (isset($searchJson))
                    @foreach($searchJson as $search)
                        <div class="panel">
                            <form method="GET" action="{{ url('set-user/'.$socialMediaAccount->id.'/'.$search->id) }}">
                                <img class="account_photo" src="{{$search->profile_image_url}}" />
                                <h3 class="screen_name">{{ '@'.$search->screen_name }}</h3><br>
                                <h4>{{$search->name}}</h4>
                                <strong>Followers:</strong> {{$search->followers_count}}<br>
                                <strong>Location:</strong> {{$search->location}}<br>
                                <strong>Description:</strong> {{$search->description}}<br>
                                <input type="hidden" name="search" value="{{json_encode($search)}}"/>
                                <input class="btn btn-success" type="submit" value="Set User"/>
                            </form>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
@stop