/**
 * Extend JS
 */

String.prototype.capitalizeFirstLetter = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

String.prototype.isNumeric = function () {
    return !isNaN(parseFloat(this)) && isFinite(this);
};

String.prototype.toCamelCase = function () {
    return this.replace(/^([A-Z])|[\s-_](\w)/g, function(match, p1, p2, offset) {
        if (p2) {
        	return p2.toUpperCase();
        }else{
        	return p1.toLowerCase();
        }        
    });
};

String.prototype.camelCaseToWords = function () {
	return this.replace(/([A-Z])/g, ' $1').capitalizeFirstLetter();
};

String.prototype.hashCode = function (){
	var hash = 0;
    if (this.length == 0) {
        return hash;
    }
    for (var i = 0; i < this.length; i++) {
        var char = this.charCodeAt(i);
        hash = ((hash<<5)-hash)+char;
        hash = hash & hash; // Convert to 32bit integer
    }
    return hash;
};

String.prototype.deserialize = function (){
	var str = this;
	var formData = false;
	try{
		formData = JSON.parse(str);
	}catch(e){
		str = str.replace(/\+/g, '%20');
		var formFieldArr = str.split("&");
		var formData = new Array();
		
		for(var i = 0; i < formFieldArr.length; i++){
			var val = formFieldArr[i].split("=");
			var name = decodeURIComponent(val[0]);
			var value = decodeURIComponent(val[1]);
			var jsonStr = '{"'+name+'": "'+value+'"}';
			formData.push(JSON.parse(jsonStr));
		}
	}

	return formData;
};

if (!String.prototype.endsWith) {
	String.prototype.endsWith = function(search, len) {
		if(len === undefined || len > this.length){
			len = this.length;
		}
		return this.substring(len - search.length, len) === search;
	};
}

if(!Array.prototype.last){
	Array.prototype.last = function(){
		return this[this.length - 1];
	};
}

Array.prototype.toObject = function() {
	var arr = this;
	var obj = {};
	for (var k in arr)
		obj[k] = arr[k];
	return obj;
};


/**
 * Extend jQuery
 */

// Cache external JS
$.cachedScript = function( url, options ) {
	 
	// Allow user to set any option except for dataType, cache, and url
	options = $.extend( options || {}, {
		dataType: "script",
	    cache: true,
	    url: url
	});
	 
	// Use $.ajax() since it is more flexible than $.getScript
	// Return the jqXHR object so we can chain callbacks
	return $.ajax( options );
};

// Check whether jQuery object has scrollbar
$.fn.hasScrollbar = function() {
    var divnode = this.get(0);
    if(divnode.scrollHeight > divnode.clientHeight){
    	return true;
    }else{
    	return false;
    }
};

// Refresh a jQuery object/selector
$.fn.refresh = function() {
	if(!this.selector){
		// The following code may generated a selector which can find multiple elements
		// Please use this with caution!! And make sure to test everything.
		if(this.attr("id")){
			this.selector = "#"+this.attr("id");
		}else if(this.attr("class")){
			this.selector = "."+this.attr("class").split(" ")[0];
		}
	}
	var elems = $(this.selector);
    this.splice(0, this.length);
    this.push.apply( this, elems );
    return this;
};

// Check whether an element has an ID attribute
$.fn.hasId = function( id, partialMatch ) {
	if(!partialMatch){
		partialMatch = false;
	}
	var currentId = this.attr("id");
	if(currentId){
		if(partialMatch){
			return currentId.indexOf(id) >= 0;
		}else{
			return id === currentId;
		}
	}else{
		return false;
	}
};

// Check whether an event has already bound to an element
$.fn.isBound = function(type, fn){
	try{
		var data = this.data('events')[type];
		if(data == undefined || data.length === 0){
			return false;
		}
		return (-1 !== $.inArray(fn, data));
	}catch(err){
		return false;
	}
	
};

// Bind event handler to first place (run this handler first)
$.fn.bindFirst = function(name, fn){
	this.on(name, fn); // Bind the handler first as it normally would
	
	// Then change order
	this.each(function(){
		var handlers = $._data(this, 'events')[name.split('.')[0]];
		var handler  = handlers.pop(); // Get just bind handler from the end
		handlers.splice(0, 0, handler); // Move it to the first place
	});
};