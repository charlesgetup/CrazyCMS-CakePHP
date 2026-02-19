<?php echo $this->Form->create('EmailMarketing.EmailMarketingCampaign'); ?>
    <div class="row">
        <div class="col-xs-12">
            <h5><?php echo __('Campaign Details'); ?></h5>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Campaign Name'); ?>
            </div>
            <div class="col-xs-6">
                <?php echo ($this->EmailMarketingPermissions->isAdmin() ? '(#' .$campaign["EmailMarketingCampaign"]["id"] .') ' : '') .$campaign["EmailMarketingCampaign"]["name"]; ?>
                <?php
                    echo $this->Form->input('id', array(
                        'type'          => 'hidden',
                        'label'         => false,
                        'value'         => $campaign["EmailMarketingCampaign"]["id"],
                        'div'           => false
                    ));
                ?>
            </div>
        </div>
    </div>
    <div id="sending-procedure">
        <div class="row hr hr-16 hr-dotted"></div>
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
                <h5><?php echo __('Email Sending Details'); ?></h5>
            </div>
            <div class="col-xs-12 blacklisted-subscribers">
                <div class="col-xs-6">
                    <?php echo __('Blacklisted Subscribers'); ?>
                </div>
                <div class="col-xs-6">
                    0
                </div>
            </div>
            <div class="col-xs-12 duplicated-subscribers">
                <div class="col-xs-6">
                    <?php echo __('Duplicated Subscribers'); ?>
                </div>
                <div class="col-xs-6">
                    0
                </div>
            </div>
            <div class="col-xs-12 invalid-subscribers">
                <div class="col-xs-6">
                    <?php echo __('Invalid Subscribers'); ?>
                </div>
                <div class="col-xs-6" id="invalid-subscribers-count">
                    0
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
    </div>
    <div id="data-collection">
        <div class="row hr hr-16 hr-dotted"></div>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('test_email_1', array(
                        'type'          => 'email',
                        'label'         => array('text' => __('Test Email 1')),
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 1
                    ));
                ?>
            </div>
            <div class="space-14 new-line">&nbsp;</div>
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('test_email_2', array(
                        'type'          => 'email',
                        'label'         => array('text' => __('Test Email 2')),
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 2
                    ));
                ?>
            </div>
            <div class="space-14 new-line">&nbsp;</div>
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('test_email_3', array(
                        'type'          => 'email',
                        'label'         => array('text' => __('Test Email 3')),
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 3
                    ));
                ?>
            </div>
            <div class="space-14 new-line">&nbsp;</div>
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('test_email_4', array(
                        'type'          => 'email',
                        'label'         => array('text' => __('Test Email 4')),
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 4
                    ));
                ?>
            </div>
            <div class="space-14 new-line">&nbsp;</div>
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('test_email_5', array(
                        'type'          => 'email',
                        'label'         => array('text' => __('Test Email 5')),
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 5
                    ));
                ?>
            </div>
        </div>
    </div>
<?php echo $this->Form->end(); ?>

<div class="notice-area">
    <h3><?php echo __("Notice"); ?>:</h3>
    <ul>
        <li>
            <?php echo __('If there is some embeded attribute used in email template, like <i>[FIRST-NAME]</i>, randomly selected subscriber\'s data will be used.<br />If there is no mailing list attached to the campaign, the embeded attribute will be ignored.'); ?>
        </li>
    </ul>
</div>

