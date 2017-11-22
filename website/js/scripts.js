/*! Scripts Index */
/* Last Updated 18-07-2016 - KW */

/* -------------------------------

- Helper Scripts
-- Delete Item
-- Wishlist
-- ScrollOff
-- Header builder Example
- Revolution Slider
- MenuZord
- Tendina Vertical Menu
- Sidr
- ScrollIt
- Parallax
- Full Screen Overlay
- Sticky Sidebar + Headers
- Slidebars
- Countdown
- WayPoint - Count To
- WayPoint - Typed
- Toggle
- Accordion
- Scroll Up
- Set Year
- Tooltips
- Clean Tabs Responsive tabs
- Header Reveal
- Fancybox light Boxes
- Vertical Align
- Owl Carousel
- Owl Carousel Single
- Owl Carousel Quotes
- Owl Carousel Events
- Fitvids 
- Images Loaded
- Isotope
- Sky Forms - Date Picker
- Header Reveal - headheasive
- List.js Sortable Tables/Lists 

---------------------------------*/







/* Helper Scripts - Delete Item
-------------------------------------------------- */
jQuery(document).ready(function() { 
	$( ".delete-item" ).click(function() {
	  $(this).closest( ".delete-item-content" ).hide(400);
	});
/* End */
});  


/* Helper Scripts - Wishlist
-------------------------------------------------- */
jQuery(document).ready(function() { 
	$( ".wishlist" ).click(function() {
	  $(this).children( ".fa" ).toggleClass( "fa-heart-o active" );
	});
	
	$( ".btn.wishlist" ).click(function() {
	  $(this).toggleClass( "btn-warning" );
	});
/* End */
});     


/* Helper Scripts - Scroll Off
-------------------------------------------------- */
jQuery(document).ready(function() { 
	$(".scroll-off-container").click(function(){
	    $(this).removeClass("scroll-off");
	});
/* End */
});  	


/* Helper Scripts - Header Builder Example
-------------------------------------------------- */
jQuery(document).ready(function() { 
	$( ".ex-element" ).click(function() {
	  $(this).toggleClass( "meta-text" );
	});
	$( ".ex-header > em" ).click(function() {
	  $(this).closest( ".ex-header" ).toggleClass( "meta-text" );
	});
/* End */
});  









/* Revolution Slider
-------------------------------------------------- */
jQuery(document).ready(function() { 
	/* Slider 1 */
   jQuery(".rev-slider-standard").revolution({
      sliderType:"standard",
      sliderLayout:"auto",
      //autoHeight:"on",
      //minHeight:700,
      disableProgressBar: "off",
      lazyType: "smart",  // full, smart, single, none
      dottedOverlay: "none",  //twoxtwo, threexthree, twoxtwowhite, threexthreewhite
      shadow: "0",  // 0,1,2,3,4,5,6
      spinner: "spinner0",  // spinner0, spinner1, spinner2, spinner3, spinner4, spinner5
      debugMode: false,
      delay:9000,
      navigation: {
      	onHoverStop:"off",		
        arrows:{enable:true} 
      }, 
      gridwidth:1140,
      gridheight:540 
    }); /* End Slider 1*/
    
    
    
    
    /* Slider 2 */
    jQuery(".rev-slider-full-screen").revolution({
       sliderType:"standard",
       sliderLayout:"fullscreen",
       //autoHeight:"on",
       //minHeight:700,
       disableProgressBar: "off",
       lazyType: "smart",  // full, smart, single, none
       dottedOverlay: "none",  //twoxtwo, threexthree, twoxtwowhite, threexthreewhite
       shadow: "0",  // 0,1,2,3,4,5,6
       spinner: "spinner0",  // spinner0, spinner1, spinner2, spinner3, spinner4, spinner5
       debugMode: false,
       delay:9000,
       navigation: {
       	onHoverStop:"off",		
         arrows:{enable:true} 
       }, 
       fullScreenOffsetContainer: "#header",
       parallax:{
          type:"scroll",
          levels:[5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85],
          origo:"enterpoint",
          speed:400,
          bgparallax:"on",
          disable_onmobile:"off"
       }
     }); /* End Slider 2*/
     
/* End */
}); 







