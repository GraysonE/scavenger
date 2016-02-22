@extends('master')

@section('content')

<form method="POST" action="{{ url('password/email') }}">
  {!! csrf_field() !!}

  @if (count($errors) > 0)
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
  @endif

  @if (session('status'))
    <div class="alert alert-success">
      {{ session('status') }}
    </div>
  @endif

  <div class="container-fluid container-fullw page-login">
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-white">
          <div class="panel-heading">
            <h5 class="panel-title">RESET PASSWORD:</h5>
          </div>

          <div class="panel-body">  
            <div>
              Email
              <input type="email" name="email" value="{{ old('email') }}" class="form-control">
            </div>

            <div>
              <button type="submit" class="btn btn-success">
                Send Password Reset Link
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

@stop