<!-- page specific plugin scripts -->
<?php
    $handleInvalidsubscriberMsg = __('The invalid subscribers have been removed successfully');
    $warningMsg                 = __("Campaign emails sending process failed! This failure is recorded and it will be fixed A.S.A.P.<br />&nbsp;&nbsp;We will inform you over email about bug fixing progress. So sorry about the inconvenience.");
    $errMsg                     = __("Communication error, please wait for a while and try again.");
    $successMsg                 = __("Campaign emails have been sent successfully.");
    $sendingMsg                 = __("Sending ...");
    $confirmMsg = __("Do you want to send test emails now?<br /><br />If so, press Yes button.<br /><br />Alert! This action cannot be undone. Refresh web page won't stop email sending process.");
    $confirmYes = __('Yes');
    $confirmNo  = __('No');
    $debug      = Configure::read('debug');
    $inlineJS = <<<EOF
    
        var bootboxSubmitBtn = $(parent.document.body).children('.bootbox').children('.modal-dialog').children('.modal-content').children('.modal-footer').children('button[data-bb-handler="Send"]');
    
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
                        var totalCount     = 0;
                        
                        $('#EmailMarketingCampaignAdminSendTestEmailForm #data-collection').find('input[name^="data[EmailMarketingCampaign][test_email_"]').each(function(){
                            if(this.value){
                                totalCount++;
                            }
                        });
        
                        /* Disable submit action to avoid multiple click */
                        sendBtn.attr({"disabled":"disabled"}).html("{$sendingMsg}"); 
                        
                        /* Clear dirty data when re-send the campaign emails */
                        $("#sending-procedure").find("div.blacklisted-subscribers:first").children("div.col-xs-6:last").html('0');
                        $("#sending-procedure").find("div.duplicated-subscribers:first").children("div.col-xs-6:last").html('0');
                        $("#sending-procedure").find("div.invalid-subscribers:first").children("div.col-xs-6:last").html('0');
                        $("#sending-procedure").find("div.processed-subscribers:first").children("div.col-xs-6:last").html('0');
                        $("div#send-start").html("");
                        $("div#send-end").html("");
    
                        /* Update web page content and progress bar based on processed email amount */
                        var updateWebPageAndProgressBar = function(data){           
                            var processedCount = parseInt(data["EmailMarketingStatistic"]["processed"]);
                            $("form#EmailMarketingCampaignAdminSendTestEmailForm").find("div.processed-subscribers:first").children("div.col-xs-6:last").html((processedCount > totalCount) ? totalCount : processedCount);
        
                            var mailProgress = processedCount > 0 ? processedCount : false;
                            $( "#progressbar" ).progressbar("option", {value: mailProgress});
        
                            return processedCount == totalCount;
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
                            var totalSubscribersAmount = totalCount;
                            var comet = new Comet('/admin/email_marketing/email_marketing_campaigns/checkSendingProcess/' + compaignId + '/' + totalSubscribersAmount + '/1', {}, beforeSend, handleResponseFunc, disconnectFunc);
                            comet.connect();
                        };
            
                        /* Trigger email sending */
                        var sendEmailHandler = function(){
                            $.ajax({
                                url: frameUrl,
                                type: "POST",
                                data: $('form#EmailMarketingCampaignAdminSendTestEmailForm').serialize(),
                                beforeSend: function ( xhr ) {
                                    /* make sure the test email addresses fields are visible */
                                    $('#EmailMarketingCampaignAdminSendTestEmailForm #sending-procedure').css('display', 'none');
                                    $('#EmailMarketingCampaignAdminSendTestEmailForm #data-collection').css('display', 'block');
                                }
                            }).done(function ( data ) {
                                if(data != undefined && data != null && data != ""){
                                    if(validateJSONString(data)){                           
                                        data = $.parseJSON(data);
                                        if(data.status == WARNING || data.status == SUCCESS){
                                            $("form#EmailMarketingCampaignAdminSendTestEmailForm").find("div.blacklisted-subscribers:first").children("div.col-xs-6:last").html(data.blacklisted);
                                            $("form#EmailMarketingCampaignAdminSendTestEmailForm").find("div.duplicated-subscribers:first").children("div.col-xs-6:last").html(data.duplicated);
                                            $("form#EmailMarketingCampaignAdminSendTestEmailForm").find("div.invalid-subscribers:first").children("div.col-xs-6:last").html(data.invalid);
                                            totalCount = data.total;
                                        }
                                        if("TRIGGER_CHECKING" == data.message){
                                            $('#EmailMarketingCampaignAdminSendTestEmailForm #sending-procedure').css('display', 'block');
                                            $('#EmailMarketingCampaignAdminSendTestEmailForm #data-collection').css('display', 'none');
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