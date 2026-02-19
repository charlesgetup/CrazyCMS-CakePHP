<?php echo $this->Form->create('EmailMarketing.EmailMarketingCampaign'); ?>
    <div class="row">
        <div class="col-xs-12">
            <div>
                <div class="col-sm-offset-1 col-sm-10">
                    <div class="tabbable">
                        <ul class="nav nav-tabs padding-16">
                            <li class="active">
                                <a data-toggle="tab" href="#add-details">
                                    <i class="pink ace-icon fa fa-line-chart bigger-125"></i>
                                    <?php echo __('Gerenral Details'); ?>
                                </a>
                            </li>
                            <li class="html">
                                <a data-toggle="tab" href="#add-html-message">
                                    <i class="pink ace-icon fa fa-html5 bigger-125"></i>
                                    <?php echo __('HTML Email'); ?>
                                </a>
                            </li>
                            <li class="txt">
                                <a data-toggle="tab" href="#add-text-message">
                                    <i class="pink ace-icon fa fa-file-text-o bigger-125"></i>
                                    <?php echo __('Text Email'); ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane in active" id="add-details">
                                <div class="content-container">
                                    <?php  
                                        $isAdd = $this->request->params['action'] == 'add';
                                        echo $this->Form->hidden('id');
                                        if($isAdd){
                                            echo $this->Form->hidden('status', array('value' => 'PENDING'));
                                            echo $this->Form->input('created' , array('type' => 'hidden', 'value' => date('Y-m-d H:i:s', strtotime('now'))));
                                        }else{
                                            echo $this->Form->hidden('status');
                                            echo $this->Form->input('modified' , array('type' => 'hidden', 'value' => date('Y-m-d H:i:s', strtotime('now'))));
                                            echo $this->Form->hidden('scheduled_time');
                                        }
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php
                                                echo $this->Form->input('email_marketing_user_id', array(
                                                    'label'         => array('text' => __('Created By') .'&nbsp;<span class="mandatory">*</span>'),
                                                    'options'       => $userList,
                                                    'class'         => 'required col-xs-12 col-sm-12',
                                                    'div'           => false,
                                                    'tabindex'      => 1
                                                ));
                                            ?>
                                        </div>
                                        <div class="space-14 new-line">&nbsp;</div>
                                        <div class="col-xs-12">
                                            <?php
                                                echo $this->Form->input('name', array(
                                                    'label'         => array('text' => __('Name') .'&nbsp;<span class="mandatory">*</span>'),
                                                    'class'         => 'required col-xs-12 col-sm-12',
                                                    'div'           => false,
                                                    'tabindex'      => 2
                                                ));
                                            ?>
                                        </div>
                                        <div class="space-14 new-line">&nbsp;</div>
                                        <div class="col-xs-12">
                                            <?php
                                                echo $this->Form->input('send_format', array(
                                                    'label'         => array('text' => __('Email Format') .'&nbsp;<span class="mandatory">*</span>'),
                                                    'options'       => array(Configure::read('EmailMarketing.email.type.text') => 'Text', Configure::read('EmailMarketing.email.type.html') => 'HTML', Configure::read('EmailMarketing.email.type.both') => 'BOTH (Text & HTML)'),
                                                    'class'         => 'required col-xs-12 col-sm-12',
                                                    'div'           => false,
                                                    'default'       => Configure::read('EmailMarketing.email.type.both'),
                                                    'tabindex'      => 3
                                                ));
                                            ?>
                                        </div>
                                        <div class="space-14 new-line">&nbsp;</div>
                                        <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingSenders/admin_index')): ?>
                                            <div class="col-xs-12" style="position: relative;">
                                                <div class="col-xs-6" style="padding-left: 0;">
                                                    <?php
                                                        echo $this->Form->input('from_email_address_prefix', array(
                                                            'label'         => array('text' => __('Send From Email Address')),
                                                            'class'         => 'col-xs-12 col-sm-12',
                                                            'div'           => false,
                                                            'tabindex'      => 4
                                                        ));
                                                    ?>
                                                </div>
                                                <div class="col-xs-1" style="position: absolute; left: 51%; top: 44%;">@</div>
                                                <div class="col-xs-5" style="position: absolute; bottom: 0; right: 0;">
                                                    <?php
                                                        echo $this->Form->input('email_marketing_sender_id', array(
                                                            'label'         => false,
                                                            'options'       => ((isset($senderList) && is_array($senderList)) ? $senderList : array()),
                                                            'class'         => 'col-xs-12 col-sm-12',
                                                            'style'         => 'height: 34px;',
                                                            'div'           => false,
                                                            'tabindex'      => 5
                                                        ));
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="space-14 new-line">&nbsp;</div>
                                        <?php endif; ?>
                                        <div class="col-xs-12">
                                            <?php
                                                echo $this->Form->input('subject', array(
                                                    'label'         => array('text' => __('Email Subject') .'&nbsp;<span class="mandatory">*</span>'),
                                                    'class'         => 'required col-xs-12 col-sm-12',
                                                    'div'           => false,
                                                    'tabindex'      => 6
                                                ));
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row hr hr-16 hr-dotted"></div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h5><?php echo __('Email Market Mailing List'); ?></h5>
                                        </div>
                                        <div class="col-xs-12">
                                            <?php
                                                if(empty($subscriberList)){
                                                    echo '<font color="red">' .__("No mailing list found. Please create a new mailing list first.") .'</font>';
                                                }else{
                                                    echo $this->Form->input('EmailMarketingMailingList', array(
                                                        'label'     => array('text' => __('Subscriber List') .'&nbsp;<span class="mandatory">*</span>'),
                                                        'type'      => 'select', 
                                                        'multiple'  => 'checkbox',
                                                        'options'   => $subscriberList,
                                                    ));
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="add-html-message">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php
                                            if(isset($this->request->data["EmailMarketingCampaign"]["use_template"]) || isset($this->request->data["EmailMarketingCampaign"]["use_purchased_template"])){
                                                $isUseTemplate          = !empty($this->request->data["EmailMarketingCampaign"]["use_template"]);
                                                $isUsePurchasedTemplate = !empty($this->request->data["EmailMarketingCampaign"]["use_purchased_template"]);
                                            }else{
                                                $isUseTemplate          = !empty($this->request->data["EmailMarketingCampaign"]["email_marketing_template_id"]);
                                                if($isUseTemplate){ $this->request->data["EmailMarketingCampaign"]["use_template"] = 1; }
                                                
                                                $isUsePurchasedTemplate = !empty($this->request->data["EmailMarketingCampaign"]["email_marketing_purchased_template_id"]);
                                                if($isUsePurchasedTemplate){ $this->request->data["EmailMarketingCampaign"]["use_purchased_template"] = 1; }
                                            }
                                        ?>
                                        <?php
                                            $isUseExternalWebPage = isset($this->request->data["EmailMarketingCampaign"]["use_external_web_page"]) && $this->request->data["EmailMarketingCampaign"]["use_external_web_page"] == 1;
                                            echo $this->Form->input('use_external_web_page', array(
                                                'label'         => array('text' => __('Use External Web Page')),
                                                'type'          => 'checkbox',
                                                'value'         => '1',
                                                'hiddenField'   => true,
                                                'div'           => false,
                                                'style'         => 'float: left; margin-right: 10px;',
                                                'tabindex'      => 1
                                            ));
                                        ?>
                                    </div>
                                    <div class="col-xs-12">
                                        <?php
                                            echo $this->Form->input('use_template', array(
                                                'label'         => array('text' => __('Use Own Email Template')),
                                                'type'          => 'checkbox',
                                                'value'         => '1',
                                                'hiddenField'   => true,
                                                'div'           => false,
                                                'style'         => 'float: left; margin-right: 10px;',
                                                'tabindex'      => 2
                                            ));
                                        ?>
                                    </div>
                                    <div class="col-xs-12">
                                        <?php
                                            echo $this->Form->input('use_purchased_template', array(
                                                'label'         => array('text' => __('Use Purchased Email Template')),
                                                'type'          => 'checkbox',
                                                'value'         => '1',
                                                'hiddenField'   => true,
                                                'div'           => false,
                                                'style'         => 'float: left; margin-right: 10px;',
                                                'tabindex'      => 3
                                            ));
                                        ?>
                                    </div>
                                    <div class="space-14 new-line">&nbsp;</div>
                                    <div class="col-xs-12" id="template_id" style="display:<?php echo ($isUseExternalWebPage || $isUseTemplate || $isUsePurchasedTemplate) ? 'none' : 'block'; ?>;">
                                       <?php
                                            echo $this->Form->input('template_data', array(
                                                'label'         => false,
                                                'type'          => 'textarea',
                                                'div'           => false,
                                                'class'         => 'col-xs-12 col-sm-12 height-220',
                                                'rows'          => false,
                                                'cols'          => false,
                                                'tabindex'      => 4
                                            ));
                                        ?>
                                    </div>
                                    <div class="col-xs-12" id="html_url" style="display:<?php echo $isUseExternalWebPage ? 'block' : 'none'; ?>;">
                                        <?php
                                            echo $this->Form->input('html_url', array(
                                                'label'         => array('text' => __('External Web Page URL')),
                                                'class'         => 'required required-when-visible col-xs-12 col-sm-12',
                                                'div'           => false,
                                                'placeholder'   => 'http://your-domain.tld',
                                                'tabindex'      => 4
                                            ));
                                        ?>
                                    </div>
                                    <div class="col-xs-12" id="template_list" style="display:<?php echo $isUseTemplate ? 'block' : 'none'; ?>;">
                                        <?php
                                            echo $this->Form->input('email_marketing_template_id', array(
                                                'label'         => array('text' => __('Own Template')),
                                                'options'       => $templateList,
                                                'class'         => 'required required-when-visible col-xs-12 col-sm-12',
                                                'div'           => false,
                                                'tabindex'      => 4
                                            ));
                                        ?>
                                    </div>
                                    <div class="col-xs-12" id="purchased_template_list" style="display:<?php echo $isUsePurchasedTemplate ? 'block' : 'none'; ?>;">
                                        <?php
                                            echo $this->Form->input('email_marketing_purchased_template_id', array(
                                                'label'         => array('text' => __('Purchased Template')),
                                                'options'       => $purchasedTemplateList,
                                                'class'         => 'required required-when-visible col-xs-12 col-sm-12',
                                                'div'           => false,
                                                'tabindex'      => 4
                                            ));
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="notice-area">
                                    <h3><?php echo __("Notice"); ?>:</h3>
                                    <ul>
                                        <li>
                                            <?php echo __('If you choose "HTML" or "Both" Email Format, please complete HTML version message.'); ?>
                                        </li>
                                        <li>
                                            <?php echo __('You can use internal tags in email content. Internal Tag: [FIRST-NAME], [LAST-NAME], [EMAIL]'); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane" id="add-text-message">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php
                                            echo $this->Form->input('text_message', array(
                                                'type'          => 'textarea',
                                                'div'           => false,
                                                'class'         => 'col-xs-12 col-sm-12',
                                                'style'         => 'height:470px;',
                                                'tabindex'      => 1
                                            ));
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12" data-ajax-content="true">
                                        <button type="button" class="btn btn-white btn-primary html-to-text right"><?php echo __('Convert HTML template to text'); ?></button>
                                    </div>
                                </div>
                                <div class="notice-area">
                                    <h3><?php echo __("Notice"); ?>:</h3>
                                    <ul>
                                        <li>
                                            <?php echo __('If you choose "Text" or "Both" Email Format, please fill in text version message.'); ?>
                                        </li>
                                        <li>
                                            <?php echo __('You can use internal tags in email content. Internal Tag: [FIRST-NAME], [LAST-NAME], [EMAIL]'); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php

    $successConvertMsg              = __('Successfully convert HTML template to text');
    $convertContentMissingMsg       = __('HTML template content is missing');
    $convertTemplateMissingMsg      = __('Please select a template before conversion');
    $convertExternalUrlMissingMsg   = __('Please enter a valid external web page URL before conversion');
    $baseUrl                        = FULL_BASE_URL;
    $htmlTemplateMissingMsg         = __('Please add HTML email template');
    
    $inlineJS = <<<EOF
    
        $('div.input.select div.checkbox input[type="checkbox"][id^="EmailMarketingMailingListEmailMarketingMailingList"]').css({'float': 'left', 'position': 'relative', 'margin-left': '0'});
    
        $('form[id^="EmailMarketingCampaign"][id$="Form"]').validate({
            rules: {
                    "data[EmailMarketingCampaign][email_marketing_user_id]": {
                        required: true
                    },
                    "data[EmailMarketingCampaign][subject]": {
                        required: true
                    },
                    "data[EmailMarketingCampaign][name]": {
                        required: true
                    },
                    "data[EmailMarketingCampaign][send_format]": {
                        required: true
                    }
            }
        });
        
        var validateHtmlTemplate = function(){
        
            var externalWebPage     = document.getElementById("EmailMarketingCampaignUseExternalWebPage");
            var ownTemplate         = document.getElementById("EmailMarketingCampaignUseTemplate");
            var purchasedTemplate   = document.getElementById("EmailMarketingCampaignUsePurchasedTemplate"); 
             
            if(externalWebPage.checked || ownTemplate.checked || purchasedTemplate.checked){
            
                if(externalWebPage.checked){
                    return document.getElementById("EmailMarketingCampaignHtmlUrl").value;
                }
                
                if(ownTemplate.checked){
                    return $("#EmailMarketingCampaignEmailMarketingTemplateId").val();
                }
                
                if(purchasedTemplate.checked){
                    return $("#EmailMarketingCampaignEmailMarketingPurchasedTemplateId").val();
                }
            
            }else{
            
                return tinymce.activeEditor ? tinymce.activeEditor.getContent() : $('#EmailMarketingCampaignTemplateData').val();
            }
        };
    
        var preCheckTemplate = function(showHTMLTemplateErrorMessage){
            if(showHTMLTemplateErrorMessage == undefined || showHTMLTemplateErrorMessage == null || showHTMLTemplateErrorMessage == ""){
                showHTMLTemplateErrorMessage = false;
            }
            switch($('select#EmailMarketingCampaignSendFormat').val()){
                case 'TXT':
                    $('li.txt').css('display', 'block');
                    $('li.html').css('display', 'none');
                    $('#EmailMarketingCampaignTextMessage').attr('required', true);
                    if($('#generate-html-field').length){
                        $('#generate-html-field').remove();
                    }
                    break;
                case 'HTML':
                    $('li.txt').css('display', 'none');
                    $('li.html').css('display', 'block');
                    $('#EmailMarketingCampaignTextMessage').attr('required', false);
                    if(!validateHtmlTemplate()){
                        if(!$('#generate-html-field').length){
                            $('form[id^="EmailMarketingCampaign"][id$="Form"]').append('<input type="hidden" id="generate-html-field" required="required" />');
                        }
                        setTimeout(function(){ $('#generate-html-field-error').css('display', 'none'); }, 100);
                        if(showHTMLTemplateErrorMessage){
                            $('li.html').addClass('active').siblings('li').removeClass('active');
                            $('#add-html-message.tab-pane').addClass('active').siblings('.tab-pane').removeClass('active');
                            messageBox({"status": ERROR, "message": "{$htmlTemplateMissingMsg}"});
                        }
                    }else if($('#generate-html-field').length){
                        $('#generate-html-field').remove();
                    }
                    break;
                case 'BOTH':
                    $('li.txt').css('display', 'block');
                    $('li.html').css('display', 'block');
                    $('#EmailMarketingCampaignTextMessage').attr('required', true);
                    if(!validateHtmlTemplate()){
                        if(!$('#generate-html-field').length){
                            $('form[id^="EmailMarketingCampaign"][id$="Form"]').append('<input type="hidden" id="generate-html-field" required="required" />');
                        }
                        setTimeout(function(){ $('#generate-html-field-error').css('display', 'none'); }, 100);
                        if(showHTMLTemplateErrorMessage){
                            $('li.html').addClass('active').siblings('li').removeClass('active');
                            $('#add-html-message.tab-pane').addClass('active').siblings('.tab-pane').removeClass('active');
                            messageBox({"status": ERROR, "message": "{$htmlTemplateMissingMsg}"});
                        }
                    }else if($('#generate-html-field').length){
                        $('#generate-html-field').remove();
                    }
                    break;
            }
        };
        preCheckTemplate();
        $('button.submit-iframe-form-btn', window.parent.document).click(function(){
            preCheckTemplate(true);
        });
    
        $('select#EmailMarketingCampaignSendFormat').on('selectmenuchange', function(){
            switch($(this).val()){
                case 'TXT':
                    $('li.txt').css('display', 'block');
                    $('li.html').css('display', 'none');
                    $('#EmailMarketingCampaignTextMessage').attr('required', true);
                    if($('#generate-html-field').length){
                        $('#generate-html-field').remove();
                    }
                    break;
                case 'HTML':
                    $('li.txt').css('display', 'none');
                    $('li.html').css('display', 'block');
                    $('#EmailMarketingCampaignTextMessage').attr('required', false);
                    if(!validateHtmlTemplate()){
                        if(!$('#generate-html-field').length){
                            $('form[id^="EmailMarketingCampaign"][id$="Form"]').append('<input type="hidden" id="generate-html-field" required="required" />');
                        }
                        setTimeout(function(){ $('#generate-html-field-error').css('display', 'none'); }, 100);
                    }else if($('#generate-html-field').length){
                        $('#generate-html-field').remove();
                    }
                    break;
                case 'BOTH':
                    $('li.txt').css('display', 'block');
                    $('li.html').css('display', 'block');
                    $('#EmailMarketingCampaignTextMessage').attr('required', true);
                    if(!validateHtmlTemplate()){
                        if(!$('#generate-html-field').length){
                            $('form[id^="EmailMarketingCampaign"][id$="Form"]').append('<input type="hidden" id="generate-html-field" required="required" />');
                        }
                        setTimeout(function(){ $('#generate-html-field-error').css('display', 'none'); }, 100);
                    }else if($('#generate-html-field').length){
                        $('#generate-html-field').remove();
                    }
                    break;
            }
        });
        
        $('.html-to-text').click(function(){
            
            if($('#template_id').css('display') == 'block'){
            
                var content = tinymce.activeEditor.getContent({format: 'text'});
                if(content){
                    
                    $('#EmailMarketingCampaignTextMessage').val(content);
                    messageBox({"status": SUCCESS, "message": "{$successConvertMsg}"});
                
                }else{
                    
                    messageBox({"status": ERROR, "message": "{$convertContentMissingMsg}"});
                }
                
            
            }else{
                
                var useOwnTemplate          = document.getElementById('EmailMarketingCampaignUseTemplate').checked;
                if(useOwnTemplate){
                
                    var templateId = $('#EmailMarketingCampaignEmailMarketingTemplateId').val();
                    if(templateId){
                    
                        if($.fn.ace_ajax){
                            $('[data-ajax-content=true]').ace_ajax('startLoading');
                        }
                        $.get('{$baseUrl}/admin/email_marketing/email_marketing_campaigns/getTemplateContentById/'+templateId+'/'+$('#EmailMarketingCampaignEmailMarketingUserId').val()+'/OWN', function(data){
                            $('#EmailMarketingCampaignTextMessage').val(createTextVersion(data));
                            messageBox({"status": SUCCESS, "message": "{$successConvertMsg}"});
                        }).always(function(){
                            if($.fn.ace_ajax){
                                $('[data-ajax-content=true]').ace_ajax('stopLoading', true);
                            }
                        });
                    
                    }else{
                        
                        messageBox({"status": ERROR, "message": "{$convertTemplateMissingMsg}"});
                    }
                }
                
                var usePurchasedTemplate    = document.getElementById('EmailMarketingCampaignUsePurchasedTemplate').checked;
                if(usePurchasedTemplate){
                
                    var templateId = $('#EmailMarketingCampaignEmailMarketingPurchasedTemplateId').val();
                    if(templateId){
                    
                        if($.fn.ace_ajax){
                            $('[data-ajax-content=true]').ace_ajax('startLoading');
                        }
                        $.get('{$baseUrl}/admin/email_marketing/email_marketing_campaigns/getTemplateContentById/'+templateId+'/'+$('#EmailMarketingCampaignEmailMarketingUserId').val()+'/PURCHASED', function(data){
                            $('#EmailMarketingCampaignTextMessage').val(createTextVersion(data));
                            messageBox({"status": SUCCESS, "message": "{$successConvertMsg}"});
                        }).always(function(){
                            if($.fn.ace_ajax){
                                $('[data-ajax-content=true]').ace_ajax('stopLoading', true);
                            }
                        });
                        
                    }else{
                        
                        messageBox({"status": ERROR, "message": "{$convertTemplateMissingMsg}"});
                    }
                }
                
                var useExternalWebPage      = document.getElementById('EmailMarketingCampaignUseExternalWebPage').checked;
                if(useExternalWebPage){
                
                    var url = $('#EmailMarketingCampaignHtmlUrl').val();
                    if(url && validURL(url)){
                    
                        if($.fn.ace_ajax){
                            $('[data-ajax-content=true]').ace_ajax('startLoading');
                        }
                        $.post('{$baseUrl}/admin/email_marketing/email_marketing_campaigns/getExternalWebPageContent.json', {"externalWebPageUrl": url}, function(data){
                            $('#EmailMarketingCampaignTextMessage').val(createTextVersion(data));
                            messageBox({"status": SUCCESS, "message": "{$successConvertMsg}"});
                        }).always(function(){
                            if($.fn.ace_ajax){
                                $('[data-ajax-content=true]').ace_ajax('stopLoading', true);
                            }
                        });
                    
                    }else{
                    
                        messageBox({"status": ERROR, "message": "{$convertExternalUrlMissingMsg}"});
                    }
                }
            }
        });
        
        var changeFormFieldForExternalUrl = function(identifier){
            if($(identifier).is(':checked')){
                $('#template_id').css({'display':'none'});
                
                $('#template_list').css({'display':'none'});
                $('#EmailMarketingCampaignUseTemplate').prop('checked', false);
                
                $('#purchased_template_list').css({'display':'none'});
                $('#EmailMarketingCampaignUsePurchasedTemplate').prop('checked', false);
                
                $('#html_url').css({'display':'block'});
            }else{
                $('#template_id').css({'display':'block'});
                $('#html_url').css({'display':'none'});
            }
        };
        $('input#EmailMarketingCampaignUseExternalWebPage').on('click', function(){
            changeFormFieldForExternalUrl(this);
        });
        
        var changeFormFieldForTemplate = function(identifier){
            if($(identifier).is(':checked')){
                $('#template_id').css({'display':'none'});
                
                $('#html_url').css({'display':'none'});
                $('#EmailMarketingCampaignUseExternalWebPage').prop('checked', false);
                
                $('#purchased_template_list').css({'display':'none'});
                $('#EmailMarketingCampaignUsePurchasedTemplate').prop('checked', false);
                
                $('#template_list').css({'display':'block'});
            }else{
                $('#template_id').css({'display':'block'});
                $('#template_list').css({'display':'none'});
            }
        };
        $('input#EmailMarketingCampaignUseTemplate').on('click', function(){
            changeFormFieldForTemplate(this);
        });
        
        var changeFormFieldForPurchasedTemplate = function(identifier){
            if($(identifier).is(':checked')){
                $('#template_id').css({'display':'none'});
                
                $('#html_url').css({'display':'none'});
                $('#EmailMarketingCampaignUseExternalWebPage').prop('checked', false);
                
                $('#template_list').css({'display':'none'});
                $('#EmailMarketingCampaignUseTemplate').prop('checked', false);
                
                $('#purchased_template_list').css({'display':'block'});
            }else{
                $('#template_id').css({'display':'block'});
                $('#purchased_template_list').css({'display':'none'});
            }
        };
        $('input#EmailMarketingCampaignUsePurchasedTemplate').on('click', function(){
            changeFormFieldForPurchasedTemplate(this);
        });
        
        loadTinymce("EmailMarketingCampaignTemplateData");
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'loadedScripts' => array('textversion.js'),
        'inlineJS' => $inlineJS
    )); 
?>