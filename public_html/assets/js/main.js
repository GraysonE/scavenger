(function ($, root, undefined) {

  $.fn.animationComplete = function (func) {
    $(this).one('webkitTransitionEnd oTransitionEnd otransitionend transitionend msTransitionEnd', func);
  }

  $.fn.hitTest = function (x, y) {
    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
    return x >= bounds.left
            && x <= bounds.right
            && y <= bounds.bottom
            && y >= bounds.top;
  };

  $.fn.onscreen = function () {
    return (($(this).offset().top >= $(window).scrollTop() + 0.9 * $(window).height()));
  }

  $(function () {


    var $wrapper = $('.wrapper');
    var $header = $('header');
    var $main = $('main');
    var $dataPicker = $("#datepicker");
    var $siteMenu = $('header .site-menu a');
    var $navigation = $('header > nav');
    var $burger = $('.title-line > .box > ul');
    var $creditsBtn = $('.credits.btn');
    var $creditsBlock = $('.credits-block');
    var $about = $('section.aboutthefilm');
    var $aboutSubmenu = $('section.aboutthefilm .box > ul > li');
    var $viewLess = $('.view-less');
    var $viewMore = $('.view-more');
    var $navCastOpen = $('.op-cl');
    var $navCastLabel = $('section.aboutthefilm .box article.cast .left  h4');
    var $navCastItems = $('section.aboutthefilm .box article.cast .left  ul li');
    var $castDataItems = $('section.aboutthefilm .box article.cast .right  ul');
    var $cast = $('section.aboutthefilm .box article.cast');
    var $ticketsIcon = $('section.tickets .icon');
    var $shareMenuBtn = $('section.gallery .swiper-container .swiper-slide > a');
    var $sharePopup = $('section.gallery .swiper-container .swiper-slide .share-popup');
    var $partnersItems = $('section.partners .swiper-container .swiper-slide');
    var $partnersBlock = $('section.partners .swiper-container');
    var $galleryBlock = $('section.gallery .swiper-container');
    var $videoItems = $('section.videos .swiper-container .swiper-slide');
    var $videoBlock = $('section.videos .swiper-container');
    var $videoPlayer = $('section.videos .box');
    var $videoControls = $('section.videos .controls');
    var $quotesBlock = $('section.reviewsandawards .swiper-container');
    var $featuredBlocks = $('section.featured .swiper-container');
    var $hoverMenuItems = $('header > nav .hover-menu ul li');
    var $hoverMenu = $('header > nav .hover-menu');
    //var $pagesMenu = $('header > nav .pages-menu a');
    var $anchorLinks = $('a[data-page]');
    var $getUpdatesBtn = $('.btn.get-updates');
    var $getUpdatesPopup = $('.get-updates-popup');


    var w = window.innerWidth ? window.innerWidth : $(window).innerWidth();
    var h, restoreAnim, lId, idS, castId, numCastIterations;
    var awardsSwiper, gallerySwiper, partnersSwiper, videoSwiper, featuredSwipers;
    var $mobileSize = (w <= 768) ? true : false;




    if (window.devicePixelRatio > 1) {
      var lowresImages = $('.r2x');
      lowresImages.each(function (i) {
        var lowres = $(this).attr('src');
        if (lowres) {
          var highres = lowres.replace('.', '@2x.');
          if (lowres.indexOf('svg') == 0) {
            $(this).attr('src', highres);
          }
        }
      });
    }

    $('section').each(function () {
      $(this).waitForImages(function () {
        $(this).addClass('loaded');
      });
    });



    function init() {

      $wrapper.show();
      $dataPicker.datepicker({});
      $(window).resize(resizeIt);
      
      
      // MAIN MARQUEE VIDEOS
      // show video when it's loaded enough to start playing
      $('section.mainmarquee video').each(function()
      {
        $(this).get(0).oncanplaythrough = function()
        {
          $(this).css('visibility', 'visible');
        };
      });


      // EMAIL SIGNUP CONFIRMATION
      
      $('form[name=mc-embedded-subscribe-form] input.submit').click(function()
      {
        var form = $(this).closest('form');
        if($(form).find('input[type=email]').val() != '')
        {
          $(form).find('input[type=email]').fadeOut();
          $(form).find('input[type=submit]').fadeOut();
          $(form).closest('.email-form').replaceWith('<p class="email-confirm">Thank you for signing up!</p>');
        }
          
      });


      // FIND CURRENT RELEASE DATE TEXT FOR SYNOPSIS

      $('p#announcement[data-rd]').each(function()
      {
        var timezone = new Date().toString().match(/([A-Z]+[\+-][0-9]+)/);
        if(timezone)
        {
          timezone = timezone[1];
          timezone = timezone.replace('GMT', '');
          timezone = timezone.substring(0, 3) + ':' + timezone.substring(3);
        }
        else
        { timezone = ''; }
        
        var today = new Date(); //client timezone

        var rd = $(this).data('rd');
        for(var i=0; i < rd.length; i++)
        {
          var dateStr = rd[i].date + 'T00:00:00' + timezone;
          var rd_date = new Date(dateStr); //client timezone

          if(rd_date <= today)
          {
            $('#announcement').html(rd[i].text);
          }
        }
      });


      // SHOW VALID MOVIES ON MOVIE LISTINGS AND TOP MENU
      
      $('body[data-list-type], .hover-menu[data-list-type]').each(function()
      {
        showCurrentMovies(this);
      });


      // GET TICKET WIDGETS

      $('#get-tickets-fandango').click(function (e)
      {
        e.preventDefault();
        var zip = $('input#zip').val();
        var date = $('input#datepicker').val();

        var url = $(this).attr('href');
        var goto = url + '?location=' + zip + '&date=' + date;
        window.open(goto);
      });
      $('#get-tickets-movietickets').click(function (e)
      {
        e.preventDefault();
        var zip = $('input#zip').val();
        var date = new Date($('input#datepicker').val());
        var datediff = 0;

        if (!isNaN(date.getTime()))
        {
          var today = new Date(); //today
          datediff = date - today;
          datediff = Math.ceil(datediff / 1000 / 60 / 60 / 24); //days
          datediff = datediff <= 0 ? 0 : datediff; //unsign it
        }
        var url = $(this).attr('href');
        var goto = url + '/SearchZip/' + zip + '/ShowDate/' + datediff;
        window.open(goto);
      });


      // FEATURED CONTENT SLIDER Initalization

      featuredSwipers = [];
      $featuredBlocks.each(function (i)
      {
        var swiperId = '#' + $(this).attr('id');
        featuredSwipers[i] = new Swiper(swiperId, {
          nextButton: $(this).siblings('.fea-n'),
          prevButton: $(this).siblings('.fea-p'),
          slidesPerView: 3,
          centeredSlides: true,
          speed: 400
        });
      });


      // AWARDS SLIDER Initalization

      awardsSwiper = [];
      $quotesBlock.each(function (i) {
        awardsSwiper[i] = new Swiper('#' + $(this).attr('id'), {
          nextButton: $(this).find('.swiper-button-next'),
          prevButton: $(this).find('.swiper-button-prev'),
          loop: true,
          speed: 400
        });
      });
      
      // REVIEWS LOGOS:
      $('img.svg.outlet-logo').each(function()
      {
        loadLogo($(this));
      });

      // PARTNERS SLIDER Initalization

      //<replace ----------------------------------------------------------------------------------------------------------- >
      partnersSwiper = [];
      $partnersBlock.each(function (i) {
        var items = $(this).find('.swiper-slide');
        items.length == 2 && $mobileSize;

        var lim = 4;
        var spv = 3
        if ($mobileSize) {
          lim = 2;
          spv = 1;
        }

        if (items.length < lim) {
          $(this).find('.swiper-button-next').hide();
          $(this).find('.swiper-button-prev').hide();
        }


        var center = false;
        if (items.length == 2) {
          $(this).addClass('manual-center')
        } else {
          if (items.length == 1 && !$mobileSize) {
            center = true;
          }
        }

        partnersSwiper[i] = new Swiper('#' + $(this).attr('id'), {
          nextButton: $(this).find('.swiper-button-next'),
          prevButton: $(this).find('.swiper-button-prev'),
          slidesPerView: spv,
          centeredSlides: center,
          speed: 400
        });
      });


      // VIDEO SLIDER Initalization



      videoSwiper = [];
      $videoBlock.each(function (i) {

        var center = false;
        var initialSlide = 0;
        var items = $(this).find('.swiper-slide');
        if (items.length == 1) {
          $(this).addClass('manual-margin-1');
        } else if (items.length == 2) {
          $(this).addClass('manual-margin');
        } else {
          center = true;
          initialSlide = 1;
        }

        var len = 4;
        if ($mobileSize) {
          len = 2;
        }

        //console.log(items.length + ":" + len + ":" + center + ":" + initialSlide)
        videoSwiper[i] = new Swiper('#' + $(this).attr('id'), {
          nextButton: $(this).parent().parent().find('.swiper-button-next'),
          prevButton: $(this).parent().parent().find('.swiper-button-prev'),
          slidesPerView: len - 1,
          centeredSlides: center,
          initialSlide: initialSlide,
          onSlideChangeEnd: function (swiper) {
            removeVideoPreview();
          },
          speed: 400
        });



        checkIfWeNeedControls(videoSwiper[i], len);
      });

      /*checkVideoControls();
       if($videoItems.length == 2) {
       $videoBlock.css('padding-left', '26em');
       $videoItems.css('margin', '0em 2em');
       
       } else {
       if ($videoItems.length == 1 || $videoItems.length == 3) {
       center = true;
       if ($videoItems.length == 3) {
       initialSlide = 1;
       }
       }
       videoSwiper = new Swiper('section.videos .swiper-container', {
       nextButton: '.vid-n',
       prevButton: '.vid-p',
       slidesPerView: 3,
       centeredSlides: center,
       initialSlide: initialSlide,
       onSlideChangeEnd: function(swiper){
       removeVideoPreview();
       },
       speed: 400
       });
       }*/


      // GALLERY SLIDER Initalization

      //<replace ----------------------------------------------------------------------------------------------------------- >
      gallerySwiper = [];
      $galleryBlock.each(function (i) {
        gallerySwiper[i] = new Swiper('#' + $(this).attr('id'), {
          nextButton: $(this).find('.swiper-button-next'),
          prevButton: $(this).find('.swiper-button-prev'),
          pagination: $(this).find('.swiper-pagination'),
          paginationClickable: true,
          preloadImages: false,
          lazyLoading: true,
          loop: true,
          speed: 400
        });
      });


      $shareMenuBtn = $('section.gallery .swiper-container .swiper-slide > a');
      $sharePopup = $('section.gallery .swiper-container .swiper-slide .share-popup');

      resizeIt();

      $burger.on('touch click', function (e) {
        e.preventDefault();
        $header.toggleClass('menu-open');
      });

      $getUpdatesBtn.on('touch click', function (e) {
        e.preventDefault();
        $getUpdatesPopup.addClass('open');
      });

      $getUpdatesPopup.find('.close').on('touch click', function (e) {
        e.preventDefault();
        $getUpdatesPopup.removeClass('open');
      });

      $getUpdatesPopup.find('.block-click').on('touch click', function (e) {
        e.preventDefault();
        $getUpdatesPopup.removeClass('open');
      });


      $videoItems.on('touch click', function (e) {
        if (!$mobileSize) {
          e.preventDefault();
          playVideo(this, true);
        }
      });

      $aboutSubmenu.on('touch click', function (e) {
        e.preventDefault();
        $about.find('.menu').find('.selected').removeClass('selected');
        $(this).addClass('selected');
        changeAboutSubsection($(this).attr('data-sub'));
      });


      $creditsBtn.on('touch click', function (e) {
        e.preventDefault();
        $creditsBlock.addClass('open');
      });


      $creditsBlock.find('.close').on('touch click', function (e) {
        e.preventDefault();
        $creditsBlock.removeClass('open');
      });


      $viewLess.on('touch click', function (e) {
        e.preventDefault();
        colapseAbout(this);
      });

      $viewMore.on('touch click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        colapseAbout(this);
      });

      $anchorLinks.on('touch click', function (e) {
        e.preventDefault();
        changeSection($(this).attr('data-page'));
      });

      $navCastOpen.on('touch click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        toogleCastNav(this);
      });

      initCastItems();

      $navCastLabel.on('touch click', function (e) {
        e.preventDefault();
        toogleCastNav(this, true);
      });

      setDefaultCast();

      $ticketsIcon.on('touch click', function (e) {
        e.preventDefault();
        $("#datepicker").datepicker('show');
      });


      $shareMenuBtn.on('touch click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        clearTimeout(idS);
        idS = setTimeout(function () {
          $(window).on('touch click', checkClick);
        }, 400);
        $sharePopup.toggleClass('open');
      });


      $hoverMenuItems.on('mouseenter', function (e) {
        e.preventDefault();
        $hoverMenu.addClass('hovered');
      });

      $hoverMenuItems.on('mouseleave', function (e) {
        e.preventDefault();
        $hoverMenu.removeClass('hovered');
      });


      $siteMenu.on('mouseenter', function (e) {
        e.preventDefault();
        $hoverMenu.parent().find('.open').removeClass('open');
        $(this).parent().parent().find('.hover').removeClass('hover');
        $(this).addClass('hover');
        $navigation.find('.' + $(this).attr('data-menu')).addClass('open');

      });

      $siteMenu.on('mouseleave', function (e) {
        e.preventDefault();

        if (!$('nav').hitTest(e.pageX, e.pageY)) {
          $(this).removeClass('hover');
          $navigation.find('.' + $(this).attr('data-menu')).removeClass('open');
        }
      });

      $hoverMenu.on('mouseleave', function (e) {
        e.preventDefault();
        $hoverMenu.removeClass('open');
        $siteMenu.parent().find('.hover').removeClass('hover');
      });




      $wrapper.addClass('animation-on');
      checkOnScreen();
      $(document).on('scroll', function () {
        checkOnScreen();
      });

    }
    
    
    // MOVIE VISIBILITY BASED ON RELEASE DATES
    // because things are 'static', need to calculate visibility of movies
    // dynamically based on stored release dates
    function showCurrentMovies(elmt)
    {
      var onCount = 0;
      var listType = $(elmt).data('list-type');
      var timezone = new Date().toString().match(/([A-Z]+[\+-][0-9]+)/);
      if(timezone)
      {
        timezone = timezone[1];
        timezone = timezone.replace('GMT', '');
        timezone = timezone.substring(0, 3) + ':' + timezone.substring(3);
      }
      else
      { timezone = ''; }

      var today = new Date(); //client timezone

      $(elmt).find('li[data-rd]').each(function()      
      {
        var rd = $(this).data('rd');
        var od = $(this).data('od');
        $(this).hide();

        for(var i=0; i < rd.length; i++)
        {
          var dateStr = rd[i].date + 'T00:00:00' + timezone;
          var rd_date = new Date(dateStr);//client timezone
          if(rd_date <= today && rd[i].type == listType)
          {
            if($(elmt).is('.hover-menu'))
            {
              if(onCount < 7)
              {
                $(this).show();
                $(this).removeClass('off');
                $(this).addClass('on');
              }
              onCount++;
            }
            else
            {
              $(this).show();
              $(this).removeClass('off');
              $(this).addClass('on');
            }
            
            $(this).find('p.releasedate').text($.datepicker.formatDate('MM d, yy', new Date(od + 'T00:00:00' + timezone)));
          }
          //a different category has more recent non-future date
          else if(rd_date <= today)
          {
            $(this).hide();
            $(this).addClass('off');
            $(this).removeClass('on');
          }
        }
      });
      
      //empty category
      if($(elmt).is('.hover-menu'))
      {
        //$(elmt).find('.box ul li.on').length == 0)
        if(onCount == 0)
        {
          var menuType = listType.replace('_', '-');
          $('ul.site-menu li a[data-menu='+menuType+']').hide();
        }
        else if(onCount <= 7)
        {
          $(elmt).find('.wire-btn').hide();
        }
      }
    }
    

    // Convert logo imgs to native svg elements in order to be able to style them
    // Based on: http://stackoverflow.com/questions/11978995/how-to-change-color-of-svg-image-using-css-jquery-svg-image-replacement
    function loadLogo($img)
    {
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




    function setDefaultCast() {

      setTimeout(function () {
        var inner = $($navCastItems[0]).find('span');
        if ($mobileSize) {
          inner = $($navCastItems[0]).find('.opl');
        }
        $(inner[0]).trigger('click');
        $($navCastLabel[0]).trigger('click');
      }, 500)
    }


    function initCastItems() {
      if ($cast.find('.selected').length > 1) {
        $cast.find('.selected').removeClass('selected');
        setDefaultCast();
      }
      $navCastItems.each(function () {
        var inner = $(this).find('span');
        var temp = $(this).find('.opl');

        $(inner[0]).off('touch click', castItemsHandler);
        $(temp[0]).off('touch click', castItemsHandler);
        if ($mobileSize) {
          inner = temp;
        }
        $(inner[0]).on('touch click', castItemsHandler);
      });

    }

    function castItemsHandler(e) {
      e.preventDefault();
      if (!$mobileSize) {
        $cast.find('.selected').removeClass('selected');
      }
      $(this).parent().toggleClass('selected');
      recountCastItemHeight($(this).parent().parent());
    }


    function changeSection(val) {
      var sec = $('section.' + val);
      $main.addClass('seek');
      $main.animationComplete(function () {
        $(window).scrollTop($(sec).offset().top - 100);
        $main.removeClass('seek');
      });
    }

    function checkOnScreen() {
      $('section').each(function () {
        if (!$(this).onscreen()) {
          $(this).addClass('onscreen')
        }
      });
    }











    function playVideo(item, autoplay) {
      var pl = $(item).parent().parent().parent();
      $(pl).find('.selected').removeClass('selected')
      $(item).addClass('selected')
      if (!$mobileSize) {
        $(pl).find('#player').remove();
        var auto = '';
        if (autoplay) {
          auto = 'autoplay=1';
        }
        $(pl).prepend('<iframe id="player" width="560" height="315" src="https://www.youtube.com/embed/' + $(item).attr('data-video') + '?enablejsapi=1&mp;rel=0&amp;' + auto + '" frameborder="0" allowfullscreen></iframe>');
      }
    }


    function initMobileVideo() {
      $videoItems.each(function () {
        $(this).find('.preview').append('<iframe width="560" height="315" src="https://www.youtube.com/embed/' + $(this).attr('data-video') + '?enablejsapi=1&rel=0" frameborder="0" allowfullscreen></iframe>');
        $(this).addClass('video');
      });

      /*if (!videoSwiper && $videoItems.length >1) {
       videoSwiper = new Swiper('section.videos .swiper-container', {
       nextButton: '.vid-n',
       prevButton: '.vid-p',
       slidesPerView: 1,
       onSlideChangeEnd: function(swiper){
       removeVideoPreview();
       },
       speed: 400
       });
       }*/
    }

    function removeVideoPreview() {
      var prev = $videoItems.parent().find('.swiper-slide-prev');
      $(prev).find('.preview iframe').remove();
      $(prev).find('.preview').append('<iframe width="560" height="315" src="https://www.youtube.com/embed/' + $(prev).attr('data-video') + '?enablejsapi=1&rel=0" frameborder="0" allowfullscreen></iframe>');
      ;
    }


    function disableMobileVideo() {
      $videoItems.each(function () {
        $videoItems.find('iframe').remove();
        $videoItems.removeClass('video');
      });


      if (!$mobileSize && $videoItems.length == 2) {
        videoSwiper.destroy();
      }
    }


    /*function checkVideoControls(len) {
     if ($mobileSize &&  $videoItems.length > 1 || $videoItems.length > len) {
     $videoControls.show();
     } else {
     $videoControls.hide();
     }
     }*/














    function checkClick(e) {
      if ($sharePopup.hasClass('open')) {
        $sharePopup.removeClass('open');
        $(window).off('touch click');
      }
    }


    function changeSubsection(sub) {
      $castDataItems.find('.preview').removeClass('preview')
      $castDataItems.find('.' + sub).addClass('preview');
    }


    function toogleCastNav(th, ignor) {
      var val = '<h4>-</h4>';
      var font = parseInt($('body').css('font-size'), 10);
      if ($(th).parent().find('ul').width() > 0) {
        if (!$(th).parent().find('ul').hasClass('open')) {
          $(th).parent().find('ul').addClass('open');
          $(th).parent().find('ul').css('height', $(th).parent().find('ul')[0].scrollHeight);
        } else {
          val = '<h4>+</h4>';
          $(th).parent().find('ul').removeClass('open');
          $(th).parent().find('ul').css('height', 0);
        }

        $(th).parent().find('.op-cl').empty();
        $(th).parent().find('.op-cl').append(val)


        if (!$mobileSize) {
          clearInterval(castId);
          numCastIterations = 0;
          castId = setInterval(function () {
            numCastIterations++;
            resizeCSHight();
            if (numCastIterations == 8) {
              clearInterval(castId);
            }
          }, 50);
        }
      }

    }

    function resizeCSHight() {
      stopTransitions();
      $about.find('.cast').find('.text').css('height', '22em');
      startTransitions();
      var casth = $about.find('.cast').height();

      if (casth > 220) {
        $about.find('.cast').find('.text').css('height', 0.9 * $about.find('.cast').height());
      } else {
        $about.find('.cast').find('.text').css('height', '22em');
      }
    }


    function colapseAbout(th) {

      $(th).parent().find('.view-less').toggleClass('no');
      $(th).parent().find('.view-more').toggleClass('no');
      $(th).parent().find('.content').toggleClass('colapsed');

      if ($about.find('.cast').hasClass('visible')) {
        var ul = $(th).parent().parent().parent();
        recountCastItemHeight(ul);
      }
    }

    function recountCastItemHeight(ul) {
      if ($(ul)[0].scrollHeight == $(ul).height()) {
        $(ul).css('height', 0);

        stopTransitions();
        $(ul).css('height', $(ul)[0].scrollHeight);
        startTransitions();
      } else {
        $(ul).css('height', $(ul)[0].scrollHeight);
      }
    }


    function changeAboutSubsection(sub) {
      $about.find('.visible').removeClass('visible');
      $about.find('article.' + sub).addClass('visible');
    }









    function checkIfWeNeedControls(swiper, len) {
      //console.log(swiper.slides.length + ":::" + len)
      if (swiper.slides.length < len) {
        $(swiper.params.nextButton).hide();
        $(swiper.params.prevButton).hide();
      } else {
        $(swiper.params.nextButton).show();
        $(swiper.params.prevButton).show();
      }
    }





    function resizeIt() {
      w = window.innerWidth ? window.innerWidth : $(window).innerWidth();
      h = window.innerHeight ? window.innerHeight : $(window).innerHeight();
      var wk = w / 1440;

      stopTransitions();

      if (w <= 768) {

        //if ($mobileSize) {
          $('input').each(function () {
            $(this).attr('placeholder', $(this).attr('data-placeholder'))
          });

          for (var i = 0; i < partnersSwiper.length; i++) {
            checkIfWeNeedControls(partnersSwiper[i], 2);
            if (partnersSwiper[i].update) {
              partnersSwiper[i].params.slidesPerView = 1;
              partnersSwiper[i].update(true);
            }
          }
          if (featuredSwipers.length > 0)
          {
            for (var i in featuredSwipers)
            {
              var fs = featuredSwipers[i];
              if (fs.update)
              {
                fs.params.slidesPerView = 1;
                fs.update(true);
              }
            }
          }
          initMobileVideo();
          for (var i = 0; i < videoSwiper.length; i++) {
            if (videoSwiper[i].update) {
              videoSwiper[i].params.slidesPerView = 1;
              videoSwiper[i].update(true);
              videoSwiper[i].slideTo(0);
              $videoPlayer.find('#player').remove();
            }
            checkIfWeNeedControls(videoSwiper[i], 2);
          }

          $mobileSize = true;
          initCastItems();
        //}
        
        $navCastItems.parent().each(function () {
          if ($(this).hasClass('open')) {
            recountCastItemHeight($(this))
          }
        })


        wk = wk * 2.3;
      } else {
        var call = false;
        if ($mobileSize) {
          $('input').each(function () {
            $(this).attr('placeholder', '')
          });


          for (var i = 0; i < partnersSwiper.length; i++) {
            checkIfWeNeedControls(partnersSwiper[i], 4);
            if (partnersSwiper[i].update) {
              partnersSwiper[i].params.slidesPerView = 3;
              partnersSwiper[i].update(true);
            }
          }
          disableMobileVideo();
          for (var i = 0; i < videoSwiper.length; i++) {
            if (videoSwiper[i].update) {
              videoSwiper[i].params.slidesPerView = 3;
              videoSwiper[i].update(true);
              if (videoSwiper[i].slides.length >= 3) {
                videoSwiper[i].slideTo(1);
              } else {
                videoSwiper[i].slideTo(0);
              }
              call = true;
            }
            checkIfWeNeedControls(videoSwiper[i], 4);
          }


          $navCastItems.parent().each(function () {
            if ($(this).hasClass('open')) {
              $(this).css('height', 'auto')
            }
          })
          $mobileSize = false;
          initCastItems();
        }

        if (call) {
          $videoBlock.find('.swiper-slide-active').each(function () {
            playVideo($(this));
          });
        }
      }


      $('body').css('font-size', 10 * wk);

      $('.center-it').each(function () {
        $(this).css('margin-left', (w - $(this).width()) / 2);
      });
      startTransitions();
    }


    function stopTransitions() {
      $wrapper.removeClass('animation-on');
    }

    function startTransitions() {
      clearInterval(restoreAnim);
      restoreAnim = setTimeout(function () {
        $wrapper.addClass('animation-on');
      }, 100);
    }


    // ATTACH GLOBAL INCLUDES
    $('#global-attach-menu').load($('#global-attach-menu').data('path'), function()
    {
      var classes = $(this).attr('class');
      $('header nav').addClass(classes);

      $(this).children().unwrap();
      $hoverMenuItems = $('header > nav .hover-menu ul li');
      $hoverMenu = $('header > nav .hover-menu');
      init();
    });
    $('#global-attach-footer').load($('#global-attach-footer').data('path'), function()
    {
      $(this).children().unwrap();
      
      //hide empty movie categories from mobile footer
      $('.hover-menu').each(function()
      {
        var menuType = $(this).data('list-type').replace('_', '-');
        if($(this).find('.box ul li.on').length == 0)
        {
          $('footer li.mobile-only a[href='+menuType+']').hide();
        }
      });
    });

  });

})(jQuery, this);
