jQuery(function($) {
  
  var viewportWidth = $('#aurora-viewport-width');
  var modernizrDebug = $('#aurora-modernizr-debug');
  
  //////////////////////////////
  // Viewport Width Display
  //////////////////////////////
  if (viewportWidth.length) {
    var widthPX = $(window).width();
    var widthEM = widthPX / 16;

    viewportWidth.html(widthEM + 'em');

    viewportWidth.click(function() {
      $(window).resize(function() {
        widthPX = $(window).width();
        widthEM = widthPX / 16;
      });

      var parseHTML = parseFloat($(this).html());
      if (parseHTML == widthPX) {
        $(this).html(widthEM + 'em')
      }
      else if (parseHTML == widthEM) {
        $(this).html(widthPX + 'px')
      }
    });

    $(window).resize(function() {
      widthPX = $(window).width();
      widthEM = widthPX / 16;
      if (viewportWidth.html().indexOf('em') > 0) {
        viewportWidth.html(widthEM + 'em');
      }
      else {
        viewportWidth.html(widthPX + 'px');
      }
    });
  }
  
  //////////////////////////////
  // Modernizr Features Display
  //////////////////////////////
  if (modernizrDebug.length) {
    if (typeof Modernizr === "undefined") {
      modernizrDebug.html('Modernizr is not loaded!');
      console.log('Modernizr Not Loaded!');
    }
    else {
      modernizrDebug.html($('html').attr('class'));
      modernizrDebug.click(function() {
        console.log($(this).height());
        if ($(this).height() == 16) {
          $(this).css('height', 'auto');
        }
        else {
          $(this).css('height', '1.5em');
        }
      });
    }
  }
});