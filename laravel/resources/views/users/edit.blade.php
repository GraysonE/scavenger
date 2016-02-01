<form method="post" action="{{ url('/users') }}" id="main-form">

  <table id="users_table" class="expandable" data-type="users">
    <thead>
      <tr>
        <th class="col-sm-3">Name</th>
        <th class="col-sm-3">Username</th>
        <th class="col-sm-3">Email</th>
        <th class="col-sm-2">Role</th>
        <th class="col-sm-1"></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr class="" data-id="{{ $user->id }}">
        <td class="col-sm-3">
          {{ csrf_field() }}
          <input class="on form-control" type="text" placeholder="First Name" value="{{ $user->first_name }}" name="first_name-{{ $user->id }}"/>
          <input class="on form-control" type="text" placeholder="Last Name" value="{{ $user->last_name }}" name="last_name-{{ $user->id }}"/>
        </td>
        <td class="col-sm-3">
          <input class="on form-control" type="text" placeholder="Username" value="{{ $user->username }}" name="username-{{ $user->id }}"/>
        </td>
        <td class="col-sm-3">
          <input class="on form-control" type="text" placeholder="Email" value="{{ $user->email }}" name="email-{{ $user->id }}"/>
        </td>
        <td class="col-sm-2">
          <div class="form-group form-group-role on">
            <div class="input-group input-group-select">
              <select name="role-{{ $user->id }}" class="form-control">

                <option value="">- Role -</option>

                <?php
                if ($user->role == 0)
                {
                  $selected = 'selected';
                } else
                {
                  $selected = '';
                }
                ?>

                <option value="0" {{ $selected }}>Administrator</option>

                <?php
                if ($user->role == 1)
                {
                  $selected = 'selected';
                } else
                {
                  $selected = '';
                }
                ?>

                <option value="1" {{ $selected }}>Editor</option>
              </select>
            </div>
          </div>
        </td>
        <td class="col-sm-1 col-actions">
          <div>
            <!--a href="#"><i class="ti-pencil-alt icon-actions-clear pull-left"></i></a-->
            <a class="delete-confirm" data-object="user" href="{{ url('auth/register/'.$user->id.'/delete') }}"><i class="fa fa-close icon-actions pull-left"></i></a>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>


  <!--div class="form-group">
    <button class="btn btn-success" type="submit">Save Users</button>
  </div-->

</form>