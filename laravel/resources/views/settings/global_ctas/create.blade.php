
<form method="post" action="{{ url('settings') }}" id="globalCTAForm">
  {{ csrf_field() }}

  <div class="form-group">
    <input type="text" placeholder="Link Text" name="text" class="form-control">
  </div>
  <div class="form-group">
    <input type="text" placeholder="URL" name="url" class="form-control">
  </div>

  <div class="form-group">
    <div class="input-group input-group-select">
      <select name="" class="form-control">
        <option value="">- Target -</option>
        <option value="_self">_self</option>
        <option value="_blank">_blank</option>
      </select>
    </div>
  </div>

<input type="hidden" value="{{ $column }}" name="column" />

<button class="btn btn-success" type="submit">Add Column {{ $column }} Footer Link</button>

</form>
