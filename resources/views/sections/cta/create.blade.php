
<div class="panel-heading">
  <h5 class="panel-title">Add a Call to Action buttons</h5>
</div>

<div class="panel-body">
  <form action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/cta') }}" method="post" class="form-inline" id="addCTAForm">
    {!! csrf_field() !!}

    <div class="form-group form-group-title">
      <input class="form-control" type="text" name="text" id="buttonTextInput" placeholder="Button Text">
    </div>

    <div class="form-group form-group-link">    
      <div class="input-group">
        <input class="form-control" type="text" name="url" id="extURLInput" placeholder="External URL">
        <input type="hidden" name="new" value="1" />
      </div>

      <span>or</span>

      <div class="input-group input-group-select">
        <select name="anchor" class="form-control">
          <option value="">- Link to content block -</option>
          @foreach ($sections as $section)
            <option value="{{ $section->id }}">{{ $section->title }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <button  class="btn btn-success" type="submit">Add CTA Button</button>

  </form>
</div>