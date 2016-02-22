@extends ('master')

@section('content')

<form method="POST" action="{{ url("/auth/register") }}">
  {!! csrf_field() !!}
  <div class="container-fluid container-fullw page-login">
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-white">
          
          <div class="panel-heading">
            <h5 class="panel-title">REGISTER A NEW USER:</h5>
          </div>

          <div class="panel-body">
            <div class="form-group">
              <input class="form-control" name="username" placeholder="Username" id="username" value="{{ old('username') }}">
            </div>

            <div class="form-group">
              <input class="form-control" name="email" placeholder="Email" id="email" value="{{ old('email') }}">
            </div>

            <div class="form-group">
              <input class="form-control" name="first_name" placeholder="First Name" id="first_name" value="{{ old('first_name') }}">
            </div>

            <div class="form-group">
              <input class="form-control" name="last_name" placeholder="Last Name" id="last_name" value="{{ old('last_name') }}">
            </div>

            <div class="form-group">
              <input class="form-control" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
            </div>

            <div class="form-group">
              <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password">
            </div>

            <input type="hidden" name="role" value="0">

            <div class="form-group">
              <button class="btn btn-success" type="submit">Register User</button>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</form>

@stop