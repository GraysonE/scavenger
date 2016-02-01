<form method="GET" action="{{ url("accounts/create") }}">
    {!! csrf_field() !!}

    <div class="panel panel-white">

        <div class="panel-heading">
            <h5 class="panel-title">REGISTER A NEW ACCOUNT:</h5>
        </div>

        <div class="panel-body">
            <div class="form-group">
                <input class="form-control" name="account_type" placeholder="account type" value="{{ old('account_type') }}">
            </div>

            <div class="form-group">
                <input class="form-control" name="screen_name" placeholder="social media account username" id="username" value="{{ old('screen_name') }}">
            </div>

            <div class="form-group">
                <input class="form-control" name="account_password" placeholder="social media account password" value="{{ old('account_password') }}">
            </div>

            <div class="form-group">
                <input class="form-control" name="consumer_key" placeholder="API: consumer key" value="{{ old('consumer_key') }}">
            </div>

            <div class="form-group">
                <input class="form-control" name="consumer_secret" placeholder="API: consumer secret" value="{{ old('consumer_secret') }}">
            </div>

            <div class="form-group">
                <input class="form-control" name="access_token" placeholder="API: access token" value="{{ old('access_token') }}">
            </div>

            <div class="form-group">
                <input class="form-control" name="access_token_secret" placeholder="API: access token secret" value="{{ old('access_token_secret') }}">
            </div>


            <input type="hidden" name="role" value="0">

            <div class="form-group">
                <button class="btn btn-success" type="submit">Register Account</button>
            </div>
        </div>

    </div>
</form>