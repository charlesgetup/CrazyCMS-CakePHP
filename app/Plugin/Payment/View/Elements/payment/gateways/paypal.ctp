<div id="paypal-button-container"></div>

<!-- page specific plugin scripts -->
<?php
    $isTempInvoiceTxt       = $isTempInvoice ? "/1" : "";
    $paypalAppMode          = Configure::read('Payment.paypal.rest.api.app.mode');
    $successMsg             = __('Payment complete!') .'<br />' .__('Thank you for your business!') .'<br /><br />' .__('Receipt will be emailed to you in the next couple of minutes. If you didn\'t receive the email, please manually trigger the email sending process in Payment section.');
    $alreadyActivatedMsg    = __('Service account has already been activated.');
    $returnDataErr          = __('Cannot check service account status. If not active, please manually activate the service account.');
    $errMsg                 = __('Payment complete!') .'<br />' .__('System is not updated correctly. Please check payment logs in Log section and report this to ' .$companyName .'.') .'<br />' .__('Sorry about the inconvenience.');
 
    $inlineJS = <<<EOF
    
        var CREATE_PAYMENT_URL  = '/admin/payment/payment_pay_pal_gateway/createPayment/{$pendingInvoiceId}{$isTempInvoiceTxt}.json';
        var EXECUTE_PAYMENT_URL;
    
        var renderPayPalButton = function(){
        
            paypal.Button.render({
    
                env:  '{$paypalAppMode}', // 'sandbox' Or 'production'
        
                commit: true, // Show a 'Pay Now' button
        
                style: {
                    label: 'buynow',
                    fundingicons: true, // optional
                    branding: true, // optional
                    size:  'small', // small | medium | large | responsive
                    shape: 'rect',   // pill | rect
                    color: 'gold'   // gold | blue | silve | black
                },
        
                payment: function() {
                    return paypal.request.post(CREATE_PAYMENT_URL).then(function(data) {
                        if(data.payment){
                            data = $.parseJSON(data.payment);
                            if(data.id && data.redirect_urls.return_url){
                                EXECUTE_PAYMENT_URL = data.redirect_urls.return_url;
                                return data.id;
                            }
                        }
                        return false;
                    });
                },
        
                onAuthorize: function(data) {
                    return paypal.request.post(EXECUTE_PAYMENT_URL, {
                        paymentID: data.paymentID,
                        payerID:   data.payerID
                    }).then(function(res) {
                    
                        $('.payment-options').html('');
                        if(inIframe(window) && $(".modal-body", window.parent.document).length){
                            initIframe($(".modal-body", window.parent.document).find('iframe').first().get(0));
                        }
                    
                        if(!res.systemUpdateError){
                            messageBox({"status": SUCCESS, "message": "{$successMsg}"});
                            
                            var paymentInfo = res.payment;
                            if("string" == typeof paymentInfo){
                                paymentInfo = $.parseJSON(paymentInfo);
                            }
                            
                            // Check whether service account needs to be activated
                            $.ajax({
                
                                async: false,
                                cache: false,
                                headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                                url:   "/users/activateAccountAfterPayment/" + paymentInfo.id + "/" + paymentInfo.transactions[0].description,
                                type:  "POST",
                                    
                            }).done(function( data ) {
    
                                try{
                                    var response = $.parseJSON(data);
                                    if(response.status && response.message){
                                        messageBox({"status": response.status, "message": response.message});
                                    }else{
                                        messageBox({"status": WARNING, "message": "{$alreadyActivatedMsg}"});
                                    }
                                    
                                    if(!$(".modal-footer", window.parent.document).find(".click-reload-top-window").length){
                                        $(".modal-footer", window.parent.document).html('<button data-bb-handler="Close" type="button" class="btn btn-sm btn-sm click-reload-top-window">Close</button>');
                                        $('.click-reload-top-window').bind('click', function(){
                                            window.top.location.reload();
                                            return false;
                                        });
                                    }
                                    
                                }catch(err){
                                    // Sometimes return data is not json format
                                    messageBox({"status": WARNING, "message": "{$returnDataErr}"});
                                }
                            
                            }).fail(function( jqxhr, settings, exception ) {
                                ajaxErrorHandler( jqxhr, settings, exception );
                            });
                            
                        }else{
                            messageBox({"status": WARNING, "message": "{$errMsg}"});
                        }
                    });
                }
        
            }, '#paypal-button-container');
            
        };
        
        if($.fn.ace_ajax && $('[data-ajax-content="true"]').length){
            $('[data-ajax-content="true"]').ace_ajax('loadScripts', [null, "https://www.paypalobjects.com/api/checkout.js", null], function() {
                renderPayPalButton();
            });
        }else{
            loadAndRunJSScript("https://www.paypalobjects.com/api/checkout.js", null, renderPayPalButton);
        }
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>