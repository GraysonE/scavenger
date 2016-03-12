$(function () {

  var FILESIZE_LIMIT = 2097152; //2MB = 2048KB = 2097152B
  var $html = $('html'), $win = $(window), wrap = $('.app-aside'); //, app = $('#app');
  /*
   * MAIN FORM SUBMISSION - [ Publish Changes to Dev ]
   *
   * This allows the main form to be positioned before/after other forms on
   * the same page.
   */
  $("#publishSideBar button[type=submit]").click(function()
  {
    $("form#main-form").submit();
  });



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


  /*
   * SIDEBAR MENU TOGGLER
   */
  $('.sidebar-mobile-toggler').each(function()
  {
    var that = $(this);
    var toggleClass = $(this).attr('data-toggle-class');
    var app = $('#app');

    $(this).on("click", function(e)
    {
      app.toggleClass(toggleClass);
      e.preventDefault();
    });
    if($(this).attr('data-toggle-click-outside'))
    {
      var sidebar = $('#sidebar');
      $(document).on("mousedown touchstart", function(e)
      {
        if(sidebar.has(e.target).length === 0 &&
          !sidebar.is(e.target) && 
          !that.is(e.target) && 
          !that.children().is(e.target) &&
          app.hasClass(toggleClass))
        {
          app.removeClass(toggleClass);
        }
      });
    };
  });


  /*
   * Convert logo imgs to native svg elements in order to be able to style them
   * Based on: http://stackoverflow.com/questions/11978995/how-to-change-color-of-svg-image-using-css-jquery-svg-image-replacement
   */
  var loadLogo = function($img)
  {
    console.log($img);
    var imgID = $img.attr('id');
    var imgClass = $img.attr('class');
    var imgURL = $img.attr('data-src');
    var imgPath = $img.attr('data-path');

    $.get(imgURL, function(data)
    {
        var $svg = $(data).find('svg');

        if(typeof imgID !== 'undefined')  { $svg = $svg.attr('id', imgID); }
        if(typeof imgClass !== 'undefined') { $svg = $svg.attr('class', imgClass+' replaced-svg'); }
        $svg = $svg.attr('data-path', imgPath);

        $svg = $svg.removeAttr('xmlns:a'); // Remove any invalid XML tags as per http://validator.w3.org

        $img.replaceWith($svg); // Replace image with new SVG

    }, 'xml');
  };

  $('img.svg.outlet-logo').each(function()
  {
    loadLogo($(this));
  });
  
  $('.block-reviews .subpanel-review select').change(function()
  {
    var $tr = $(this).closest('.subpanel');
    var logo = $(this).val();    
    var svg = $tr.find('.outlet-logo');
    var imgPath = svg.data('path');

    if(logo != '')
    {
      var elmt = $('<img class="svg outlet-logo" data-path="'+imgPath+'" data-src="'+imgPath+'/'+logo+'.svg"/>');
      svg.replaceWith(elmt);
      loadLogo(elmt);
    }
    else
    {
      svg.replaceWith('<div class="outlet-logo" data-path="'+imgPath+'"/>');
    }
  });
  

  /*
   * SELECT ACTIVE TAB BASED ON LOCATION HASH
   */
  var hash = window.location.hash;
  $('.tab-content').each(function()
  {
    if(hash && $('.tab-pane'+hash).length != 0)
    {
      $(this).find('.nav-tabs li').removeClass('active');
      $(this).find('.tab-pane').removeClass('active');

      $(this).find('.nav-tabs li a[href="'+hash+'"]').parent().addClass('active');
      $(this).find('.tab-pane'+hash).addClass('active');
    }
  });


  /*
   * DELETE CONFIRMATION DIALOG
   */
  $(".delete-confirm").on("click", function (e)
  {
    var that = this;
    var delObj = $(this).attr('data-object');
    delObj = typeof delObj == 'undefined' ? 'item' : delObj;

    swal({
      title: "Are you sure you want to delete this " + delObj + "?",
      //text: "",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#C82E29",
      confirmButtonText: "Delete " + delObj,
      closeOnConfirm: false
    },
    function ()
    {
      window.location.replace($(that).attr("href"));
    });

    e.preventDefault();
  });


  /*
   * COLORPICKER
   */
  if ($.fn.colorpicker)
  {
    $('.form-colorpicker').colorpicker();

    //skips Design page since it's already in its own form
    $('#form-colorpicker .form-colorpicker').on('changeColor.colorpicker', function(e)
    {
      var pathname = window.location.pathname;
      var movie_id = $('#form-colorpicker input[name=movie_id]').val();
      var section_id = $('#form-colorpicker input[name=section_id]').val();
      var id = $('#form-colorpicker input[name=id]').val();

      var color_id = $(this).attr('id');
      var data = {
        movie_id: movie_id,
        section_id: section_id,
        id: id,
        color: e.color.toHex()
      };
      
	  console.log(data);
	  
      $.ajax({
        data: data,
        type: 'POST',
        url: pathname + '/custom-background',
        success: function (data)
        {
	        console.log(data);
        },
        error: function (data)
        {
          var errors = data.responseJSON;
          console.log(errors);
        }
      });
    });
  }


  /*
   * DATEPICKER
   */
  if ($.fn.datepicker)
  {
    $('.datepicker').datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      todayHighlight: true
    });
  }


  /*
   * CKEDITOR INIT
   */
  if ($.fn.ckeditor)
  {
    "use strict";
    CKEDITOR.disableAutoInline = true;
    $('textarea.ckeditor').ckeditor({
      uiColor: '#ffffff',
      resize_enabled: false,
      customConfig: '../../assets/js/stx-ckeditor-config.js'
    });
  }


  /*
   * EXPANDABLE TABLE ROWS FOR EDITING
   */