/* MenuZord
-------------------------------------------------- */
jQuery(document).ready(function(){  
	jQuery(".menuzord").menuzord({
		//align: "right", //left, right
		trigger: "hover", // hover, click
		//animation: "zoom-in", //zoom-in, zoom-out, drop-up, drop-left, swing, flip, roll-in and stretch
		effect: "fade", // slide, fade
		responsive: true,
		scrollable: false,
		showSpeed: 200,
		hideSpeed: 100,	
		showDelay: 200,
		hideDelay: 100,
		indentChildren: false,
		indicatorFirstLevel: "<i class='fa fa-angle-down'></i>",
		indicatorSecondLevel: "<i class='fa fa-angle-right'></i>"
	});
/* End */
});









/* Tendina Vertical Menu
-------------------------------------------------- */
jQuery(document).ready(function(){  
    $('.vert-menu').tendina({
      animate: true,
      speed: 400,
      onHover: false,
      hoverDelay: 300,
      activeMenu: $('.vert-menu-active')
    });
/* End */
});
    









/* Sidr
-------------------------------------------------- */
jQuery(document).ready(function(){
	$('#sidr-menu-button').sidr({
	    name: 'sidr-main',
	    renaming: false,
	    speed: 300,
	    side: 'left',
	    source: '#sidr-nav'
	});
	
	$('#sidr-close').click(function() {
	    $.sidr('close', 'sidr-main');
	});
	
	$( document ).on( 'click', '#header, .main-content, #footer', function ( event ) {
	    $.sidr('close', 'sidr-main');
	} );
/* End */	    
});

jQuery(window).resize(function() {
     jQuery.sidr('close', 'sidr-main');
});
    









/* ScrollIt
-------------------------------------------------- */
jQuery(document).ready(function(){
	$.scrollIt({
	  upKey: 38,             // key code to navigate to the next section
	  downKey: 40,           // key code to navigate to the previous section
	  easing: 'linear',      // the easing function for animation
	  scrollTime: 600,       // how long (in ms) the animation takes
	  activeClass: 'active', // class given to the active nav element
	  onPageChange: null,    // function(pageIndex) that is called when page is changed
	  topOffset: 0           // offste (in px) for fixed top navigation
	});
/* End */
});









/* Parallax
-------------------------------------------------- */
jQuery(document).ready(function(){
	$.stellar({
	  // Set scrolling to be in either one or both directions
	    horizontalScrolling: false,
	    verticalScrolling: true,
	  
	    // Set the global alignment offsets
	    horizontalOffset: 0,
	    verticalOffset: 0,
	  
	    // Refreshes parallax content on window load and resize
	    responsive: true,
	  
	    // Select which property is used to calculate scroll.
	    // Choose 'scroll', 'position', 'margin' or 'transform',
	    scrollProperty: 'scroll',
	  
	    // Select which property is used to position elements.
	    // Choose between 'position' or 'transform',
	    positionProperty: 'position',
	  
	    // Enable or disable the two types of parallax
	    parallaxBackgrounds: true,
	    parallaxElements: true,
	  
	    // Hide parallax elements that move outside the viewport
	    hideDistantElements: true
	});
/* End */
});









/* Full Screen Overlay
-------------------------------------------------- */
$(document).ready(function(){
    $(".overlay-tigger a").click(function(){
        $(".canvas-overlay").fadeToggle(350);
       $(this).toggleClass('btn-open').toggleClass('btn-close');
    });
});
$('.overlay-menu, .btn-closer').on('click', function(){
    $(".canvas-overlay").fadeToggle(200);   
    $(".overlay-tigger a").toggleClass('btn-open').toggleClass('btn-close');
    open = false;
});









