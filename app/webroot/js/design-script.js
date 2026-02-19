$(function() {
	
	// IPad/IPhone
	var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]'); 
	var ua = navigator.userAgent;

	var gestureStart = function() {
		viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6";
	};

	var scaleFix = function() {
		var isMobile = /iPhone|iPad/.test(ua);
		var isOpera = /Opera Mini/.test(ua);
		if (viewportmeta && isMobile && !isOpera) {
			viewportmeta.content = "width=device-width, minimum-scale=1.0, maximum-scale=1.0";
			document.addEventListener("gesturestart", gestureStart, false);
		}
	};

	scaleFix();
	
	// Menu Android
	if (window.orientation != undefined) {
		var regM = /ipod|ipad|iphone/gi;
		var result = ua.match(regM);
		if (!result) {
			$('.sf-menu li').each(function() {
				if ($(">ul", this)[0]) {
					$(">a", this).toggle(function() {
						return false;
					}, function() {
						window.location.href = $(this).attr("href");
					});
				}
			});
		}
	}
	
	// Set up RD Nav
	var o = $('.rd-navbar');
    o.RDNavbar({
    	responsive: {
    		0: {
    			layout: 'rd-navbar-fixed',
    			stickUp: false
    		},
    		1010: {
    			layout: 'rd-navbar-static',
    			stickUp: true,
    			stickUpClone: false,
    			stickUpOffset: '100%'
    		}
    	}
    });
    
    // Display rating
    if($('.rating').length){
    	$('.rating').each(function(){
    		var that = this;
    		$(that).raty({ 
    			starType: 'i',
    			readOnly: true,
    			score: $(that).data("rating")
    		});
    	});
    }
});

var ua = navigator.userAgent.toLocaleLowerCase();
var regV = /ipod|ipad|iphone/gi;
var result = ua.match(regV);
var userScale = "";
if (!result) {
	userScale = ",user-scalable=0";
}
document.write('<meta name="viewport" content="width=device-width,initial-scale=1.0' + userScale + '">');