//  $.fn.extend({
//    setOffValues: function ()
//    {
//      var subpanel = $(this).closest('.subpanel');
//      $(subpanel).find('.on').each(function ()
//      {
//        if ($(this).is('.form-control'))
//        {
//          $(this).siblings('.off').html($(this).val());
//        }
//        else if ($(this).is('.form-group-link') || $(this).is('.form-group-role'))
//        {
//          var field = $(this).find('.form-control').filter(function () {
//            return this.value.length !== 0;
//          });
//          var val = '';
//          if (field.is('select'))
//          {
//            val = field.find('option:selected').text();
//          }
//          else
//          {
//            val = field.val();
//          }
//          $(this).siblings('.off').html(val);
//        }
//        else
//        {
//          $(this).siblings('.off').html($(this).find('.form-control').val());
//        }
//      });
//    }
//  });


  $('.nav-tabs li').click(function () {
	  
    var tab = $(this).find('a[role=tab]').attr('aria-controls');
    $('#' + tab).find('.active').removeClass('active');
    
	var data = $(this).find('a[role=tab]').attr('href').split('#');
    
    $('input[name=tab]').val(data[1]);
    
  });
  
  if ($('input[name=tab]').val() == '') {
	  var data = window.location.hash.split('#');
	  $('input[name=tab]').val(data[1]);
  }

  $('.expandable').on('click', '.ti-pencil, .ti-pencil-alt, .btn-cancel', function ()
  {
    var fieldset = $(this).closest('.subpanel');

    if(fieldset.is('.active'))
    {
      //cancel edits and revert to original data
      fieldset.find('input[data-orig-value]').each(function()
      {
        $(this).val($(this).attr('data-orig-value'));
      });
      fieldset.find('textarea').each(function()
      {
        var orig = $(this).val($(this).siblings('pre.orig-textarea').html());
        CKEDITOR.instances[$(this).attr('id')].setData(orig);
      });
      fieldset.find('input[data-orig-checkbox]').each(function()
      {
        if($(this).attr('data-orig-checkbox') != false) //empty or false
        {
          $(this).prop('checked', 'checked');
        }
        else
        {
          $(this).removeProp('checked');
        }
      });
    }
    fieldset.toggleClass('active');
  });

  $('.expandable input, .expandable select').focusin(function()
  {
    $(this).closest('tr').addClass('active');
  });
  $('.expandable .col-checkbox').click(function()
  {
    $(this).closest('tr').addClass('active');
  });
  $('.expandable input, .expandable select').focusout(function()
  {
    $(this).closest('tr:not(.subpanel)').removeClass('active');
  });