/* Sticky Sidebar + Headers
-------------------------------------------------- */
$(document).ready(function(){
  
  $('.sticky.pre-header').stickit({
  		scope: StickScope.Document, 
  		top: 0,
  		className: "stuck",
  		screenMinWidth: 768,
  		zIndex: 102
  });
  
  $('.sticky.main-header').stickit({
  		scope: StickScope.Document, 
  		top: 0, // Adjust for height of pre header
  		className: "stuck",
  		screenMinWidth: 768,
  		zIndex: 101
  });
  
  $('.sticky.post-header').stickit({
  		scope: StickScope.Document, 
  		top: 0, // Adjust for height of header
  		className: "stuck",
  		screenMinWidth: 768,
  		zIndex: 100
  });
  
  $('.sticky.sidebar').stickit({
  		scope: StickScope.Parent, 
  		top: 60, // Adjust for height of header
  		extraHeight: 0,
  		overflowScrolling: true,
  		className: "stuck",
  		screenMinWidth: 768,
  		zIndex: 1
  });
  
  $('.side-header.sticky').stickit({
  		scope: StickScope.Parent, 
  		top: 0, // Adjust for height of header
  		extraHeight: 0,
  		overflowScrolling: true,
  		className: "stuck",
  		screenMinWidth: 768,
  		zIndex: 1
  });
  
  
  /* End */
}); 








/* Slidebars
-------------------------------------------------- */
jQuery(document).ready(function() { 
	var controller = new slidebars();
	controller.init();
	
	$( '.left-slidebar-btn' ).on( 'click', function ( event ) {
	    event.stopPropagation();
	    controller.toggle( 'left-slidebar' );
	} );
	
	$( '.right-slidebar-btn' ).on( 'click', function ( event ) {
	    event.stopPropagation();
	    controller.toggle( 'right-slidebar' );
	} );
	
	$( '.top-slidebar-btn' ).on( 'click', function ( event ) {
	    event.stopPropagation();
	    controller.toggle( 'top-slidebar' );
	} );
	
	$( '.bottom-slidebar-btn' ).on( 'click', function ( event ) {
	    event.stopPropagation();
	    controller.toggle( 'bottom-slidebar' );
	} );
	
	$( document ).on( 'click', '.js-close-any', function ( event ) {
		//$('.sticky').stickit();
        if ( controller.getActiveSlidebar() ) {
            event.preventDefault();
            event.stopPropagation();
            controller.close();
        }
    } );
	
	// Add close class to canvas container when Slidebar is opened
    $( controller.events ).on( 'opening', function ( event ) {
        $( '[canvas]' ).addClass( 'js-close-any' );
        $( 'body' ).addClass( 'slidebars' );
        //$('.sticky').stickit('destroy');
        
    } );

    // Add close class to canvas container when Slidebar is opened
    $( controller.events ).on( 'closing', function ( event ) {
        $( '[canvas]' ).removeClass( 'js-close-any' );
        $( 'body' ).removeClass( 'slidebars' );
    } );    
	
/* End */
});









/* Countdown
-------------------------------------------------- */
$(document).ready(function() {
	// Single Clock	
	$('#clock').countdown('2020/10/10', function(event) {
		var $this = $(this).html(event.strftime(''
	     + '<span>%w</span> weeks '
	     + '<span>%d</span> days '
	     + '<span>%H</span> hr '
	     + '<span>%M</span> min '
	     + '<span>%S</span> sec'));
	 });
	 
	 // Multiple Clocks http://hilios.github.io/jQuery.countdown/documentation.html
	 $('[data-countdown]').each(function() {
	   var $this = $(this), finalDate = $(this).data('countdown');
	    $this.countdown(finalDate, function(event) {
	      $this.html(event.strftime('<span class="cd-years">%Y <span>Years</span></span>  <span class="cd-months">%m <span>Months</span></span>  <span class="cd-weeks">%w <span>Weeks</span></span>   <span class="cd-days">%d <span>Days</span></span>  <span class="cd-hours">%H <span>Hours</span></span>  <span class="cd-minutes">%M <span>Mins</span></span>  <span class="cd-seconds">%S <span>Secs</span></span>'));
	    });
	  });
	 
/* End */
}); 








/* WayPoint - Count To
-------------------------------------------------- */
jQuery(document).ready(function() { 
	$('.counter').waypoint(function() {
	    $('.counter').countTo();
	}, {
	    offset: '100%'
	});
/* End */
});







/* WayPoint - Typed
-------------------------------------------------- */
jQuery(document).ready(function() { 
	$('.type-wrap').waypoint(function() {
	    $(".typed").typed({
	        stringsElement: $('.typed-strings'),
	        typeSpeed: 0,
            startDelay: 500,
            backSpeed: 0,
            shuffle: false,
            backDelay: 500,
            loop: false,
            loopCount: false,
            showCursor: true,
            cursorChar: "|",
            attr: null,
            contentType: 'html',
	    });
	}, {
	    offset: '100%'
	});
/* End */
});
    
    
    
    




