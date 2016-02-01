<form method="POST" action="{{ url('/auth/register') }}" id="add_user_form">
  {!! csrf_field() !!}

  
  <div class="form-group form-inline">
    <input class="form-control" type="text" name="first_name" placeholder="First Name" id="first_name" value="{{ old('first_name') }}">
    <input class="form-control" type="text" name="last_name" placeholder="Last Name" id="last_name" value="{{ old('last_name') }}">
  </div>

  <div class="form-group form-inline">
    <input class="form-control" type="text" name="username" placeholder="Username" id="username" value="{{ old('username') }}">
    <input class="form-control" type="text" name="email" placeholder="Email" id="email" value="{{ old('email') }}">
  </div>

  <div class="form-group form-inline">
    <input class="form-control" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
    <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password">
  </div>

  <div class="input-group input-group-select form-group">
    <select name="role" class="form-control">
      <option value="">- Choose Role -</option>
      <option value="0">Administrator</option>
      <option value="1">Editor</option>
    </select>
  </div>

  <input class="form-control" type="hidden" name="userView" value="1">
  
  <button class="btn btn-success" type="submit">Register User</button>

</form>