//  $('.expandable .fa-close, .expandable .btn-save').click(function ()
//  {
//    $(this).closest('.subpanel.active').removeClass('active');
//    $(this).setOffValues();
//  });
//  $('.expandable').on('keypress', 'input', function (e)
//  {
//    if (e.keyCode == 13) //enter
//    {
//      $(this).closest('.subpanel').removeClass('active');
//      $(this).setOffValues();
//    }
//  });

//  $(document).mouseup(function(e)
//  {
//    var activeInput = $('.expandable .subpanel.active');
//    if(!activeInput.is(e.target) &&
//        activeInput.has(e.target).length===0 &&
//        $(e.target).closest('.datepicker').length===0 &&
//        $(e.target).closest('.colorpicker').length===0)
//    {
//      activeInput.removeClass('active');
//    }
//  });

  /*
   * PUBLISHING PAGE - DRAGGABLES
   */
  if ($.fn.sortable)
  {
    $('.panel-servers > div').sortable({
      helper: 'clone',
      connectWith: '.col',
      start: function (e, ui)
      {
        var clone = $(ui.item).clone();
        $(clone).show();
        $(clone).addClass("sorting");
        $(ui.item).before(clone);
      },
      receive: function (e, ui)
      {
        //prevent duplicates when moving to a different list
        var item_id = $(ui.item).attr("data-block-id");
        if ($(e.target).find('div[data-block-id=' + item_id + ']').length > 1)
        {
          $(ui.item).remove();
        }
        $('.panel-servers .sorting').removeClass('sorting');
      },
      stop: function (e, ui)
      {
        //remove the original item if sorting from the same list to prevent dupes
        $('.panel-servers .sorting').remove();

        var server = $(ui.item).closest('.server');
        
        //if dragging to the same server, ignore
        if($(e.target).is(server))  { return false; }
        
        //maintain same sort order as original list
        $(server).find('.content-block').sort(sort_blocks).appendTo($(server));

        updateServerBlocks(server);
      }
    });

    $('.panel-servers').on('click', '.fa-close', function ()
    {
      var server = $(this).closest('.server'); //save before removing block
      $(this).closest('.content-block').remove();

      updateServerBlocks(server);
    });

    function sort_blocks(a, b)
    {
      return ($(b).data('position')) < ($(a).data('position')) ? 1 : -1; //sort by [data-position=""] attribute
    }
    
    function updateServerBlocks(server)
    {
      if(server.length > 0)
      {
        var servername = $(server).attr('data-server');
        $('select[name="'+servername+'[]"] option').removeProp('selected');
        $(server).find('div[data-block-type]').each(function()
        {
          var sectionId = $(this).attr('data-block-id');
          $('select[name="'+servername+'[]"] option[value='+sectionId+']').prop('selected', 'selected');
        });

//        var blocks = $(server).find('div[data-block-type]').map(function()
//        {
//          return $(this).attr('data-block-type');
//        }).toArray();

//        $.ajax({
//          data: {blocks: blocks, server: servername, _token: csrf_token},
//          type: 'POST',
//          url: window.location.pathname,
//          success: function (order)
//          {
//          },
//          error: function (order)
//          {
//            var errors = order.responseJSON;
//            console.log(errors);
//          }
//        });
      }
    }
  }

  /*
   * FONT SELECTION PREVIEW
   */
  $('.panel-font-select select').change(function ()
  {
    var font = $(this).find('option:selected').text();
    $(this).closest('.form-group').find('.preview').attr('style', "font-family:'" + font + "'");
  });
  //init font preview
  $('.panel-font-select .input-group-select').each(function()
  {
    var font = $(this).find('option:selected').text();
    $(this).siblings('.preview').css('font-family', font);
  });

  /*
   * CUSTOM CONTENT BLOCK SELECTION DIALOG
   */
  var orig_type = '';
  $('#custom_block_dialog').dialog(
  {
    autoOpen: false,
    appendTo: 'form#addBlockForm',
    width: 800,
    height: 690,
    modal: true,
    buttons: [
      {
        text: "USE SELECTED LAYOUT",
        click: function ()
        {
          orig_type = $('input[name=custom_type]:checked').val();
          $(this).dialog('close');
        }
      },
      {
        text: "CANCEL",
        click: function ()
        {
          $(this).dialog('close');
        }
      }
    ],
    close: function ()
    {
      $('input').removeAttr("checked");
      if (orig_type != '')
      {
        $('input#' + orig_type).prop('checked', true);
      }
    },
  });
  $('select.form-select-block-types').change(function ()
  {
    if ($(this).val() == 'featured_content' ||
    $(this).val() == 'custom_content' ||
    $(this).val() == 'sections.contentblocks.featuredcontent')
    {
      $('#custom_block_dialog').dialog('open');
    }
    $(this).blur();
  });


  /*
   * FILE UPLOADS
   *
   * Submit upload form when file field has changed.
   */
   $('.image-upload-form input[type=file]').change(function()
   {
     if( $(this).get(0).files[0].size > FILESIZE_LIMIT)
     {
       var mb = Math.round(FILESIZE_LIMIT / 1024 / 1024);
       $(this).closest('.file-drop-zone').after('<div class="errors"><div class="alert alert-danger small">File cannot be uploaded as it exceeds '+mb+'MB.</div></div>');
     }
     else
     {
        $(this).closest('form').submit();
     }
   });

   /*
   * PARTNERS / FEATURED CONTENT FILE UPLOADS 
   * 
   * Submit upload form when file field has changed.
   */
   $('form#main-form .file-uploader input[type=file]').change(function()
   {
     $('form#main-form').submit();
   });


   /*
    * TICKETING TOGGLE
    */
  $('.panel-gettickets .toggle').click(function()
  {
    $('.panel-gettickets .toggle').removeClass('toggle-on');
    $('.panel-gettickets .toggle').addClass('toggle-off');
    $('.panel-gettickets .toggle div').text('OFF');
    $(this).removeClass('toggle-off');
    $(this).addClass('toggle-on');
    $(this).find('div').text('ON');


    // Form handling for ticket displays
    var powster = $('#powster');
    var simple = $('#simple');

    if (powster.hasClass('toggle-on')) {
	    $("input[name=powster_display]").attr('value', 1);
    } else if (simple.hasClass('toggle-on')) {
	    $("input[name=simple_display]").attr('value', 1);
    }

    if (powster.hasClass('toggle-off')) {
	    $("input[name=powster_display]").attr('value', 0);
    } else if (simple.hasClass('toggle-off')) {
	    $("input[name=simple_display]").attr('value', 0);
    }
  });








