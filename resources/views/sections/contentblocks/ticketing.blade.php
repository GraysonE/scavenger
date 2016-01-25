@extends ('master')

@section ('content')

@include ('movies.title')

<!-- TICKETING SECTION -->
<div class="container-fluid container-fullw block-ticketing">

  <div class="row page-description">
    <div class="col-sm-8 col-lg-9">
      <p class="main-description">Choose either a POWSTER URL or simple widget below for the GET TICKETS buttons.</p>
    </div>

    @if (Auth::user() && $editMovie)
    @include ('admin.publish')
    @endif

  </div>


  <div class="row">
    <div class="col-sm-12">




      <!-- GET TICKETS PANEL -->
      <div class="panel panel-white panel-gettickets">

        <div class="panel-body">
          <form id="main-form" method="post" action="{{ url('/admin/movies/'.$movie->id.'/edit/sections/'.$currentSection->id.'/tickets') }}">
            {{ csrf_field()}}
            <!-- POWSTER -->
            <div class="row powster">

              @if (!$tickets->isEmpty())
              @foreach ($tickets as $ticket)
              @if (($ticket->provider == 'powster') && ($ticket->on))
              	<div class="col-xs-1 toggle toggle-on" id="powster"><div>ON</div></div>
              @elseif (($ticket->provider == 'powster') && (!$ticket->on))
              	<div class="col-xs-1 toggle toggle-off" id="powster"><div>OFF</div></div>
              @endif
              @endforeach
              @else
              	<div class="col-xs-1 toggle toggle-off" id="powster"><div>OFF</div></div>
              @endif

              <div class="col-xs-11 content">

                <div class="row vertical-align-middle">
                  <div class="col-xs-10 form-group form-inline">
                    <label for="powster">Powster</label>



                    @if (!$tickets->isEmpty())

                    @foreach ($tickets as $ticket)
                    @if ($ticket->provider == 'powster')
	                    <input type="text" name="powster_url" placeholder="Enter URL" class="form-control" value="{{ $ticket->value }}">
	                    <input type="hidden" name="powster_id" value="{{ $ticket->id }}">
	                    <input type="hidden" name="powster_display" value="{{ $ticket->on }}">

                    @endif
                    @endforeach
                    @else 
	                    <input type="text" name="powster_url" placeholder="Enter URL" class="form-control">
	                    <input type="hidden" name="powster_display" value="1">
                    @endif

                  </div>
                  <div class="col-xs-2 form-group">
                    <div class="powster">
                      <!--a href="#"><i class="ti-pencil-alt icon-actions-clear pull-left"></i></a-->
                      <!--a href="#"><i class="fa fa-minus icon-actions pull-left"></i></a-->
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- SIMPLE TICKET WIDGETS -->
            <div class="row simple">

              @if (!$tickets->isEmpty())
              @foreach ($tickets as $ticket)
              @if (($ticket->provider == 'fandango') || ($ticket->provider == 'movietickets'))
              @if ($ticket->on)
              <div class="col-xs-1 toggle toggle-on" id="simple"><div>ON</div></div>
              <?php break; ?>
              @else
              <div class="col-xs-1 toggle toggle-off" id="simple"><div>OFF</div></div>
              <?php break; ?>
              @endif
              @endif
              @endforeach
              @else
              <div class="col-xs-1 toggle toggle-off" id="simple"><div>OFF</div></div>
              @endif

              <div class="col-xs-11 content">

                <h4>Simple Ticket Widget</h4>

                <div class="row">
                  <div class="col-xs-0 col-sm-1 form-group"></div>
                  <div class="col-xs-10 col-sm-9 form-group form-inline">
                    <label for="fandango_id"><img src="{{ asset('/admin-files/assets/images/logo-fandango.png') }}"/></label>

                    @if (!$tickets->isEmpty())
                    @foreach ($tickets as $ticket)
                    @if ($ticket->provider == 'fandango')
                    <input type="text" name="fandango" placeholder="Fandango Movie ID" class="form-control" value="{{ $ticket->value }}">
                    <input type="hidden" name="fandango_id" value="{{ $ticket->id }}">
                    @endif
                    @endforeach
                    @else
                    <input type="text" name="fandango" placeholder="Fandango Movie ID" class="form-control">
                    @endif
                  </div>
                  <div class="col-xs-2 form-group">
                    <!--a href="#"><i class="ti-pencil-alt icon-actions-clear pull-left"></i></a>
                    <a href="#"><i class="fa fa-minus icon-actions pull-left"></i></a-->
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-0 col-sm-1 form-group"></div>
                  <div class="col-xs-10 col-sm-9 form-group form-inline">
                    <label for="movietickets_id"><img src="{{ asset('/admin-files/assets/images/logo-movietickets.png') }}"/></label>

                    @if (!$tickets->isEmpty())

                    @foreach ($tickets as $ticket)
                    @if ($ticket->provider == 'movietickets')
                    <input type="text" name="movietickets" placeholder="Movietickets Movie ID" class="form-control" value="{{ $ticket->value }}">
                    <input type="hidden" name="movietickets_id" value="{{ $ticket->id }}">
                    <input type="hidden" name="simple_display" value="{{ $ticket->on }}">
                    @elseif (($ticket->provider == 'fandango') && ($ticket->on))
                    <input type="hidden" name="simple_display" value="1">

                    @endif
                    @endforeach
                    @else
                    <input type="text" name="movietickets" placeholder="Movietickets Movie ID" class="form-control">
                    <input type="hidden" name="simple_display" value="0">
                    @endif



                  </div>
                  <div class="col-xs-2 form-group">
                    <!--a href="#"><i class="ti-pencil-alt icon-actions-clear pull-left"></i></a-->
                    <!--a href="#"><i class="fa fa-minus icon-actions pull-left"></i></a-->
                  </div>
                </div>

              </div>

            </div>
          </form>
        </div>

      </div>




      <div class="panel panel-white panel-file-upload">
        <div class="panel-heading">
          <h5 class="panel-title">Ticketing Block Background Content</h5>
        </div>

        <div class="panel-body">

          <!-- FILE UPLOADER WIDGET -->
          <div class="file-uploader">
            <div class="row">

              @include('sections.images.edit')
              @include('sections.images.create')

            </div>
          </div>
          <!-- end: FILE UPLOADER WIDGET -->

          <!-- COLOR PICKER -->
          <div class="row padding-top-15">
            <div class="col-sm-12">
              @include('sections.colorpicker.edit')
             </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
@stop