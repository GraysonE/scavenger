
<div class="panel-body">
  <div class="form-group form-inline">
    <label for="analytics_desktop">Google Desktop</label>
    <input class="form-control" type="text" value="{{ $movie->google_analytics_id }}" name="google_analytics_id"/>
    <i class="ti-pencil-alt icon-actions-clear"></i>
  </div>
  <div class="form-group form-inline">
    <label for="analytics_mobile">Google Mobile</label>
    <input class="form-control" type="text" value="{{ $movie->mobile_google_analytics_id }}" name="mobile_google_analytics_id"/>
    <i class="ti-pencil-alt icon-actions-clear"></i>
  </div>
</div>
