@extends ('master')

@section ('content')



<!-- start: DASHBOARD TITLE -->
<section id="page-title" class="">
  <div class="row">
    <div class="col-sm-12">
      <h1>Global STX Settings</h1>
      <span class="breadcrumb">
        (not movie specific)
      </span>
    </div>
  </div>
</section>
<!-- end: DASHBOARD TITLE -->



<!-- SETTINGS SECTION -->
<div class="container-fluid container-fullw page-settings">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">Global settings for all pages:</p>
    </div>
    <div id="publishSideBar" class="col-sm-4 col-lg-3 page-form-actions">
      <button type="submit" name="" class="btn btn-orange">SAVE ALL LINKS AND SOCIAL MEDIA</button>
    </div>
  </div>


  <div class="row">
    <div class="col-sm-12">

      <form method="post" action="{{ url('/settings') }}" id="main-form" enctype="multipart/form-data">
        {{ csrf_field() }}

        <!-- FOOTER LINKS COLUMN 1 FORM -->
        <div class="panel panel-white">
          <div class="panel-heading">
            <div class="panel-title">Footer Links - Column 1</div>
          </div>
          <div class="panel-body">

            <table class="table-sortable expandable">
              <thead>
                <tr>
                  <th class="col-sm-4">Link Text</th>
                  <th class="col-sm-4">URL</th>
                  <th class="col-sm-2">Target</th>
                  <th class="col-sm-2"></th>
                </tr>
              </thead>
              <tbody class="ui-sortable" data-type="setting">
			  	
                @include ('settings.global_ctas.edit', array('column' => '1'))
				
				<tr>
					<td class="pull-right">
						<a href="{{ url('/settings/new/cta/1') }}" class="btn btn-success">Add New Footer Link</button>
					</td>
				</tr>
				
              </tbody>
            </table>
          </div>
        </div>



        <!-- FOOTER LINKS COLUMN 2 FORM -->
        <div class="panel panel-white">
          <div class="panel-heading">
            <div class="panel-title">Footer Links - Column 2</div>
          </div>
          <div class="panel-body">

            <table class="table-sortable expandable">
              <thead>
                <tr>
                  <th class="col-sm-4">Link Text</th>
                  <th class="col-sm-4">URL</th>
                  <th class="col-sm-2">Target</th>
                  <th class="col-sm-2"></th>
                </tr>
              </thead>
              <tbody class="ui-sortable" data-type="setting">

                @include ('settings.global_ctas.edit', array('column' => '2'))
				<tr>
					<td class="pull-right">
						<a href="{{ url('/settings/new/cta/2') }}" class="btn btn-success">Add New Footer Link</button>
					</td>
				</tr>
              </tbody>
            </table>
          </div>

        </div>



        <!-- SOCIAL FORM -->
        <div class="tab-content block-seo" id="tabs">
          <!-- TABS -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#social" aria-controls="social" role="tab" data-toggle="tab">Social media</a></li>
            <li role="presentation" class=""><a href="#ogtags" aria-controls="ogtags" role="tab" data-toggle="tab">Edit OG tags</a></li>
            <li role="presentation" class=""><a href="#share" aria-controls="share" role="tab" data-toggle="tab">Upload Share images</a></li>
          </ul>

          <!-- TAB CONTENT -->

          @include ('settings.global_social_media.edit')
		  
          
        </div>

        <input type="hidden" name="master" value="1"/>
      </form>



    </div>
  </div>
</div>




@stop
