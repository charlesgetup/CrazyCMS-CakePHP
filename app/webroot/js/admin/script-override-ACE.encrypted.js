/**
 * Override ACE demo functions (The override functions often need to be run right away.)
 */

// Ajax load content
window.ajaxLoadContent = function () {
	if(!$.fn.ace_ajax){ return; }
	 
	var defaultHash = '/admin/dashboard/view';
	var errorPageHash = '#/admin/error/404';
	var contentAreaSelector = '[data-ajax-content=true]';
	
	if($(contentAreaSelector).length < 1){
		return;
	}
	
	if(window.Pace) {
		window.paceOptions = {
			ajax: true,
			document: true,
			eventLag: false
		};
	}

	var updatePageTitle = function(hash, url, linkText){
		var $contentArea = $(contentAreaSelector);
		var $title = $contentArea.find('.ajax-append-title');
		var extra = false;
		if($title.length > 0) {
			linkText = $title.text();
			$title.remove();
		}else if(linkText.length > 0) {
			extra = $.trim(String(document.title).replace(/^(.*)[\-]/, ''));//for example like " - CrazySoft"
			if(extra){ extra = ' - ' + extra; }
			linkText = $.trim(linkText);
		}
		
		// Set page <title> tag
		document.title = linkText + (extra ? extra : ''); 
		
		// Set page title/header
		var pageTitleEle = $contentArea.closest('.page-content-area').children('.page-header'); 
		if(pageTitleEle.length){
			pageTitleEle.html('<h1>' + linkText + '<h1>');
			var pageDescription = $('a[data-url="'+hash+'"]').attr('data-description');
			if(pageDescription != undefined && pageDescription != null && pageDescription != ""){
				pageTitleEle.append('<small><i class="ace-icon fa fa-angle-double-right"></i>' + pageDescription + '</small>');
			}
		}
	};
	
	var ajaxOptions = {
		 'close_active': true,
		 'update_title': function(hash, url, linkText){ updatePageTitle(hash, url, linkText); },
		 'default_url': defaultHash,
		 'content_url': function(hash) {
			if( !hash.match(/^\/admin\//) ){ return false; }
			var path = (hash.match(/^\/admin\/dashboard/)) ? (hash.toLowerCase().indexOf('faq') > 0 ? hash : defaultHash) : hash; // Put FAQ page in Dashboard instead of create another controller for it
			var currentUrlPath = document.location.pathname;
			
			// User can directly enter the URL in the address bar. If so, reload the page and put user input to the hash part and this can correct the breadcrumb
			if(currentUrlPath.match(/^\/admin\//) && !currentUrlPath.match(/^\/admin\/dashboard/) && path != currentUrlPath){

				path = defaultHash.replace("/view", "") + '#' + currentUrlPath;
				window.location.href = path;
				return false;
			}

			return path;
		  },
		  'max_load_wait': 4
	};
	   
	// for IE9 and below we exclude PACE loader (using conditional IE comments)
	// for other browsers we use the following extra ajax loader options
	if(window.Pace) {
		ajaxOptions['loading_overlay'] = 'body';//the opaque overlay is applied to 'body'
	}
	
	// initiate ajax loading on this element( which is .page-content-area[data-ajax-content=true] in Ace's demo)
	$(contentAreaSelector).ace_ajax(ajaxOptions);

	// if general error happens and ajax is working, let's stop loading icon & PACE
	$(window).on('error.ace_ajax', function() {
		$(contentAreaSelector).each(function() {
			var $this = $(this);
			if( $this.ace_ajax('working') ) {
				if(window.Pace && Pace.running){ Pace.stop(); }
				$this.ace_ajax('stopLoading', true);
			}
		});
	});
	
	/*
	 * Register ajax loading content related event
	 */
	
	// Ajax load start event
	$( "body" ).delegate( contentAreaSelector, "ajaxloadstart", function ( event, params ) {
		
	});
	
	// Ajax load error event
	$( "body" ).delegate( contentAreaSelector, "ajaxloaderror", function ( event, params ) {
		event.preventDefault();
		var cache = true;
		$(contentAreaSelector).ace_ajax('stopLoading', true);
		window.location.href = '/errors/404';
	});
	
	// Ajax load complete event
	$( "body" ).delegate( contentAreaSelector, "ajaxloadcomplete", function ( event, params ) {
		event.preventDefault();

		/* Resize iframe in plain page tabs (not in Bootbox) */
	    initIframeResizeInPlainPage();
	});
};
window.ajaxLoadContent();

// This event may happen before the "exceptionHandler" function loaded and that is why we cannot call that function here
window.onerror = function(error) {
	
	if(error && error.match){
		
		// This may happen when minified JS is loaded after the JS in <script> tag
		var notAFunctionError = error.match(/TypeError:(.+)is not a function/ig);
		if(notAFunctionError || error == "Script error."){
			window.location.reload(true); 
		}
		
	}
	
	// Handle other errors
	if(DEBUG && DEBUG === true){
		console.log(error);
		console.trace();
	}
};