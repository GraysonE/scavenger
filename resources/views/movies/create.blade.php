<div class="panel">
  <div class="panel-heading">
    <h5 class="panel-title">Add a new movie</h5>
  </div>
  <div class="panel-body">

    <form method="POST" action="{{ url('/admin/movies') }}" class="addMovieForm form-inline">
      {!! csrf_field() !!}

      <div class="form-group">
        <div class="input-group">
          <input type="text" name="title" placeholder="Enter movie title" class="form-control">
        </div>
      </div>

      <div class="form-group">
        <div class="on input-group input-append datepicker date">
          <input type="text" name="release_date" readonly="readonly" class="form-control" placeholder="Pick release date (mm/dd/yyyy)"/>
          <span class="input-group-btn">
            <button class="btn btn-default" type="button">
              <i class="glyphicon glyphicon-calendar"></i>
            </button>
          </span>
        </div>
      </div>

      <button class="btn btn-success" type="submit">Add Movie</button>

    </form>

  </div>
</div>