/* Toggle
-------------------------------------------------- */
$(document).ready(function() {	  
	$('.toggle-btn').click(function(e){
		e.preventDefault();
		$(this).closest('li').find('.toggle-content').not(':animated').slideToggle();
	});
	
	// Add Class for Toggle
	$(".toggle-btn").click(function () {
		$(this).toggleClass("active");
	});
/* End */
}); 







/* Accodion
-------------------------------------------------- */
$(document).ready(function() {		  
	$('.accordion-btn').click(function(e){
		e.preventDefault();
		$this = $(this);
		$thisAccordionContent = $this.closest('li').find('.accordion-content');
		var currentStatus = "";
		if ($this.attr('class').indexOf('active') != -1) {
			currentStatus = "active";
		}
		//first close all and remove active class
		$this.closest('.accordion').find('li').each(function(index) {
			$thisLi = $(this);
			$thisLi.find('.accordion-btn').removeClass('active');
			$thisLi.find('.accordion-content').slideUp('400', function() {
				$(this).removeClass('active');
			});
		});
		if (currentStatus != "active") {
			$thisAccordionContent.not(':animated').slideDown();
			$this.addClass('active');
			$thisAccordionContent.addClass('active');
		}
	});
/* End */
}); 








/* Scroll Up
-------------------------------------------------- */
$(document).ready(function() {
	$.scrollUp({
        scrollName: 'scrollUp',      // Element ID
        scrollDistance: 300,         // Distance from top/bottom before showing element (px)
        scrollFrom: 'top',           // 'top' or 'bottom'
        scrollSpeed: 200,            // Speed back to top (ms)
        easingType: 'linear',        // Scroll to top easing (see http://easings.net/)
        animation: 'fade',           // Fade, slide, none
        animationSpeed: 200,         // Animation speed (ms)
        scrollTrigger: false,        // Set a custom triggering element. Can be an HTML string or jQuery object
        scrollTarget: false,         // Set a custom target element for scrolling to. Can be element or number
        scrollText: '<em class="fa fa-angle-up"></em>', // Text for element, can contain HTML
        scrollTitle: false,          // Set a custom <a> title if required.
        scrollImg: false,            // Set true to use image
        activeOverlay: false,        // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        zIndex: 2147483647           // Z-Index for the overlay
    });
/* End */
});  	
  	
  	
 





/* Set Year
-------------------------------------------------- */
$(document).ready(function() {
    var theYear = new Date().getFullYear();
    $(".copy-year").html(theYear);
/* End */
});   
  
  
  




/* Tooltips
-------------------------------------------------- */
$(document).ready(function() {
	$('.ttip').tooltipster({
		animation: 'grow', // fade, grow, swing, slide, fall
		theme: 'tooltipster-shadow', // default, borderless, noir, punk
		trigger: 'hover',
		maxWidth: 400,
		side: 'top' // left, right, top, bottom
	});
/* End */
});   
  
       
         
  
  
  	


/* Clean Tabs Responsive tabs
-------------------------------------------------- */
$(document).ready(function() {
	$(".tab-container").cleanTabs({
		"speed": 400
	});
/* End */
}); 	
  	
  	
  	



          	
  	
  	

/* Fancybox light Boxes
-------------------------------------------------- */
$(document).ready(function() {
	$('.fancybox').fancybox(); 
/* End */
});   	
  	
  	
  	





/* Vertical Align
-------------------------------------------------- */
$(document).ready(function() {
    $('.hover-box-content, .vert-middle').flexVerticalCenter();
/* End */
});  










/* Footer Reveal
-------------------------------------------------- */
$(document).ready(function() {
	$('body.footer-reveal #footer').footerReveal({ 
		shadow: true, 
		shadowOpacity: 0.2,
		zIndex: -100 
	});  	
/* End */
});  







  
  	


