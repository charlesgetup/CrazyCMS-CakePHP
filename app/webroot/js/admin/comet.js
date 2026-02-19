// Comet implementation
// (Acturally using Polling technic)
var Comet = function (dataUrl, data, beforeSend, handleResponseFunc,disconnectFunc) {
	
	this.url 			= dataUrl;  
	this.noerror 		= true;
	this.data 			= typeof data == "string" ? $.parseJSON(data) : (data ? data : {});
	this.disconnect 	= disconnectFunc ? disconnectFunc : function() {}; // Didn't figure out how to use it for now
	this.handleResponse = handleResponseFunc ? handleResponseFunc : function(response) {this.terminate = true;};
	this.beforeSend		= beforeSend ? beforeSend : function ( xhr ) {};
	this.terminate		= false;

	this.connect = function() {
		var self = this;

	    $.ajax({
	    	type 		: 'post',
	    	url 		: self.url,
	    	dataType 	: 'json', 
	    	data 		: self.data,
	    	beforeSend: function ( xhr ) {
                self.beforeSend();
            },
	    	success : function(response) {
	    		self.terminate = self.handleResponse(response);
		        self.noerror   = true;          
	    	},
	    	complete : function(response) {
	    		if(self.terminate == false){
	    			// send a new ajax request when this request is finished
			        if (!self.noerror) {
			          // if a connection problem occurs, try to reconnect each 2 seconds
			            setTimeout(function(){ self.connect(); }, 5000);           
			        }else {
			          // persistent connection
			        	setTimeout(function(){ self.connect(); }, 2000);  
			        }
	    		}else{
	    			self.disconnect();
	    		}
		
		        self.noerror = false; 
	    	}
	    });
	};

};