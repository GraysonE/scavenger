$(function () {
	
	// Add the CSRF token to the ajax prefilter to allow ajax requests
	// to get past VerifyCsrfToken::class
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
	if (options.type.toLowerCase() === "post") {
	  // add leading ampersand if `data` is non-empty
	  options.data += options.data ? "&" : "";
	  // add _token entry
	  options.data += "_token=" + csrf_token;
	}
	});
	
	$(".ui-sortable:not(.server)").sortable({
      items: '> :not(.nosort)',
      axis: 'y',
      placeholder: "placeholder",
      handle:'.handle',
      opacity: 0.8,
      update: function (event, ui)
      {
        var pathname = window.location.pathname;
        var sort_type = $(this).attr('data-type');
        var order = [];

        $(this).children().each(function (i)
        {
          order.push($(this).attr('data-id'));
        });

        var route = '';
        if (sort_type == 'model_users') {
	        var account_id = pathname.split('/')[2];
          route = '/set-user/'+account_id+'/sort-order';
        } 
        
        

        console.log(pathname + route);
        console.log(sort_type + order);

        // POST to server using $.post or $.ajax
        $.ajax({
          data: {order: order, type: sort_type, _token: csrf_token},
          type: 'POST',
          url: route,
          success: function (order)
          {
            if (order) {
              console.log(order);
            } else {
             console.log('No HTML output.');
            }
          },
          error: function (order)
          {
            var errors = order.responseJSON;
            console.log(errors);
          }
        });
      }
    });	
    
    $('#account_accordion').accordion({
	    collapsible: true,
	    active: false
    });
    
    
	
});