/* Owl Carousel 
-------------------------------------------------- */
$(document).ready(function() {	
	
	var owl = $(".post-carousel, .post-carousel-3");
	  owl.owlCarousel({
	  	items: 3,
	    navigation : false,
	    pagination: false,
	    singleItem : false,
	    transitionStyle : false, //"fade" "backSlide" "goDown" "scaleUp" - Works only with one item on screen
	    scrollPerPage: true,
	    autoHeight : true,
	    lazyLoad : true,
	    autoPlay: 6000
	  });
	    
	  
	var owl = $(".post-carousel-2");
	  owl.owlCarousel({
	  	items: 2,
	    navigation : false,
	    pagination: false,
	    singleItem : false,
	    transitionStyle : false, //"fade" "backSlide" "goDown" "scaleUp" - Works only with one item on screen
	    scrollPerPage: true,
	    autoHeight : true,
	    lazyLoad : true,
	    autoPlay: 6000
	  }); 
	  
	  
	var owl = $(".post-carousel-4");
	  owl.owlCarousel({
	  	items: 4,
	    navigation : false,
	    pagination: false,
	    singleItem : false,
	    transitionStyle : false, //"fade" "backSlide" "goDown" "scaleUp" - Works only with one item on screen
	    scrollPerPage: true,
	    autoHeight : true,
	    lazyLoad : true,
	    autoPlay: 6000
	  }); 
	
	    
	var owl = $(".post-carousel-5");
	  owl.owlCarousel({
	  	items: 5,
	    navigation : false,
	    pagination: false,
	    singleItem : false,
	    transitionStyle : false, //"fade" "backSlide" "goDown" "scaleUp" - Works only with one item on screen
	    scrollPerPage: true,
	    autoHeight : true,
	    lazyLoad : true,
	    autoPlay: 6000
	  }); 
	  
	      
	var owl = $(".owl-carousel");
	  owl.owlCarousel({
	  	items: 5,
	    navigation : false,
	    navigationText : ["prev","next"],
	    pagination: true,
	    singleItem : false,
	    scrollPerPage: true,
	    autoHeight : true,
	    lazyLoad : true,
	    autoPlay: 3000,
	    transitionStyle : false //"fade" "backSlide" "goDown" "scaleUp" - Works only with one item on screen
	  });
	  
	  
	  	  
	// Custom Navigation Events
	$(".owl-next").click(function(){
		owl.trigger('owl.next');
	})
	$(".owl-prev").click(function(){
		owl.trigger('owl.prev');
	})  
	$(".owl-play").click(function(){
	  	owl.trigger('owl.play',1000); //owl.play event accept autoPlay speed as second parameter
	})
	$(".owl-stop").click(function(){
	  	owl.trigger('owl.stop');
	})  	
/* End */
}); 	  	
	  	
	  	






/* Owl Carousel Single
-------------------------------------------------- */
$(document).ready(function() {	
	
	var owl = $(".single-carousel");
	  owl.owlCarousel({
	  	items: 1,
	    navigation : true,
	    navigationText : ["<em class='fa fa-angle-left'>","<em class='fa fa-angle-right'>"],
	    pagination: true,
	    paginationNumbers: true,
	    singleItem : true,
	    scrollPerPage: true,
	    autoHeight : true,
	    lazyLoad : true,
	    autoPlay: 6000,
	    transitionStyle : "fadeUp" //"fade" "backSlide" "goDown" "scaleUp" - Works only with one item on screen
	  });
	  
	
/* End */
}); 









/* Owl Carousel Quotes
-------------------------------------------------- */
$(document).ready(function() {	
	
	var owl = $(".quotes-carousel");
	  owl.owlCarousel({
	  	items: 1,
	    navigation : false,
	    navigationText : ["<em class='fa fa-angle-left'>","<em class='fa fa-angle-right'>"],
	    pagination: true,
	    paginationNumbers: false,
	    singleItem : true,
	    scrollPerPage: true,
	    autoHeight : true,
	    lazyLoad : true,
	    autoPlay: 4000,
	    transitionStyle : "fade" //"fade" "backSlide" "goDown" "scaleUp" - Works only with one item on screen
	  });
	  
	
/* End */
}); 
	
	
	
	
	
	
	
	
/* Owl Carousel Events
-------------------------------------------------- */
$(document).ready(function() {	
	
	var owl = $(".event-calendar-carousel");
	  owl.owlCarousel({
	  	items: 1,
	    navigation : false,
	    pagination: false,
	    paginationNumbers: true,
	    singleItem : true,
	    scrollPerPage: true,
	    autoHeight : true,
	    lazyLoad : true,
	    autoPlay: false,
	    transitionStyle : "fade" //"fade" "backSlide" "goDown" "scaleUp" - Works only with one item on screen
	  });
	  
	  
	  // Custom Navigation Events
	  $(".event-next").click(function(){
	  	owl.trigger('owl.next');
	  })
	  $(".event-prev").click(function(){
	  	owl.trigger('owl.prev');
	  })  
	
/* End */
});   	







