jQuery(function($) {
	
	/**
	 * Global settings
	 */
	
	/* Format drop down list */
	setTimeout(function(){
		$('select').each(function(){
			if($(this).attr('name') && $(this).attr('name').indexOf('data-table_length') < 0 && !$(this).hasClass('no-selectmenu')){
				$(this).selectmenu();
				if(!$(this).hasClass('oneline')){
					$(this).prev('label').not('.oneline').css({'float': 'left'});
					$(this).next('span.ui-selectmenu-button.ui-widget').css({'clear':'both', 'float': 'left', 'width': '100%'});
				}
			}
		});
	}, 800);
	
	
	/**
	 *  Register JS custom event
	 */
	
	/* 
	 * Check admin page base URL. If not right, correct it with redirection
	 */
	$("a").on('click', function(event){
		if(
			window.location.pathname.startsWith("/admin/") && !window.location.pathname.startsWith("/admin/dashboard") && $(this).attr("href").startsWith('#') && $(this).attr("href").length > 1 &&
			(!$(this).attr("data-toggle") || ($(this).attr("data-toggle") != 'tab' && $(this).attr("data-toggle") != 'collapse'))
		){
			event.preventDefault();
			event.stopPropagation();	
			window.location.href = '/admin/dashboard' + $(this).attr("href");
			return;
		}
		
		if($(this).hasClass('show-comments')){
			$(this).closest('.row.comments').children('.comment').css('display', 'block');
		}
		
	});
	
	$('.click-reload-top-window').click(function(){
		window.top.location.reload();
		return false;
	});
	
	/* Register "submitIframeFormFromParentWindowEvent" */
	$( "body" ).delegate( ".submit-iframe-form-btn", "submitIframeFormFromParentWindowEvent", function ( event, iframeId, formId, isAjax ) {

		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();

		var iframe = getFormHolderIframe(iframeId);
		
		// Some iframe is embeded within other iframes. We need to recursively find it.
		if(!iframe.length){
			iframe = findIframe(window, "#"+iframeId);
		}
		
		// We need to use jQuery instance which binds with iframe to get form element, because jQuery instances in iframe and top window are different.
		// We already use iframe jQuery to set up the validation rules, then we have to use the same jQuery instance to validate the form inside iframe.
		var form = document.getElementById(iframeId).contentWindow.$("#"+formId);
		
		if(!form.length){
			form = iframe.find("#"+formId).first() || iframe.contents().find("#"+formId).first();
		}

		if(typeof form.valid == 'function'){
			
			if(form.valid()){
				
				// Load editors content to form before submit
				if(form.find('.Editor-container').length){
	        		form.find('.Editor-container').each(function(){
	        			var that = this;
	        			var textarea = $(that).prev('textarea');
	        			textarea.val($(that).children(".Editor-editor").html());
	        		});
		    	}
				
				var ajaxReturnData = false; // Prepare a return data var

				if(isAjax){

					var serializeArray = form.serializeArray();
					var formJsonData = {};
					for(var i = 0; i < serializeArray.length; i++){
						formJsonData[serializeArray[i]['name']] = serializeArray[i]['value'];
					}

					var actionUrl = form.attr("action");
					if(!actionUrl.endsWith(".json")){
						if(actionUrl.endsWith("/")){
							actionUrl = actionUrl.substring(0, (actionUrl.length -1)) + ".json";
						}else{
							actionUrl += ".json";
						}
					}
					
					$.ajax({
				        async: false,
				        url: actionUrl,
				        type: form.attr("method"),
				        data: formJsonData,
				        beforeSend: function ( xhr ) { 
				        	/* Do nothing for now */
				        },
				    }).done(function ( data ) {
				    	hideLoaddingSpinner(event.target);
	    	
				    	if('false' === event.target.dataset.reload){
				    		
				    		/* Show flash message manually */
				    		if((typeof data) == 'string'){
				    			try{
				    				data = $.parseJSON(data);
					    			messageBox(data);
				    			}catch(err){
				    				// If data is not JSON, it might be a callback function. We will try to run it.
				    				try{
				    					eval(data);
				    				}catch(err){
				    					ajaxErrorHandler(null, null, 'Cannot parse return data: ' + data);
				    					return data;
				    				}
				    			}
				    			ajaxReturnData = data;
				    		}
				    		
				    	}else{
				    		window.location.reload(); /* Reload window to show flash message */
				    	}
				    }).fail(function(jqXHR, textStatus, errorThrown) {
				    	hideLoaddingSpinner(event.target);
				        ajaxErrorHandler(jqXHR, textStatus, errorThrown);
				    }).always(function() { 
				    	/* Do nothing for now */
				    });
					
				}else{
					form.submit();
				}
				
				if(ajaxReturnData && !$(event.target).hasClass("close-popup-after-submit")){
					// If no window reload and there is a return data, then return the data by putting it in HTML
					$(event.target).data("ajax-return-data", ajaxReturnData);
				}
				
			}else{
				hideLoaddingSpinner(event.target);
				return false; // Prevent the popup form being closed by reload the page
			}
		}else{
			form.submit(); // jQuery validation is not enabled, directly submit the form
		}
		
		/* 
		 * Handle special requirement after normal submit:
		 * 	reload the page to update the data table content and show message box 
		 */ 
		if($(event.target).hasClass("close-popup-after-submit")){

			var startTime = (new Date()).getTime();
			var timer;
			timer = setInterval(function(){ 
				iframe = findIframe(window, "#"+iframeId);
				var gritterMsgBox = iframe.contents().find('div[id^="gritter-item-"]').filter(function(){ return $(this).css("display") == "block";});
				if(gritterMsgBox.length){
					clearInterval(timer);
					gritterMsgBox.first().bind("DOMNodeRemoved", function(e){
						window.location.reload();
					});
				}else{
					// Some page may not have gritter message box. Then we check for 5 sec and then reload the window
					var timeNow = (new Date()).getTime();
					if((timeNow - startTime) >= 5000){
						clearInterval(timer);
						window.location.reload();
					}
				}
			}, 200);
			
		}
	});
	
	/* Register "resetIframeFormFromParentWindowEvent" */
	$( "body" ).delegate( ".reset-iframe-form-btn", "resetIframeFormFromParentWindowEvent", function ( event, iframeId, formId ) {
	    getFormHolderIframe(iframeId).find("#"+formId).get(0).reset();
	});
	
	/* Bootbox global opened callback */
    $(document).on("shown.bs.modal", function (event) {

    	/* Use iframeResizer while resizing the iframe (responsive design) */
    	var isOldIE = (navigator.userAgent.indexOf("MSIE") !== -1); // Detect IE10 and below
    	var windowHeight = $(document).height();
    	
    	$(event.target).find('iframe:first').iFrameResize({
            log                     : false, // Enable console logging
            enablePublicMethods     : true,
            heightCalculationMethod : isOldIE ? 'max' : 'lowestElement',
            checkOrigin				: true,
            scrolling				: true,
//            maxHeight				: windowHeight - 30, //Math.round(windowHeight * 0.95),
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
    	
    	/* When modal window appears, clear all the message box */
    	$.gritter.removeAll();
    	
    });
    
    /* Bootbox global close callback */
    $(document).on("hidden.bs.modal", function (event) {
    	
    });
    
    /* Resize iframe in plain page tabs (not in Bootbox) */
//    initIframeResizeInPlainPage();
	
    /* Tab shown event (in main web page content area) */
    $('#main-container a[data-toggle="tab"]').on("shown.bs.tab", function (event) {

    	/* Resize iframe in plain page tabs (not in Bootbox) */
    	initIframeResizeInPlainPage();
    	
    	// If there is an iframe inside the tab, the data tables inside that iframe will be generated before the iframe resizer initialise.
    	// Due to this problem, we need to manually adjust the data table's columns width and make sure the data can be displayed correctly.
    	var tabContentId = $(event.target).attr("href");
    	if($(tabContentId).length && $(tabContentId).find("iframe").length){
    		$(tabContentId).find("iframe").each(function(){
    			if(this.contentWindow.manuallyAdjustDataTable){
    				this.contentWindow.manuallyAdjustDataTable();
    			}
    		});
    	}
    	
    });
    
    /**
     * Third party library
     */
    
    /* Configure tooltip */
    $('[data-rel="tooltip"]').tooltip({placement: tooltipPlacement});
    function tooltipPlacement(context, source) {
    	var $source = $(source);
    	
    	if($source.attr("data-placement")){
    		return $source.attr("data-placement");
    	}
    	
    	var $parent = $source.parent();
    	var off1 = $parent.offset();
    	var w1 = $parent.width();

    	var off2 = $source.offset();
//    	var w2 = $source.width();

    	if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ){ return 'right'; }
    	return 'left';
    }

    /* 
     * Set jQuery.validate settings for bootstrap integration. This function need to be called before validate(). 
     * 
     * Notice: When field ID is different from NAME, use NAME in jQuery.validate function.
     */
    $.validator.setDefaults({
    	debug: false,
    	validClass: "valid",
        errorClass: "invalid",
        errorPlacement: function(error, element) {
        	var eleType = element.attr('type');
        	if(eleType && eleType != "hidden"){
        		var fieldLabel = element.closest("body").find('label[for="'+element.attr("id")+'"]').first();
            	if(fieldLabel != undefined && fieldLabel != null && fieldLabel != "" && fieldLabel.length && !fieldLabel.hasClass('no-validation-symbol')){
            		error.css({"float": "right"}).insertAfter(fieldLabel);
            	}else{
            		element.parent().css({"position": "relative"});
            		error.css({"position": "absolute", "right": 0, "margin": "auto", "z-index": 9999});
            		if(element.next('.spinbox-buttons').length){
            			element = element.next('.spinbox-buttons');
            			error.css({"right": "-24px"});
            		}
            		if(!element.hasClass('no-validation-symbol')){
                		error.insertAfter(element);
            		}
            	}
        	}
        },
        ignore: ".required-when-visible:hidden, *:not([name])",
        invalidHandler: function(event, validator) {
            var errors = {};
            var currentDocument = $(document);
            var parentWin = null;
            if(currentDocument.find("#"+event.target.id).length < 1){
            	// If cannot find form field in the current window, it means the form is in an iFrame which is embeded in the current window.
            	/* At a time, only one popup box will appear and iframe will only live within the iframe. */
            	parentWin = currentDocument;
            	currentDocument = getFormHolderIframe(currentDocument.find('.bootbox iframe').first().attr("id")).find("#"+event.target.id).first();
            }
            for(var key in validator.errorMap){
                var fieldName = currentDocument.find('[name="'+key+'"]:first').prev('label').text().replace(/\*/g,"").trim();
                errors[fieldName] = validator.errorMap[key];
            }
            if(!$.isEmptyObject(errors)){
                generateErrorMessage(JSON.stringify(errors));
            }
        },
        success: function(element) {
            element.text('OK!').addClass('valid'); 
        }
    });
	
});