$('#previewMovie').click(function()
{
    var data = $('#main-form').serialize();
    var url = $('#main-form').attr('action');
    var movie_id = $('#previewMovie').attr('data-movie-id');
    var pathname = window.location.pathname.split('movies');
    
//         var preview_location = pathname[0] + 'movies/' + movie_id + '/publish/html';
    var preview_location = pathname[0] + 'movies/' + movie_id + '/publish/html';
    
    console.log(pathname);
    
    $.ajax({
		data: data,
		type: 'POST',
		url: url,
		async: false,
		success: function (data)
        {
          	window.open(preview_location, '_blank');
//               	window.location.href = preview_location;
        },
        error: function (data)
        {
            var errorBag = data.responseJSON;
	        console.log(errorBag);
            errorsHtml = '<div class="alert alert-danger"><ul>';

	        $.each( errorBag , function( key, value ) {
	            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
	        });
	        errorsHtml += '</ul></di>';
	            
	        $( '.errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
        }

	});
});



//$('.btn-success').click(function(e)
//	{
//		var pathname = $(this).attr('href');
//		var text = $(this).text();
//
//		console.log(text);
//
//		if (pathname) {
//
//			e.preventDefault();
//	        var data = $('#main-form').serialize();
//	        var url = $('#main-form').attr('action');
//
//	        $.ajax({
//				data: data,
//				type: 'POST',
//				url: url,
//				success: function (data)
//		        {
//			        console.log('success');
//	              	window.location.href = pathname;
//		        },
//		        error: function (data)
//		        {
//			        var errorBag = data.responseJSON;
//			        console.log(errorBag);
//		            errorsHtml = '<div class="alert alert-danger"><ul>';
//
//			        $.each( errorBag , function( key, value ) {
//			            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
//			        });
//			        errorsHtml += '</ul></di>';
//
//			        $( '.errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
//		        }
//
//			});
//        }
//
//
//	});



  /*
   * COPIED FROM GLOBAL.JS
   */

  if ($.fn.sortable)
  {
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
        if (sort_type == 'videos') {
          route = '/videos/sort-order';
        } else if (sort_type == 'sections') {
          route = '/sort-order';
        } else if (sort_type == 'people') {
          route = '/people/sort-order';
        } else if (sort_type == 'setting') {
          route = '/sort-order';
        } else if (sort_type == 'call_to_action') {
          route = '/cta/sort-order';
        } else if (sort_type == 'images') {
          route = '/gallery/sort-order';
        } else if (sort_type == 'featured') {
          route = '/featured/sort-order';
        } else if (sort_type == 'movies') {
          route = '/movies/sort-order';
        }
        
        

        console.log(pathname + route);
        console.log(sort_type + order);

        // POST to server using $.post or $.ajax
        $.ajax({
          data: {order: order, type: sort_type, _token: csrf_token},
          type: 'POST',
          url: pathname + route,
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
      },
      //fixes sortable issue with ckeditor:
      //http://ckeditor.com/forums/CKEditor/CKEditor-Becomes-Disabled-When-Moved-with-Javascript
      start: function(event, ui)
      {
        var textareaId = ui.item.find('textarea').attr('id');
        if (typeof textareaId != 'undefined')
        {
          var editorInstance = CKEDITOR.instances[textareaId];
          editorInstance.destroy();
          CKEDITOR.remove( textareaId );
        }
      },
      stop: function(event, ui)
      {
        var textareaId = ui.item.find('textarea').attr('id');
        if (typeof textareaId != 'undefined')
        {
          console.log('replace');
          CKEDITOR.replace( textareaId,
          {
            uiColor: '#ffffff',
            resize_enabled: false,
            customConfig: '../../assets/js/stx-ckeditor-config.js'
          });
        }
      }
    });
  }

  /*
   * SIDEBAR HIGHLIGHTING
   */
  //@todo: temp - keep sidebar open until figure out paths
  $('#sidebar li.parent').addClass('open');


  $('#sidebar a').each(function()
  {
    var current = $(this).attr('href').split('#'); //ignore hashes
    if(current[0].endsWith(window.location.pathname))
    {
      $(this).closest('li').addClass('open');
      $(this).closest('li.parent').addClass('open');
    }
  });

  $('#sidebar li.parent > a').click(function()
  {
    $(this).siblings('ul.sub-menu').slideToggle('fast', function()
    {
      $(this).parent().toggleClass('open');
    });
  });
  

});
