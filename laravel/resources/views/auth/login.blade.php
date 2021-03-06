@extends('master')

@section('content')

<form method="POST" action="{{ url('auth/login') }}">
  {!! csrf_field() !!}
  <div class="container-fluid container-fullw block-about page-login">
    <div class="row">
      <div class="col-sm-12">
        <div class="panel">
          <div class="panel-heading">
            <h5 class="panel-title">LOG IN:</h5>
          </div>

          <div class="panel-body">            
            <div>
              <input type="text" name="username" placeholder="username" id="username" class="form-control">
            </div>

            <div>
              <input type="password" name="password" placeholder="password" id="password" class="form-control">
            </div>

            <div class="form-inline">
              <input type="checkbox" name="remember">
              <label for="remember">Remember me</label>
            </div>

            <div>
              <button type="submit" class="btn btn-success form-control">Login</button>
            </div>
            
            <div>
              <br/><br/>
              <p><small><a href="{{ url('/password/email') }}">Forgot your password?</a></small></p>
              <p><small><a href="{{ url('/auth/register') }}">Register</a></small></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>


@stop