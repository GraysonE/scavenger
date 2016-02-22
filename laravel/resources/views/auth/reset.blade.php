@extends ('master')

@section('content')

<form method="POST" action="{{ url('password/reset') }}">
    {!! csrf_field() !!}
  <div class="container-fluid container-fullw page-login">
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-white">
          
          <div class="panel-heading">
            <h5 class="panel-title">RESET YOUR PASSWORD:</h5>
          </div>

          <div class="panel-body">

            <input type="hidden" name="token" value="{{ $token }}">

            @if (count($errors) > 0)
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="form-group">
                Email
                <input type="email" name="email" value="{{ old('email') }}" class="form-control margin-bottom-15">
            </div>

            <div class="form-group">
                Password
                <input type="password" name="password" class="form-control margin-top-0 margin-bottom-15">
            </div>

            <div class="form-group">
                Confirm Password
                <input type="password" name="password_confirmation" class="form-control margin-top-0">
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    Reset Password
                </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

@stop