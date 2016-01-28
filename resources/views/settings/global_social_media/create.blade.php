<form method="post" action="">
  {{ csrf_field() }}

  <div class="form-group">
    <input type="text" name="tag" placeholder="Company" class="form-control"/>
  </div>
  <div class="form-group">
    <input type="text" name="url" placeholder="http://instagram.com/company" class="form-control">
  </div>

<button class="btn btn-success" type="submit">Add Social Media</button>

</form>