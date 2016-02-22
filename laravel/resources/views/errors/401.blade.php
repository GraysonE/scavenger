@extends('master')

@section('content')

	<h3>HTTP Error Code: {!! $http_code !!}</h3>
	<h4>You are not authorized. Your API access token may need to be regenerated. Contact Grayson.</h4> 

@stop