/* Fitvids
-------------------------------------------------- */
$(document).ready(function() {
	$(".container").fitVids();
/* End */
}); 








/* Images Loaded
-------------------------------------------------- */
$(document).ready(function() {
	$('.container').imagesLoaded( function() {});

/* End */
}); 






	  	
	
/* Isotope 
-------------------------------------------------- */
$(document).ready(function() {	
	var $grid = $('.grid').isotope({
	  	// options
	  	itemSelector: '.grid-item',
	  	percentPosition: true,
	  	stagger: 30,
	  	masonry: {
	    columnWidth: '.grid-sizer',
	    gutter: '.gutter-sizer'
	  }
	});
	
	// filter items on button click
	$('.filter-button-group').on( 'click', 'a', function() {
	  var filterValue = $(this).attr('data-filter');
	  $grid.isotope({ filter: filterValue });
	  $('.filter-button-group a').removeClass('active');
	  $(this).addClass("active");
	});
	
/* End */
}); 	
	







/* Sky Forms - Date Picker
-------------------------------------------------- */
jQuery(document).ready(function() {
	// Regular datepicker
	$('#date').datepicker({
		dateFormat: 'dd.mm.yy',
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>'
	});
	
	// Date range
	$('#start').datepicker({
		dateFormat: 'dd.mm.yy',
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>',
		onSelect: function( selectedDate )
		{
			$('#finish').datepicker('option', 'minDate', selectedDate);
		}
	});
	$('#finish').datepicker({
		dateFormat: 'dd.mm.yy',
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>',
		onSelect: function( selectedDate )
		{
			$('#start').datepicker('option', 'maxDate', selectedDate);
		}
	});
	
	// Inline datepicker
	$('#inline').datepicker({
		dateFormat: 'dd.mm.yy',
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>'
	});
	
	// Inline date range
	$('#inline-start').datepicker({
		dateFormat: 'dd.mm.yy',
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>',
		onSelect: function( selectedDate )
		{
			$('#inline-finish').datepicker('option', 'minDate', selectedDate);
		}
	});
	$('#inline-finish').datepicker({
		dateFormat: 'dd.mm.yy',
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>',
		onSelect: function( selectedDate )
		{
			$('#inline-start').datepicker('option', 'maxDate', selectedDate);
		}
	});
	
	
	// Regular slider
	$('.regular-slider').slider({
		min: 0,
		max: 500,
		slide: function(event, ui)
		{
			$('#slider1-value').text(ui.value);
		}
	});
		
	// Range slider
	$('.range-slider').slider({
		min: 0,
		max: 500,
		range: true,
		values: [75, 300],
		slide: function(event, ui)
		{
			$('#slider2-value1').text(ui.values[0]);
			$('#slider2-value2').text(ui.values[1]);
		}
	});
		
	// Step slider
	$('.step-slider').slider({
		min: 0,
		max: 500,
		step: 100,
		slide: function(event, ui)
		{
			$('#slider3-value').text(ui.value);
		}
	});
/* End */
});  








/* Header Reveal - headheasive
-------------------------------------------------- */

// Set options
var options = {
    offset: 200,
    offsetSide: 'top',
    classes: {
        clone:   'header-reveal--clone',
        stick:   'header-reveal--stick',
        unstick: 'header-reveal--unstick'
    }
};

// Initialise with options
var banner = new Headhesive('.header-reveal', options);
// Headhesive destroy
// banner.destroy();







/* List.js Sortable Tables/Lists
-------------------------------------------------- */
var options = {
  valueNames: [ 'faq', 'born' ]
};

var userList = new List('list-sort', options);