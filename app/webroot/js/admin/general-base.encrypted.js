/**
 * Base functions 
 */

window.gotoHomePage = function(){
	var homePageUrl = "/";
	var currentUrlPath = document.location.pathname;
	if(currentUrlPath.match(/^\/admin\//)){
		homePageUrl = '/admin\/dashboard';
	}
	window.location.href = homePageUrl;
	return false;
};

window.showLoaddingSpinner = function(target){
	// target is a HTML element which is used as a position reference for the spinner
	if(target != undefined && target != '' && target != null){
		if($(target).siblings('.inline-loading-spinner').length){
			$(target).siblings('.inline-loading-spinner').css('display', 'inline-block');
		}else{
			$('<img src="/img/admin/loading.gif" class="inline-loading-spinner" />').insertAfter($(target));
		}
	}
};

window.hideLoaddingSpinner = function(target){
	// target is a HTML element which is used as a position reference for the spinner
	if(target != undefined && target != '' && target != null){
		$(target).siblings('.inline-loading-spinner').css('display', 'none');
	}
};

window.formatDateTime = function(dateTimeStr){
	var ms = parseInt(Date.parse(dateTimeStr));
	return (new Date(ms)).toString().split(" GMT")[0];
};

window.getCurrentTimestamp = function(){
	return formatDateTime(Date().split(" GMT")[0]);
};

window.ajaxErrorHandler = function(jqXHR, textStatus, errorThrown){
	
	if(DEBUG && DEBUG === true){
		console.trace();
		console.log(jqXHR);
	    console.log(textStatus);
	    console.log(errorThrown);
	}
};

window.exceptionHandler = function(err){
	
	if(DEBUG && DEBUG === true){
		console.log(err);
		console.trace();
	}
};

window.validateJSONString = function(str){
	return /^[\],:{}\s]*$/.test(str.replace(/\\["\\\/bfnrtu]/g, '@').
			replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
			replace(/(?:^|:|,)(?:\s*\[)+/g, ''));
};

// Clone one object to another, and if you modify the original object, the cloned one won't be affected
window.clone = function (obj) {
    if (null == obj || "object" != typeof obj) return obj;
    var copy = obj.constructor();
    for (var attr in obj) {
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
    }
    return copy;
};

//Remove JS comments
window.removeComments = function (str) {
    str = ('__' + str + '__').split('');
    var mode = {
        singleQuote: false,
        doubleQuote: false,
        regex: false,
        blockComment: false,
        lineComment: false,
        condComp: false 
    };
    for (var i = 0, l = str.length; i < l; i++) {
 
        if (mode.regex) {
            if (str[i] === '/' && str[i-1] !== '\\') {
                mode.regex = false;
            }
            continue;
        }
 
        if (mode.singleQuote) {
            if (str[i] === "'" && str[i-1] !== '\\') {
                mode.singleQuote = false;
            }
            continue;
        }
 
        if (mode.doubleQuote) {
            if (str[i] === '"' && str[i-1] !== '\\') {
                mode.doubleQuote = false;
            }
            continue;
        }
 
        if (mode.blockComment) {
            if (str[i] === '*' && str[i+1] === '/') {
                str[i+1] = '';
                mode.blockComment = false;
            }
            str[i] = '';
            continue;
        }
 
        if (mode.lineComment) {
            if (str[i+1] === '\n' || str[i+1] === '\r') {
                mode.lineComment = false;
            }
            str[i] = '';
            continue;
        }
 
        if (mode.condComp) {
            if (str[i-2] === '@' && str[i-1] === '*' && str[i] === '/') {
                mode.condComp = false;
            }
            continue;
        }
 
        mode.doubleQuote = str[i] === '"';
        mode.singleQuote = str[i] === "'";
 
        if (str[i] === '/') {
 
            if (str[i+1] === '*' && str[i+2] === '@') {
                mode.condComp = true;
                continue;
            }
            if (str[i+1] === '*') {
                str[i] = '';
                mode.blockComment = true;
                continue;
            }
            if (str[i+1] === '/') {
                str[i] = '';
                mode.lineComment = true;
                continue;
            }
            mode.regex = true;
 
        }
 
    }
    return str.join('').slice(2, -2);
};

// Check current web page is loaded inside an iframe or not
window.inIframe = function(curWin) {
	if(curWin == undefined || curWin == null || curWin == ""){
		curWin = window; 
	}
    try {
        return curWin.self !== window.top;
    } catch (e) {
    	return true;
    }
};

// Mouse click a link
window.actuateLink = function (link){
   var allowDefaultAction = true;
      
   if (link.click){
      link.click();
      return;
   }else if (document.createEvent){
      var e = document.createEvent('MouseEvents');
      e.initEvent(
         'click'     // event type
         ,true      // can bubble?
         ,true      // cancelable?
      );
      allowDefaultAction = link.dispatchEvent(e);    
   }
   
   if (allowDefaultAction){
	  //TODO submit a custom form may cause security problem
      var f = document.createElement('form');
      f.action = link.href;
      document.body.appendChild(f);
      f.submit();
   }
};

// Json to string
JSON.stringify = JSON.stringify || function (obj) {
    var t = typeof (obj);
    if (t != "object" || obj === null) {
        // simple data type
        if (t == "string"){ obj = '"'+obj+'"'; }
        return String(obj);
    }
    else {
        // recurse array or object
        var n, v, json = [], arr = (obj && obj.constructor == Array);
        for (n in obj) {
            v = obj[n]; t = typeof(v);
            if (t == "string"){ 
            	v = '"'+v+'"'; 
            }else if (t == "object" && v !== null) {
            	v = JSON.stringify(v);
            }
            json.push((arr ? "" : '"' + n + '":') + String(v));
        }
        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
};

// Check whether the variable is a function
window.isFunction = function (functionToCheck) {
	var getType = {};
	return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
};

// Dynamic Script Loading
window.loadAndRunJSScript = function (url, iframeElement, callback) {
	
	$.ajax({
		
        async: false,
        cache: false,
        url: url,
        dataType: "script"
        	
    }).done(function( script, textStatus ) {

    	// Run callback function when ajax is successfully done
        if(isFunction(callback)){
        	callback();
        }
        
    }).fail(function( jqxhr, settings, exception ) {
        
    	// Fall back to JS method to load the script
    	var documentElement = (iframeElement != undefined && iframeElement != null && iframeElement != '') ? iframeElement.contentWindow.document : document;
    	
        // Adding the script tag to the head as suggested before
        var head = documentElement.getElementsByTagName('head')[0];
        var script = documentElement.createElement('script');
        script.type = 'text/javascript';

        // Then bind the event to the callback function.
        // There are several events for cross browser compatibility.
        if(isFunction(callback)){
        	if(script.readyState){
        		script.onreadystatechange = function(){
                    if (script.readyState == "loaded" || script.readyState == "complete"){
                        script.onreadystatechange = null;
                        callback();
                    }
                };
        	}else{
        		script.onload = function(){
                    callback();
                };
        	}
        }

        // Fire the loading
        script.src = url;
        head.appendChild(script);
    	
    });
	
};

//Find top window inside iframe
window.findTopWindow = function(){
	var topWin 	= window.top;
	var selfWin = window.self;
    while(topWin != selfWin){
    	topWin 	= topWin.top;
    	selfWin	= selfWin.top;
    }
    return topWin;
};

// Find parent window (inside an iFrame)
window.findParentWindow = function(curWin){
	if(curWin == undefined || curWin == null || curWin == ""){
		curWin = window; 
	}
    return inIframe(curWin) ? curWin.parent : curWin;
};

// Find parent window which has "parentIFrame" (iFrameResize) attribute. 
window.findParentWindowHasParentIFrameAttr = function(){
	var win = window.parent;
	while(win.parentIFrame == undefined && inIframe()){
    	win = win.parent;
    }
    return win;
};

//Find iframe from parent window
//@param win (window object)
//@param selector (css selector)
window.findIframe = function(win, selector){
	var iframe = false;
	var allIframes = $(win.document).find("iframe");
	if(allIframes.length){
		var iframe = $(win.document).find(selector);
		if(iframe.length == 0){
			for(var i = 0; i < allIframes.length; i++){
				iframe = findIframe(allIframes[i].contentWindow, selector);
				if(iframe !== false && iframe.length > 0){
					break;
				}
			}
		}
	}
	return iframe;
};

//Find Iframe object from inside
window.findSelfiFrame = function(insideiFrameElementSelector, curWin){
	if(insideiFrameElementSelector == undefined || insideiFrameElementSelector == null || insideiFrameElementSelector == ""){
		return false;
	}
	if(curWin == undefined || curWin == null || curWin == ""){
		curWin = window; 
	}
	var iframes = curWin.parent.document.getElementsByTagName('iframe'), ifr, i = 0, id = null;
	while(ifr = iframes[i++]){
		if($(ifr.contentWindow.document).find(insideiFrameElementSelector).length){ 
			break; 
		}
	}
	return ifr;
};

// Find window object which contains provided object (selector)
window.findWinByElementInside = function(insideElementSelector, curWin){
	if(insideElementSelector == undefined || insideElementSelector == null || insideElementSelector == ""){
		return false;
	}
	if(curWin == undefined || curWin == null || curWin == ""){
		curWin = window; 
	}
	while($(curWin.document).find(insideElementSelector).length < 1){
		curWin = curWin.parent;
	}
	return curWin;
};

//Create popup message box
/* 
 * Message format: (only "status" and "message" are required)
 * 		{
 * 			status: SUCCESS/ERROR/WARNING, 
 * 			message: "message content (can be HTML)", 
 * 			css_class: "special css class, the class(es) will be appended to the existing css class, the class(es) cannot override the existing class", 
 * 			title: "customize title, set it to empty to clear the box title. If this attribute is missing, default title will be used.", 
 * 			sticky: true/false, 
 * 			after_close: function(){ \/* callback *\/ }
 *		}
 */
window.messageBox = function(data){
	if(data.message && data.status){
		var cssClass = "gritter-warning";
		var title = "Hey";
		
		var afterClose = (data.after_close && jQuery.isFunction(data.after_close)) ? data.after_close : null;
		if(afterClose == null && (typeof data.after_close) == 'string' && data.after_close.indexOf('function(') == 0){
			eval('afterClose = ' + data.after_close); // Sometimes we passed JS function in JSON as a string. Then we need to convert that string back to function here.
		}else{
			afterClose = function(){};
		}
		
		var beforeOpen = (data.before_open && jQuery.isFunction(data.before_open)) ? data.before_open : null;
		if(beforeOpen == null && (typeof data.before_open) == 'string' && data.before_open.indexOf('function(') == 0){
			eval('beforeOpen = ' + data.before_open); // Sometimes we passed JS function in JSON as a string. Then we need to convert that string back to function here.
		}else{
			beforeOpen = function(){};
		}
		
		switch(data.status){
		case SUCCESS:
			cssClass = "gritter-success";
			title = "Success";
			break;
		case ERROR:
			cssClass = "gritter-error";
			title = "Alert";
			break;
		case WARNING:
			cssClass = "gritter-warning";
			title = "Hey";
			break;
		}
		title = data.title ? data.title : title;
		cssClass = cssClass + " gritter-center " + (data.css_class ? data.css_class : "");

		$.gritter.removeAll();
		$.gritter.add({
		    "title": title,
		    "text": data.message,
		    "image": '',
		    "sticky": (data.sticky == true),
		    "time": '8000',
		    "class_name": cssClass,
		    "before_open": beforeOpen,
		    "after_close": afterClose
		});
	}
};

// Format raw error message
window.generateErrorMessage = function (errJson){
	var errors = [];
	errJson = $.parseJSON(errJson);
	for(var key in errJson){
		if(key == "" || key == null){
			continue;
		}
		var value 		= errJson[key];
		var fieldName 	= key+":    ";
		var errorMsg 	= "";
		if( Object.prototype.toString.call( value ) === '[object Array]' ) {
			$.each(value, function(index, msg){
				errorMsg += '<div style="display:inline-block;">'+msg+'</div><br />';
			});
		}else{
			errorMsg = '<div style="display:inline-block;">'+value+'</div><br />';
		}
        errors.push('<div style="width:160px;display:inline-block;font-weight:bold;">'+fieldName+'</div>'+errorMsg);
	}
	if(errors.length){
		var message = (errors.length == 1)
	        ? '1 error occurred.'
	        : errors.length + ' errors occurred.';
	    errors.splice(0, 0, '<div>'+message+'</div>');
        var errorMessage = errors.join('');
        if(inIframe()){
        	if ('parentIFrame' in window){ 
        		window.parentIFrame.sendMessage({"status": ERROR, "message": errorMessage}); 
        	}
        }else{
        	messageBox({"status": ERROR, "message": errorMessage});
        }
    }
};

window.getParameterByName = function (name, searchStr) {
	if(searchStr == undefined || searchStr == null || searchStr == ""){
		searchStr = location.search;
	}
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
    var results = regex.exec(searchStr);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
};

window.getURLRelativePath = function(absoluteURL){
	if(absoluteURL == undefined || absoluteURL == null || absoluteURL == ""){
		absoluteURL = window.location.href;
	}
	return absoluteURL.replace(/^(?:\/\/|[^\/]+)*\//, "");
};

window.getFormHolderIframe = function(iframeId){
    var innerIframeContent = $(document);
    if((typeof iframeId) == "object"){
        for(var i = 0; i < iframeId.length; i++){
            innerIframeContent = innerIframeContent.find('iframe#' + iframeId[i]).contents();
        }
    }else{
        innerIframeContent = innerIframeContent.find('iframe#' + iframeId).contents();
    }
    return innerIframeContent;
};

window.getIframeIdsByEvent = function(event){
	var submitBtn = event.target;
    var iframeId = $(submitBtn).attr("data-iframeId");
    if(iframeId == undefined || iframeId == null || iframeId == ""){
    	if($(submitBtn).closest("div.modal-footer")){
    		var iframe = $(submitBtn).closest("div.modal-footer").siblings("div.modal-body").find("iframe:first");
    		iframeId   = iframe.attr("id");
    		
    		// Fix iframe which doesn't have an "id" attribute
    		if((iframeId == undefined || iframeId == null || iframeId == "") && iframe.length){
    			var tempId = "id_"+(new Date()).getTime();
    			iframe.attr("id", tempId);
    			iframeId = tempId;
    		}
    	}
    }
    var submitFormId = $(submitBtn).attr("data-submitFormId");
    if(submitFormId == undefined || submitFormId == null || submitFormId == ""){
    	if(iframeId != undefined && iframeId != null && iframeId != ""){
    		submitFormId = getFormHolderIframe(iframeId).find("form:first").attr("id");
    	}
    }
    if(iframeId && submitFormId){
    	return { "iframeId": iframeId, "iframeFormId": submitFormId};
    }else{
    	return false;
    }
};

window.submitIframeForm = function(event, isAjax){
	if(!isAjax){
		isAjax = false;
	}
	var submitBtn = event.target;
    var iframeIds = getIframeIdsByEvent(event);
    if(iframeIds){
    	$( submitBtn ).trigger( "submitIframeFormFromParentWindowEvent", [iframeIds.iframeId, iframeIds.iframeFormId, isAjax] );
    }
};

window.ajaxSubmitIframeForm = function(event){
	var isAjax = true;
	showLoaddingSpinner(event.target);
	setTimeout(function(){
		submitIframeForm(event, isAjax); // Delay this function in order to see the spinner
	}, 100);
};

window.resetIframeForm = function(event){
	var resetBtn = event.target;
    var iframeIds = getIframeIdsByEvent(event);
    if(iframeIds){
    	$( resetBtn ).trigger( "resetIframeFormFromParentWindowEvent", [iframeIds.iframeId, iframeIds.iframeFormId] );
    }
};

// Manually adjust data table (column's width)
window.manuallyAdjustDataTable = function(){
	var tables = $.fn.dataTable.tables( true );
	if(tables.length){
		for(var i = 0; i < tables.length; i++){
			$( tables[i] ).DataTable().draw(); //TODO Reload the table for now. Find a better solution which doesn't need to query the DB 
		}
	}
};

// make sure chrome & safari iframe has vertical scroll bar
window.enableIFrameScrollBar = function(iframe){
	
	// Whether to show scroll bar based on how iframe scrolling attr is set.
	var noScroll = $(iframe).attr('scrolling');
	if(noScroll == undefined || noScroll == null || noScroll == ""){
		if(iframe.iframe){
			noScroll = iframe.iframe.scrolling;
		}else{
			noScroll = iframe.scrolling;
		}
		noScroll = (noScroll == undefined || noScroll == null || noScroll == "") ? false : (noScroll.toLowerCase() == 'no');
	}else{
		noScroll = noScroll.toLowerCase() == 'no';
	}
	
	try{
		if(!noScroll){
			var iframeHeight = $(iframe.iframe).css('max-height');
//			$(iframe.iframe).contents().find('html').css({'max-height': iframeHeight, 'height': iframeHeight, 'overflow-y': 'scroll'});
//			$(iframe.iframe).contents().find('body').css({'max-height': iframeHeight, 'height': iframeHeight, 'overflow-y': 'scroll'});
			$(iframe.iframe).contents().find('html').css({'height': 'fit-content', 'overflow-y': 'scroll'});
			$(iframe.iframe).contents().find('body').css({'height': 'fit-content', 'overflow-y': 'scroll'});
		}
	}catch(err){
		exceptionHandler(err); // Error: TypeError: Cannot read property 'defaultView' of undefined. This is about jQuery context - (var) iframe 
	}
};

// Check given string is valid URL or not
window.validURL = function(str){
	var pattern = /^(https?:\/\/)?((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|((\d{1,3}\.){3}\d{1,3}))(\:\d+)?(\/[-a-z\d%_.~+]*)*(\?[;&a-z\d%_.~+=-]*)?(\#[-a-z\d_]*)?$/i; 
    if(!pattern.test(str)) {
	    return false;
	} else {
	    return true;
	}
};

// Check given string is absolute path or not
window.isPathAbsolute = function (path) {
	return /^(?:\/|[a-z]+:\/\/)/.test(path);
};

// Resize iframe in plain pages (not in Bootbox)
window.initIframeResizeInPlainPage = function (){
	
	// First, make sure there is iframe in the current page
	if(document.documentElement.innerHTML.indexOf("<iframe ")){
		
		var resizeIframe = function (currentIframe){
			var isOldIE = (navigator.userAgent.indexOf("MSIE") !== -1); // Detect IE10 and below

			if(validURL(currentIframe.src) || isPathAbsolute(currentIframe.src)){
				$(currentIframe).iFrameResize({
	                log                     : false, // Enable console logging
	                enablePublicMethods     : true,
	                heightCalculationMethod : isOldIE ? 'max' : 'lowestElement',
	                checkOrigin				: true,
	                scrolling				: false, // No scroll, then we have to use full height in order to display all content
	                initCallback			: function(iframe){
	                	initIframe(iframe, this);
	                },
	                messageCallback         : function(data){
	                	postMessageReceiver(data);
	                },
	                resizedCallback			: function(iframe,height,width,type){
	                	enableIFrameScrollBar(iframe);
	                }
	        	});
			}
		};
		
		$(document).find("iframe").filter(function(){ return $(this).hasId("iFrameResizer", true) === false; }).each(function(){
			
			var currentIframe = this;
			
			resizeIframe(currentIframe);
			
			if(inIframe() && this.contentWindow.manuallyAdjustDataTable){
				this.contentWindow.manuallyAdjustDataTable();
			}
        	
        });
		
	}
};

//Initialise an iframe
window.initIframe = function(iframe, resizer){
	if(iframe){
		
		// Make sure the iframe has an ID
		var iframeCssId = iframe.id;
		if(iframeCssId == undefined || iframeCssId == null || iframeCssId == ""){
			iframe.id = "iframe_temp_id_" + (new Date().getTime());
		}

		var winWidth 		= $(window).outerWidth();
		var winHeight 		= $(window).outerHeight();

		// Adjust model popup window width
		var modelWinWidth = $(iframe).closest(".modal-dialog").outerWidth();
		var maxPopupWinWidth = winWidth * 0.9;
		if(modelWinWidth < maxPopupWinWidth){
			$(iframe).closest(".modal-dialog").outerWidth(maxPopupWinWidth);
		}
		
		// Set little delay for the following code to give browser more time to process and avoid JS error
		setTimeout(function() {
			
			// Adjust Height & Width of iframe which has (will have) an embeded iframe
			if(resizer != undefined && resizer != null && resizer != "" && ($(iframe).contents().find('table.dataTable').length || inIframe())){
				resizer.maxWidth 	= winWidth;
				resizer.maxHeight 	= winHeight;
			}		
	
			// Manually adjust Height & Width of (embeded iframe) un-processed iframe
			if((resizer == undefined || resizer == null || resizer == "") /* && $(iframe).hasId("iFrameResizer", true) === false */){

				// Some client with slow Internet connection may need more time to load everything in the web page.
				var timer;
				var adjustLoadedIframeHeight = function(){
					if($(iframe).contents().find('body').get(0) != undefined && $(iframe).contents().find('.page-content-area').first().html() /* && $(iframe).contents().find('table.table.dataTable').first().find('td').length */){
						clearInterval(timer);
						$(iframe).css({'overflow': 'auto'});
						var h = $(iframe).contents().find('body').first().height();
						if($(iframe).contents().find('body').first().find('img[data-action="zoom"]').length){
							$(iframe).css({'max-width': '100%', 'max-height': h + 'px', 'width': '100%', 'height': h + 'px'}); // in order to keep the original page layout, set max-height as height
						}else{
							$(iframe).css({'max-width': '100%', 'max-height': '100%', 'width': '100%', 'height': h + 'px'});
						}
					}
				};
				timer = setInterval(function(){ adjustLoadedIframeHeight(); }, 200);
				
			}
			
			// Apply the height styles to iframe body element
			enableIFrameScrollBar(iframe);
		
		}, 800);
		
		// Change iframe dialog action button
		if($(iframe).closest('.modal-body') && $(iframe).closest('.modal-body').next('.modal-footer')){
			var actionbtnStatus = getParameterByName('action', iframe.contentWindow.location);
			switch(actionbtnStatus){
				case "no-submit":
					if(!$(iframe).closest('.modal-body').next('.modal-footer').find('.click-reload-top-window').length){
						$(iframe).closest('.modal-body').next('.modal-footer').html('<button data-bb-handler="Close" type="button" class="btn btn-sm btn-sm click-reload-top-window">Close</button>');
						$('.click-reload-top-window').bind('click', function(){
	                        window.top.location.reload();
	                        return false;
	                    });
					}
					break;
			}
		}
		
	}
};

// Process post messages
window.postMessageReceiver = function(data){
	/* Process action request at parent window */
	if(data.message.action){
		switch(data.message.action){
    		case "SWITCH_ADD_EDIT_TITLE":
    			var action = "add";
    			if(data.message.data && data.message.data.indexOf("edit") > 0){
    				action = "edit";
    			}
    			var titleObj = $('.bootbox.modal[role="dialog"]').filter(function(){ return $(this).css("display") == "block";}).first().find('.modal-title:first');
    			if(titleObj.length == 0){
    				// If the source iframe is not the child of current DOM, find the iframe first and then locate the "title" element
    				if(data.message.sourceIframeId){
    					var iframe = findIframe(window, 'iframe#'+data.message.sourceIframeId);
    					if(iframe !== false && iframe.length > 0){
    						titleObj = $(iframe[0]).closest("body").find('.bootbox.modal[role="dialog"]').filter(function(){ return $(this).css("display") == "block";}).first().find('.modal-title:first');
    					}
    				}else{
    					break;
    				}
    			}    			
    			var title = titleObj.text().trim();
    			var actionInTitle = title.split(" ")[0];
    			if(actionInTitle.toLowerCase() != action){
    				title = title.replace(actionInTitle, action.capitalizeFirstLetter());
    			}
    			titleObj.text(title);
    			break;
		}
	}
	
	/* Display message in parent window */
	if(data.message.status && data.message.message){
		messageBox(data.message);
	}
};

//Load Tinymce
window.loadTinymce = function(selector){
	// Editor may be in a popup element, and this element display style could be "none".
    // If we init editor while the container has style "display: none", the editor may not be loaded correctly.
    // Wait some time, until the wrapper contain display style is not "none", then initialise the editor
    
    var editorTimer                     = null;
    var displayStatusChangedElements    = new Array();
    
    function initEditor(selector){
        
        if($(selector).length){
            var needRecheck = false;
            $( selector ).parents().each(function(){
                var displayStatus = $(this).css("display");
                if(displayStatus != undefined && displayStatus != null && displayStatus != "" && displayStatus == "none"){
                    needRecheck = true;
                    return false;
                }else if(displayStatus == undefined || displayStatus == null || displayStatus == ""){
                    $(this).css("display", "block");
                    displayStatusChangedElements.push($(this).get(0));
                }
            });

            var iframeWindow = window.frameElement;
            if(!needRecheck && iframeWindow != undefined && iframeWindow != null && iframeWindow != ""){
                var displayStatus = $(iframeWindow).css("display");
                if(displayStatus != undefined && displayStatus != null && displayStatus != "" && displayStatus == "none"){
                    needRecheck = true;
                }else if(displayStatus == undefined || displayStatus == null || displayStatus == ""){
                    $(iframeWindow).css("display", "block");
                    displayStatusChangedElements.push(iframeWindow);
                }
                if(!needRecheck){
                    $( iframeWindow ).parents().each(function(){
                        var displayStatus = $(this).css("display");
                        if(displayStatus != undefined && displayStatus != null && displayStatus != "" && displayStatus == "none"){
                            needRecheck = true;
                            return false;
                        }else if(displayStatus == undefined || displayStatus == null || displayStatus == ""){
                            $(this).css("display", "block");
                            displayStatusChangedElements.push($(this).get(0));
                        }
                    });
                }
            }

            if(!needRecheck){
            	tinymce.init({
                    selector: selector,
                    width: $(selector).width() + 16,
                    browser_spellcheck: true,
                    remove_script_host : false,
                    convert_urls : false,
                    plugins: [
                        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                        "insertdatetime table contextmenu paste filemanager"
                    ],
                    image_advtab: true,
                    menubar: true,
                    toolbar: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fullscreen",
                    setup: function(editor){
                    	editor.on('change', function(){
                    		editor.save();
                    	});
                    },
                    init_instance_callback: function(){
                        if(displayStatusChangedElements.length){
                            for(var i = 0; i < displayStatusChangedElements.length; i++){
                                displayStatusChangedElements[i].style.display = "";
                            }
                        }
                    },
                    urlconverter_callback: function(url, node, on_save){
                        url = url.replace("app/webroot/", "");
                        return url;
                    }
                });
                
                stopTimer();
            }
        }else{
            alert("Editor element not found");
            stopTimer();
        }
        
    }
    
    function stopTimer(){
        if(editorTimer != null){
            window.clearInterval(editorTimer);
        }
    }
    
    editorTimer = window.setInterval(function(){
    	if(selector != undefined && selector != null && selector != "" && $("textarea#" + selector).length){
    		initEditor("textarea#" + selector);	
    	}
    }, 100);
};

//Load payment box outside Payment Plugin in an iframe with ajax form submit
window.showPaymentBoxIniFrame = function(event, link, tokenName){

	// Make sure that we can get link HTML element from (click) event
	event = event || window.event;
	
	if(link == undefined || link == null || link.nodeName != 'A'){
		link = event.target || event.srcElement;
		if(link.nodeType == 3){
			link = link.parentNode; // defeat Safari bug
		}
	}
	
	if(link.nodeName != 'A'){
		link = event.currentTarget || event.delegateTarget;
	}
	
	// Start load linked content in iFrame
	if(event && link){
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		link = (link.tagName == 'A' || $(link).hasClass('clickable')) ? $(link) : $(link).closest("a");
		var iframeUrl = link.attr("href") ? link.attr("href") : link.data("link-url");

		$.ajax({
			
	        async: false,
	        cache: false,
	        headers: {"X-CSRF-Token" : window.getCookie(tokenName)},
	        url: iframeUrl,
	        type: "POST",
	        	
	    }).done(function( data ) {
	    	
	    	if(data){
	    		data = $.parseJSON(data);
	    		if(data.tempInvoiceId){
	    			bootbox.dialog({
	    		        message: '<iframe src="'+iframeUrl+'/'+data.tempInvoiceId+'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
	    		        title: link.attr("data-title"),
	    		        onEscape: false,
	    		        buttons: {
	    		        	"Close" : {
	    		        		"label" : link.attr("data-closest-btn"),
	    		        		"className" : "btn-sm btn-sm",
	    		        		"callback" : function(event){
	    		        			if(link.attr("data-reload-params")){
	    		        				var param 	= link.attr("data-reload-params");
	    		        				var url 	= window.location.href;
	    		        				if(url.indexOf("&" + param) < 0){
	    		        					if(url.indexOf("?" + param) < 0){
	    		        						window.location.href = url + ((url.indexOf("?") > 0) ? "&" : "?") + param; // only add param when it has not been added
	    		        					}else{
	    		        						window.location.reload(); 
	    		        					}
	    		        				}else{
	    		        					window.location.reload(); 
	    		        				}
	    		        			}else{
	    		        				window.location.reload(); /* Reload window to show flash message */
	    		        			}
	    		        		}
	    		        	}
	    		        }
	    		    });
	    		}
	    		if(data.result === true){
	    			window.location.reload(); /* Reload window to show flash message */
	    		}
	    		if(data.result === false){
	    			window.location.reload(); /* Reload window to show flash message */
	    		}
	    	}
	    }).fail(function( jqxhr, settings, exception ) {
	    	ajaxErrorHandler( jqxhr, settings, exception );
	    });
	}
	
	return false; // Stop the JS process, because we want to load the linked content in a popup iFrame instead of displaying it in top browser window
};

// Convert relative URI to full URL
window.getURLObject = function(uri){
	var link = document.createElement("a");
	link.href = uri;
	var url = new URL(link.href);
	return url;
};

window.setCookie = function (cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
};

window.getCookie = function (cname) {
    var name = cname + "=";
    var ca = inIframe(window) ? findTopWindow().document.cookie.split(';') : document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
    	var c = ca[i];
    	while (c.charAt(0) == ' ') {
    		c = c.substring(1);
    	}
    	if (c.indexOf(name) == 0) {
    		return c.substring(name.length, c.length);
    	}
    }
    return "";
};

window.wait = function(ms){
	var start = new Date().getTime();
	while(new Date().getTime() < start + ms);
};

window.objectGetKeyByValue = function(object, value){
	return Object.keys(object).filter(key => object[key] === value);
};

window.getRandomColorHex = function(){
	return "#" + ((1 << 24) * Math.random() | 0).toString(16);
};