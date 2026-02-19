<?php echo $this->Form->create('EmailMarketing.EmailMarketingCampaign'); ?>
    <div class="row">
        <div class="col-xs-12">
            <h5><?php echo __('Campaign Email Sending Progress'); ?></h5>
        </div>
        <div class="col-xs-12">
            <div id="progressbar"></div>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Send Start'); ?>
            </div>
            <div class="col-xs-6" id="send-start"></div>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Send End'); ?>
            </div>
            <div class="col-xs-6" id="send-end"></div>
        </div>
    </div>
    <div class="row hr hr-16 hr-dotted"></div>
    <div class="row">
        <div class="col-xs-12">
            <h5><?php echo __('Campaign Details'); ?></h5>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <?php echo __('Campaign Name'); ?>
                </div>
                <div class="col-xs-6">
                    <?php echo ($this->EmailMarketingPermissions->isAdmin() ? '(#' .$campaign["EmailMarketingCampaign"]["id"] .') ' : '') .$campaign["EmailMarketingCampaign"]["name"]; ?>
                    <?php echo '<input type="hidden" name="data[EmailMarketingCampaign][id]" value="' .$campaign["EmailMarketingCampaign"]["id"] .'">'; ?>
                </div>
            </div>
            <div class="col-xs-12 total-subscriber-amount">
                <div class="col-xs-6">
                    <?php echo __('Total subscribers'); ?>
                </div>
                <div class="col-xs-6">
                    <?php echo count($subscribers); ?>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <?php echo __('Email Marketing plan'); ?>
                </div>
                <div class="col-xs-6">
                    <?php echo $campaign["EmailMarketingUser"]["EmailMarketingPlan"]["name"]; ?>
                </div>
            </div>
            <div class="col-xs-12 email-left-amount">
                <div class="col-xs-6">
                    <?php echo __('Email Left'); ?>
                </div>
                <div class="col-xs-6">
                    <?php 
                        echo ($campaign["EmailMarketingUser"]["EmailMarketingPlan"]["email_limit"] == 0 ? __("Unlimited") : intval($campaign["EmailMarketingUser"]["EmailMarketingPlan"]["email_limit"]) + intval($campaign["EmailMarketingUser"]["free_emails"]) - intval($campaign["EmailMarketingUser"]["used_email_count"])); 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row hr hr-16 hr-dotted"></div>
    <div class="row">
        <div class="col-xs-12">
            <h5><?php echo __('Email Sending Details'); ?></h5>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Blacklisted Subscribers'); ?>
            </div>
            <div class="col-xs-6">
                <?php echo count($blacklisted); ?>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Duplicated Subscribers'); ?>
            </div>
            <div class="col-xs-6">
                <?php echo $duplicated; ?>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Invalid Subscribers'); ?>
            </div>
            <div class="col-xs-6" id="invalid-subscribers-count">
                <?php 
                    echo count($invalid); 
                    foreach($invalid as $i){
                        echo '<input type="hidden" name="data[EmailMarketingCampaign][invalid_subscriber][]" value="' .$i .'">';
                    }
                ?>
            </div>
        </div>
        <div class="col-xs-12 processed-subscribers">
            <div class="col-xs-6">
                <?php echo __('Processed Subscribers'); ?>
            </div>
            <div class="col-xs-6">
                0
            </div>
        </div>
    </div>
    <div class="row hr hr-16 hr-dotted"></div>
    <div class="row">
        <div class="col-xs-12 handle-invalid-subscribers">
            <button data-bb-handler="Reset" type="button" class="btn btn-sm btn-danger remove-invalid-subscribers" <?php echo (count($invalid) > 0) ? "" : 'disabled="disabled"'; ?>><?php echo __('Remove invalid subscribers from list'); ?></button>
        </div>
        <div class="space-10 new-line">&nbsp</div>
        <p><strong><?php echo __("Becasue some Mail Server cannot send the bounced email back in time, the bounced email statistics may need more time to collect. To view and process bounced email data about this campaign, please click <a target='_blank' href='/admin/email_marketing/email_marketing_statistics/viewCampaignHistory/" .$campaign["EmailMarketingCampaign"]["id"] ."'>here</a>, or click the statistic icon (<i class='ace-icon fa fa-pie-chart bigger-130 orange'></i>) in <a target='_blank' href='/admin/dashboard#/admin/email_marketing/email_marketing_campaigns'>campaign list page</a>."); ?></strong></p>
    </div>
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php
    $subscribersAmount          = count($subscribers);
    $handleInvalidsubscriberMsg = __('The invalid subscribers have been removed successfully');
    $warningMsg                 = __("Campaign emails sending process failed! This failure is recorded and it will be fixed A.S.A.P.<br />&nbsp;&nbsp;We will inform you over email about bug fixing progress. So sorry about the inconvenience.");
    $errMsg                     = __("Communication error, please wait for a while and try again.");
    $successMsg                 = __("Campaign emails have been sent successfully.");
    $sendingMsg                 = __("Sending ...");
    $removeinvalidSubscriber    = __("Are you sure to permanently delete invalid subscribers?");
    $confirmMsg = __("Do you want to send the emails now?<br /><br />If so, press Yes button.<br /><br />Alert! This action cannot be undone. Refresh web page won't stop email sending process.");
    $confirmYes = __('Yes');
    $confirmNo  = __('No');
    $debug      = Configure::read('debug');
    $inlineJS = <<<EOF
    
        var bootboxSubmitBtn = $(parent.document.body).children('.bootbox').children('.modal-dialog').children('.modal-content').children('.modal-footer').children('button[data-bb-handler="Send"]');
    
        $(function () {
            if({$subscribersAmount} <= 0){
                bootboxSubmitBtn.attr('disabled', true);
            }
        });
    
        $('.remove-invalid-subscribers').click(function(){
        
            if(confirm("{$removeinvalidSubscriber}")){
            
                $.ajax({
                    url: "/admin/email_marketing/email_marketing_subscribers/removeInvalidSubscriber",
                    type: "POST",
                    data: $("#EmailMarketingCampaignAdminSendEmailForm").serialize(),
                    beforeSend: function ( xhr ) {
                        /* TODO show spinner next to the button */
                    }
                }).done(function ( msg ) {
                    if(msg != undefined && msg != null && msg != "" && msg.indexOf("{$handleInvalidsubscriberMsg}") >= 0){
                        messageBox({"status": SUCCESS, "message": msg});
                        $('#invalid-subscribers-count').html("0");
                        $('div.handle-invalid-subscribers').children('button').attr({'disabled':true});
                    }else{
                        messageBox({"status": ERROR, "message": msg});
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    ajaxErrorHandler(jqXHR, textStatus, errorThrown);
                }).always(function() { 
                    /* TODO remove spinner */
                });
            }
        });
       
        /* Send compaign email action */
        bootboxSubmitBtn.bindFirst("click", function(e){
            
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            var sendBtn = $(this);
            
            bootbox.confirm({
                message: "{$confirmMsg}",
                buttons: {
                    confirm: {
                        label: '<i class="fa fa-check"></i>&nbsp;&nbsp;{$confirmYes}',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: '<i class="fa fa-times"></i>&nbsp;&nbsp;{$confirmNo}',
                        className: 'btn-danger'
                    },
                },
                callback: function(result){
                    if(result){
                        
                        window.scrollTo(0,0);
            
                        var frame          = $(parent.document.body).find("div.bootbox.modal").filter(function(){ return $(this).css("display") == "block"; }).find('div.bootbox-body:first').children('iframe');
                        var frameUrl       = frame.attr('src');
                        var totalCount     = parseInt($("form#EmailMarketingCampaignAdminSendEmailForm:first").find("div.total-subscriber-amount:first").children('div.col-xs-6:last').html().trim());
                        var emailLeft      = $("form#EmailMarketingCampaignAdminSendEmailForm").find("div.email-left-amount:first").children('div.col-xs-6:last');
                        var emailLeftCount = parseInt(emailLeft.html().trim());
        
                        /* Disable submit action to avoid multiple click */
                        sendBtn.attr({"disabled":"disabled"}).html("{$sendingMsg}"); 
                        
                        /* Clear dirty data when re-send the campaign emails */
                        var processCountEle = $("form#EmailMarketingCampaignAdminSendEmailForm:first").find("div.processed-subscribers:first").children("div.col-xs-6:last");
                        if(parseInt(processCountEle.html().trim()) > 0){
                            processCountEle.html(0);
                            $("div#send-start").html("");
                            $("div#send-end").html("");
                        }
            
                        /* Update web page content and progress bar based on processed email amount */
                        var updateWebPageAndProgressBar = function(data){
                            var bouncedCount = 0; /* Not get bounced count in run time. It will be collected via cron job */
                            var processedCount = parseInt(data["EmailMarketingStatistic"]["processed"]);
                            emailLeft.html(emailLeftCount - bouncedCount - processedCount);
                            $("form#EmailMarketingCampaignAdminSendEmailForm:first").find("div.processed-subscribers:first").children("div.col-xs-6:last").html((processedCount > totalCount) ? totalCount : processedCount);
        
                            var mailProgress = (bouncedCount + processedCount) > 0 ? (bouncedCount + processedCount) : false;
                            $( "#progressbar" ).progressbar("option", {value: mailProgress});
        
                            return (bouncedCount + processedCount) == totalCount;
                        };
            
                        /* Check sending email progress */
                        var checkingProgress = function(){
                            var beforeSend = function() {
                                $.gritter.removeAll();
                                
                                /* Initial progressbar */
                                if(!$( "#progressbar" ).hasClass("ui-progressbar")){
                                    $( "#progressbar" ).progressbar({
                                        max: totalCount,
                                        value: false
                                    });
                                    var progressbarValue = $( "#progressbar" ).find( ".ui-progressbar-value" );
                                    progressbarValue.css({"background": "#87b87f"});
                                }
                            };
                            var handleResponseFunc = function(data) {
        
                                /* Keep some debug code for now */
                                if({$debug}){
                                    console.log(data["EmailMarketingStatistic"]["status"]);
                                    console.log(data["EmailMarketingStatistic"]["processed"]);
                                    console.log(data["EmailMarketingStatistic"]["bounced"]);
                                    console.log(data["EmailMarketingStatistic"]["total_time_used"]);
                                    console.log(data["EmailMarketingStatistic"]["send_start"]);
                                    console.log(data["EmailMarketingStatistic"]["send_end"]);
                                    console.log('============================');
                                }
        
                                var terminateChecking = true;
                                if(data["EmailMarketingStatistic"]){
                                    
                                    if((data["EmailMarketingStatistic"] == "PENDING" || data["EmailMarketingStatistic"]["status"] == "PENDING") && data["EmailMarketingStatistic"]["processed"] == 0){
                                    
                                        if(!$("div#send-start").html()){
                                            $("div#send-start").html(data["EmailMarketingStatistic"]["send_start"]);
                                            return updateWebPageAndProgressBar(data);
                                        }
                                    
                                        /* If statistic status is PENDING, this means the job is not started and we wait until the job gets started. */
                                        terminateChecking = false;
                                        return terminateChecking;
                                    }else if(data["EmailMarketingStatistic"]["status"] == "FAILED"){
                                        /* Display end timestamp */
                                        if(!$("div#send-end").html()){ $("div#send-end").html(data["EmailMarketingStatistic"]["send_end"]/*getCurrentTimestamp()*/); }
                
                                        /* Display warning message */
                                        messageBox({"status": ERROR, "message": "{$warningMsg}"});
                
                                        terminateChecking = true;
                                        return terminateChecking;
                                    }else if(data["EmailMarketingStatistic"]["status"] == "SENT"){
                                        /* Display end timestamp */
                                        if(!$("div#send-end").html()){ $("div#send-end").html(data["EmailMarketingStatistic"]["send_end"]/*getCurrentTimestamp()*/); }
                
                                        /* Display success message */
                                        messageBox({"status": SUCCESS, "message": "{$successMsg}"});
                
                                        /* Update web page and progress bar for the last time */
                                        updateWebPageAndProgressBar(data);
                                        
                                        /* Remove send button and if client wants to re-send emails, close popup and re-click send icon in data table. 
                                           This will reload CSRF token in popup form, because the CSRF token can only be used once. */
                                        sendBtn.remove();
                
                                        terminateChecking = true;
                                        return terminateChecking;
                                    }else{
                                        /* Display start timestamp */
                                        if(!$("div#send-start").html()){ $("div#send-start").html(data["EmailMarketingStatistic"]["send_start"]); }
        
                                        /* Update web page and progress bar for the last time */
                                        return updateWebPageAndProgressBar(data);
                                    }
                                    
                                }
                                return terminateChecking;
                            };
                            var disconnectFunc = function() {};
                            var compaignId = frameUrl.split("?").shift().split("/").pop();
                            var totalSubscribersAmount = $("form#EmailMarketingCampaignAdminSendEmailForm").find('div.total-subscriber-amount:first').children('div.col-xs-6:last').text().trim();
                            var comet = new Comet('/admin/email_marketing/email_marketing_campaigns/checkSendingProcess/' + compaignId + '/' + totalSubscribersAmount, {}, beforeSend, handleResponseFunc, disconnectFunc);
                            comet.connect();
                        };
            
                        /* Trigger email sending */
                        var sendEmailHandler = function(){
                            $.ajax({
                                url: frameUrl,
                                type: "POST",
                                headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                                beforeSend: function ( xhr ) {
                                    /* Do nothing for now */
                                }
                            }).done(function ( data ) {
                                if(data != undefined && data != null && data != ""){
                                    if(validateJSONString(data)){                           
                                        data = $.parseJSON(data);
                                        if("TRIGGER_CHECKING" == data.message){
                                            checkingProgress();
                                        }else{
                                            messageBox({"status": data.status, "message": data.message});
                                        }
                                    }
                                }else{
                                    messageBox({"status": ERROR, "message":"{$errMsg}"});
                                }
                                
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                ajaxErrorHandler(jqXHR, textStatus, errorThrown);
                                sendBtn.remove();
                            }).always(function() { 
                                /* Do nothing for now */
                            });
                        };
            
                        /* Start sending emails */
                        sendEmailHandler();
                        
                    }
                }
            });

        });
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'loadedScripts' => array('comet.js'),
        'inlineJS' => $inlineJS
    )); 
?>