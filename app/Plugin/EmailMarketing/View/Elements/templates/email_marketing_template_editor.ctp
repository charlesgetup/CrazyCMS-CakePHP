<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo __('Email Editor'); ?></title>
    
    <?php 
        echo $this->Minify->css(array(
            'admin/ace.css',
            'admin/jquery.gritter'
        )); 
    ?>
    
    <script type="text/javascript">
        <?php
            /* Global JS settings */
            foreach(Configure::read('System.variable') as $systemVar){
            
                if($systemVar == Configure::read('System.variable.debug')){
                
                    $debug = (Configure::read('debug') > 0) ? 'true' : 'false';
                    echo "var {$systemVar} = {$debug};";
                    
                }else{
                    
                    echo "var {$systemVar} = '{$systemVar}';";
                }
            }
        ?>
    </script>
    
    <?php 
        echo $this->Minify->css(array(
            'admin/grapesjs/css/grapes.min',
            'admin/grapesjs-newsletter/style/material',
            'admin/grapesjs-newsletter/style/tooltip',
            'admin/grapesjs-newsletter/style/toastr.min',
            'admin/grapesjs-newsletter/dist/grapesjs-preset-newsletter'
        )); 
    ?>

    <?php 
        echo $this->Minify->script(array(
            'admin/grapesjs-newsletter/js/jquery-3.2.1.min',
            'admin/grapesjs/grapes.min',
            'admin/grapesjs-newsletter/dist/grapesjs-preset-newsletter.min',
            'admin/grapesjs-newsletter/dist/grapesjs-plugin-export.min',
            'admin/grapesjs-newsletter/js/toastr.min',
            'admin/grapesjs-newsletter/js/ajaxable.min',
            'admin/es6-promise.min',
            'admin/es6-promise.auto.min',
            'admin/canvas2image',
            'admin/html2canvas',
            'admin/jquery.gritter',
            'admin/dropzone',
            'admin/general-extend.encrypted', 
            'admin/general-base.encrypted'
        )); 
    ?>

  </head>
  <style>
    body, html{ height: 100%; margin: 0;}
    .nl-link {
      color: inherit;
    }
    .info-link {
      text-decoration: none;
    }
    #info-cont {
      line-height: 17px;
    }
    .grapesjs-logo {
      display: block;
      height: 90px;
      margin: 0 auto;
      width: 90px;
    }
    .grapesjs-logo path{
      stroke: #eee !important;
      stroke-width: 8 !important;
    }

    #toast-container {
      font-size: 13px;
      font-weight: lighter;
    }
    #toast-container > div,
    #toast-container > div:hover{
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
      font-family: Helvetica, sans-serif;
    }
    #toast-container > div{
      opacity: 0.95;
    }
    #gritter-notice-wrapper{
      top: 50%;
      right: 50%;
    }
    .gritter-close {
      right: 3px;
      left: auto;
    }
    
    .gjs-btn-prim {
        margin: 15px 0 0 0;
        padding: 5px;
        background: #263238;
        color: #fff;
    }
    
    .gjs-asset-manager .gjs-btn-prim {
        margin: 0;
    }
    
    #restore-to-original-version button.gjs-btn-prim {
        font-size: 13px;
        color: #fff;
        padding: 10px 15px;
    }
    
    /* Temporary hide remove assets button */
    .gjs-asset-manager div[data-toggle="asset-remove"] {
        display: none;
        visibility: hidden;
    }
  </style>
  <body>


    <div id="gjs" style="height:0px; overflow:hidden">


      <?php
        $templateContent = empty($template['html']) ? (empty($template['customized_html']) ? null : unserialize($template['customized_html'])) : unserialize($template['html']);
        echo empty($templateContent['gjs-html']) ? '' : $templateContent['gjs-html'];
      ?>


      <style>
        <?php echo empty($templateContent['gjs-css']) ? '' : $templateContent['gjs-css']; ?>
      </style>


    </div>


    <?php if(empty($customiseTemplateId)): ?>
        <div id="save-popup" style="display:none">
          <div class="gjs-import-label">
            <div class="gjs-trt-traits">
                <div class="gjs-trt-trait">
                    <div class="gjs-label"><?php echo __('Template Name'); ?></div>
                    <div class="gjs-field gjs-field-text">
                        <div class="gjs-input-holder">
                            <input type="text" name="EmailMarketingTemplate[name]" placeholder="<?php echo __('eg. Promotion Newsletter'); ?>" value="<?php echo (empty($template['name']) ? '' : $template['name']); ?>" />
                        </div>
                    </div>
                </div>
                <?php if(!$this->EmailMarketingPermissions->isClient()): ?>
                    <div class="gjs-trt-trait">
                        <div class="gjs-label"><?php echo __('Template Price'); ?></div>
                        <div class="gjs-field gjs-field-text">
                            <div class="gjs-input-holder">
                                <input type="number" name="EmailMarketingTemplate[price]" step="0.01" min="0" placeholder="<?php echo __('eg. 0.00'); ?>" value="<?php echo (empty($template['price']) ? '' : $template['price']); ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="gjs-trt-trait">
                        <div class="gjs-label"><?php echo __('For Sale'); ?></div>
                        <div class="gjs-fields">
                            <div class="gjs-field gjs-field-radio">
                                <div class="gjs-radio-items">
                                    <div class="gjs-radio-item">
                                        <input type="radio" class="gjs-sm-radio" id="border-collapse-separate" name="EmailMarketingTemplate[for_sale]" value="1" <?php echo ((@$template['for_sale'] == 1) ? 'check="checked"' : ''); ?> />
                                        <label class="gjs-radio-item-label" for="border-collapse-separate"><?php echo __("Yes"); ?></label>
                                    </div>
                                    <div class="gjs-radio-item">
                                        <input type="radio" class="gjs-sm-radio" id="border-collapse-separate" name="EmailMarketingTemplate[for_sale]" value="0" <?php echo ((@$template['for_sale'] == 0) ? 'check="checked"' : ''); ?> />
                                        <label class="gjs-radio-item-label" for="border-collapse-separate"><?php echo __("No"); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="space-10">&nbsp;</div>
                <div class="gjs-trt-trait">
                    <div class="gjs-label">&nbsp;</div>
                    <div class="gjs-fields">
                        <button class="gjs-btn-prim btn-light"><?php echo __('Save'); ?></button>
                    </div>
                </div>
            </div>
          </div>
        </div>
    <?php else: ?>
        <div id="restore-to-original-version" style="display:none">
            <div class="gjs-import-label">
                <p>
                    <?php echo __('Do you want to restore to the original state of this purchased template?'); ?>
                    <br />
                    <br />
                    <?php echo __('Press "Restore" to proceed, or press "Cancel" to abort.'); ?>
                    <br />
                    <br />
                    <strong>
                        <font color="red">
                            <?php echo __('This action cannot be undone.'); ?>
                        </font>
                    </strong>
                </p>
                <div>
                    <div class="gjs-fields">
                        <button class="gjs-btn-prim btn-light restore"><?php echo __('Restore'); ?></button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="gjs-btn-prim btn-light cancel"><?php echo __('Cancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div id="info-cont" style="display:none">
      <br/>
      <svg class="grapesjs-logo" xmlns="http://www.w3.org/2000/svg" version="1"><g id="gjs-logo"><path d="M40 5l-12.9 7.4 -12.9 7.4c-1.4 0.8-2.7 2.3-3.7 3.9 -0.9 1.6-1.5 3.5-1.5 5.1v14.9 14.9c0 1.7 0.6 3.5 1.5 5.1 0.9 1.6 2.2 3.1 3.7 3.9l12.9 7.4 12.9 7.4c1.4 0.8 3.3 1.2 5.2 1.2 1.9 0 3.8-0.4 5.2-1.2l12.9-7.4 12.9-7.4c1.4-0.8 2.7-2.2 3.7-3.9 0.9-1.6 1.5-3.5 1.5-5.1v-14.9 -12.7c0-4.6-3.8-6-6.8-4.2l-28 16.2" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-width:10;stroke:#fff"/></g></svg>
      <br/>
      <div class="gjs-import-label">
        <b>GrapesJS Newsletter Builder</b> is a showcase of what/how is possible to build an editor using the
        <a class="info-link gjs-color-active" target="_blank" href="http://artf.github.io/grapesjs/">GrapesJS</a>
        core library. Is not intended to represent a complete builder solution.
        <br/><br/>
        For any tip about this demo (or newsletters construction in general) check the
        <a class="info-link gjs-color-active" target="_blank" href="https://github.com/artf/grapesjs-preset-newsletter">Newsletter Preset repository</a>
        and open an issue. For any problem with the builder itself, open an issue on the main
        <a class="info-link gjs-color-active" target="_blank" href="https://github.com/artf/grapesjs">GrapesJS repository</a>.
        <br/><br/>
        Being a free and open source project contributors and supporters are extremely welcome.
      </div>
    </div>

    <!-- page specific plugin scripts -->
    <?php
        
        $inlineJS = <<<EOF
        
            /* When the template didn't use any assets or customize the purchased template for the first time, load client's own assets.
             In the other situation, load the template's assets */
EOF;

        if(empty($templateContent['gjs-assets']) || (!empty($customiseTemplateId) && isset($template['html']))){
        
            $inlineJS .= <<<EOF
                var images = [
EOF;

            if(!empty($assets) && is_array($assets)){
                foreach($assets as $asset){
                
                    $inlineJS .= <<<EOF
                        '{$asset}',
EOF;
                }
            }
            
            $inlineJS .= <<<EOF
                ];
EOF;

        }else{
        
            $inlineJS .= <<<EOF
                var images = {$templateContent['gjs-assets']};
EOF;
        }
        
        $uploadEndpoint = FULL_BASE_URL .DS .'admin' .DS .'email_marketing' .DS .'email_marketing_templates' .DS .'uploadAssets';
        $urlStore = FULL_BASE_URL .DS .'admin' .DS .'email_marketing' .DS .'email_marketing_templates' .DS .(empty($templateId) ? (empty($customiseTemplateId) ? 'add' : 'customiseTemplateHtml' .DS .$customiseTemplateId) : 'edit' .DS .$templateId);
        $urlLoad = FULL_BASE_URL .DS .'admin' .DS .'email_marketing' .DS .'email_marketing_templates' .DS .(empty($templateId) ? (empty($customiseTemplateId) ? 'add' : 'customiseTemplateHtml' .DS .$customiseTemplateId) : 'edit' .DS .$templateId);
        $templateSavedSuccessfully = __('Template changes are saved successfully.');
        $templateEditPath = FULL_BASE_URL .DS .'admin' .DS .'email_marketing' .DS .'email_marketing_templates' .DS .'edit' .DS;
        $dropFileTxt = __('Drop files here or click to upload');
        $addImgTxt = __('Add image');
        $unknownErrTxt = __('Unknown error. Please wait for a while and try again.');
        $imgFileTypeTxt = __('Only image file (.jpg, .jpeg, .png, .gif) is allowed to add here.');
        $dropAssetsTxt = __('Drop here your assets');
        $selectImgTxt = __('Select Image');
        $cssImportTxt = __('Paste all your code/css here below and click import');
        $cssExportTxt = __('Copy the code/css and use it wherever you want');
        $importPlaceHolderTxt = __('Hello world!');
        $exportToZipTxt = __('Export to ZIP');
        $removeTasksUploadsPath = FULL_BASE_URL .DS .'admin' .DS .'task_management' .DS .'task_management_task_uploads' .DS .'remove';
        $templateIdInJS = (empty($templateId) ? (empty($customiseTemplateId) ? '' : $customiseTemplateId) : $templateId);
        $savePreviewImgPath = FULL_BASE_URL .DS .'admin' .DS .'email_marketing' .DS .'email_marketing_templates' .DS .'savePreviewImage' .DS;
        $browserVersionTxt = __("Browser version is too low. Please upgrade to latest version to experience better performance.");
        $noTxt = __("No");
        $yesTxt = __("Yes");
        $processingTxt = __("Processing the request now. Please don't close or refresh this web page.");
        $enterTemplateNameTxt = __('Please enter template name');
        $saveTemplateTxt = __('Save Template');
        
        $inlineJS .= <<<EOF
            /* Set up GrapesJS editor with the Newsletter plugin */
            var uploadEndpoint = '{$uploadEndpoint}';
            var remoteUploadAssets = [];
            var newlyAddedTemplateId = null;
            var editor = grapesjs.init({
                height: '100%',
                /* noticeOnUnload: 0,*/
                storageManager:{
                    autosave: false,
                    autoload: false,
                    setStepsBeforeSave: 15,
                    type: 'remote',
                    headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                    urlStore: '{$urlStore}',
                    urlLoad: '{$urlLoad}',
                    beforeSend: function () {
                        
                    },
                    onComplete: function(jqXHR, status){
                        try{
                            var result = $.parseJSON(jqXHR);
                            if(result.error){
                                console.error(result.error);
                            }
                            if(result.success){
                                newlyAddedTemplateId = result.success;
                                console.success('{$templateSavedSuccessfully}');
                                var storeManagerConfig = editor.StorageManager.getConfig();
                                
                                /* Switch to edit action after first save */
                                if(storeManagerConfig.urlStore.indexOf('edit') < 0 && storeManagerConfig.urlStore.indexOf('customiseTemplateHTML') < 0 && parseInt(result.success) == result.success){
                                    storeManagerConfig.urlStore = '{$templateEditPath}' + result.success;
                                    storeManagerConfig.urlLoad = storeManagerConfig.urlStore;
                                    editor.StorageManager.init(storeManagerConfig);
                                }
                                
                                editor.Modal.close();
                            }
                        }catch(err){
                            ajaxErrorHandler(jqXHR, status, err);
                        }
                    }
                },
                assetManager: {
                    assets: images,
                    upload: uploadEndpoint,
                    uploadName: 'files',
                    autoAdd: 0,
                    uploadText: '{$dropFileTxt}',
                    addBtnText: '{$addImgTxt}',
                    uploadFile: function(e){
                        var files = e.dataTransfer? e.dataTransfer.files : e.target.files;
                        var formData = new FormData();
                        for(var i in files){
                            formData.append('file-' + i, files[i]);
                        }
                        $.ajax({
                            url: uploadEndpoint,
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            crossDomain: true,
                            mimeType: "multipart/form-data",
                            processData: false,
                            headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                            success: function(result){
                                result = $.parseJSON(result); 
                                if(result.error){
                                    console.error(result.error);
                                }else if(result.data){
                                    var images = result.data;
                                    editor.AssetManager.add(images);
                                }else{
                                    console.warn('{$unknownErrTxt}');
                                }
                            }
                        });
                    },
                    handleAdd: function(textFromInput){
                        var isValid = /\.jpe?g|\.png|\.gif$/i.test(textFromInput);
                        if(isValid){
                            remoteUploadAssets.push(textFromInput);
                            editor.AssetManager.add(textFromInput);
                        }else{
                            console.error('{$imgFileTypeTxt}');
                        }
                    },
                    /* TODO this makes the asset manager displayed after all component dropping. Maybe we can use it in future version */
                    /* dropzone: 1,
                    dropzoneContent: '<div class="dropzone-inner">{$dropAssetsTxt}</div>', */
                    modalTitle: '{$selectImgTxt}'
                },
                container : '#gjs',
                fromElement: true,
                plugins: ['gjs-preset-newsletter', 'gjs-plugin-export'],
                pluginsOpts: {
                    'gjs-preset-newsletter': {
                        modalLabelImport: '{$cssImportTxt}',
                        modalLabelExport: '{$cssExportTxt}',
                        codeViewerTheme: 'material', 
                        /* defaultTemplate: templateImport, */
                        importPlaceholder: '<table class="table"><tr><td class="cell">{$importPlaceHolderTxt}</td></tr></table>',
                        cellStyle: {
                            'font-size': '12px',
                            'font-weight': 300,
                            'vertical-align': 'top',
                            color: 'rgb(111, 119, 125)',
                            margin: 0,
                            padding: 0,
                        }
                    },
                    'gjs-plugin-export': {
                        btnLabel: '{$exportToZipTxt}',
                        preHtml: '<!doctype><html><head><link rel="stylesheet" href="./css/style.css"></head><body>',
                        postHtml: '</body><html>'
                    }
                }
            });
            
            /* NOTICE: email marketing template assets are shared between all templates the same user created. 
                We didn't allow the asset to be deleted, because the same assets any used in other template
                Or in the sold templates
                When later we need this function, we will come back to finish it */
            /*
                editor.on('asset:remove', function(asset){
                    $.ajax({
                        url: '{$removeTasksUploadsPath}',
                        type: 'POST',
                        data: {"templateId": {$templateIdInJS}, "type" : asset.attributes.type, "src": asset.attributes.src},
                        dataType: "json",
                        headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                        success: function(result){
                            if(result.error){
                                console.error(result.error);
                            }else if(result.success){
                                console.success(result.success);
                            }else{
                                console.warn('{$unknownErrTxt}');
                            }
                            $.gritter.removeAll();
                        }
                    });
                });
            */
            
            var mdlClass = 'gjs-mdl-dialog-sm';
            var pnm = editor.Panels;
            var cmdm = editor.Commands;
            var md = editor.Modal;
            
            /* Add manual save template HTML button & popup */
            cmdm.add('save-to-db', {
                run: function(editor, sender){
                    sender && sender.set('active'); /* turn off button */
                    editor.store();
                    /* Auto generate preview image */
                    var pewviewElement  = $('iframe.gjs-frame').contents().find('body').get(0);
                    var previewHeight   = $(pewviewElement).prop('scrollHeight');
                    html2canvas(pewviewElement, {
                        height:     previewHeight,
                        proxy:      '/assets/html2canvas/html2canvas-proxy.php',
                        logging:    false
                    }).then(function(canvas){
                        var savePreviewImage = function(imageData, needManuallyOptimise){
                        
                            var templateId = '{$templateIdInJS}';
                            if(!templateId && newlyAddedTemplateId){
                                templateId = newlyAddedTemplateId;
                            }
        
                            $.ajax({
                                url: '{$savePreviewImgPath}' + templateId + '/' + (needManuallyOptimise ? 1 : 0),
                                type: 'POST',
                                data: {"base64data": imageData},
                                dataType: "json",
                                headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                                success: function(result){
                                    if(result.error){
                                        console.error(result.error);
                                    }else if(result.success){
                                        console.success(result.success);
                                    }else{
                                        console.warn('{$unknownErrTxt}');
                                    }
                                    $.gritter.removeAll();
                                }
                            });
                        }; 
                        if(canvas.toBlob && FileReader){
                            canvas.toBlob(function(blob){
                                var reader = new FileReader();
                                reader.readAsDataURL(blob);
                                reader.onloadend = function(){
                                    var imageData = reader.result;
                                    imageData = imageData.replace("data:image/jpeg;base64,", "");
                                    if(imageData){
                                        savePreviewImage(imageData, false);
                                    }
                                }
                            }, 'image/jpeg', 0.8);
                        }else{
                            var imageData = Canvas2Image.saveAsDATA(canvas, canvas.width, canvas.height);
                            if(imageData){
                                console.log('{$browserVersionTxt}');
                                savePreviewImage(imageData, true);
                            }
                        }
                    });
            
                }
            });
            
            var savePopupContainer = document.getElementById("save-popup");
            if(savePopupContainer){
                cmdm.add('open-save-popup', {
                    run(editor, sender) {
                        sender.set('active', 0);
                        var mdlDialog = document.querySelector('.gjs-mdl-dialog');
                        savePopupContainer.style.display = 'block';
                        $(savePopupContainer).children('.gjs-import-label').children('.gjs-trt-traits').children('.gjs-trt-trait:nth-child(3)').children('.gjs-fields').children('.gjs-field.gjs-field-radio').css('width', '100%').children('.gjs-radio-items').children('.gjs-radio-item').children('label').css('width', '40px').on('click', function(){
                            if($(this).text() == "{$noTxt}"){
                                $(this).prev('input').addClass('checked').prop("checked", true);
                                $(this).closest('.gjs-radio-item').prev('.gjs-radio-item').find('input').first().removeClass('checked').prop("checked", false);
                            }
                            if($(this).text() == "{$yesTxt}"){
                                $(this).prev('input').addClass('checked').prop("checked", true);
                                $(this).closest('.gjs-radio-item').next('.gjs-radio-item').find('input').first().removeClass('checked').prop("checked", false);
                            }
                        });
                        $(savePopupContainer).find('button').first().css({'width': '100px', 'font-size': '13px'}).on('click', function(){
                            messageBox({"status": WARNING, "message": "{$processingTxt}", "sticky": true});
                            var templateName        = $(savePopupContainer).find('input[name="EmailMarketingTemplate[name]"]').val();
                            var templatePrice       = $(savePopupContainer).find('input[name="EmailMarketingTemplate[price]"]').val();
                            var templateIsForSale   = $(savePopupContainer).find('input[name="EmailMarketingTemplate[for_sale]"]:checked').val();
                            if(!templateName){
                                console.error('{$enterTemplateNameTxt}');
                                $.gritter.removeAll();
                                return false;
                            }
                            if(!templatePrice){
                                templatePrice = 0;
                            }
                            if(!templateIsForSale){
                                templateIsForSale = 0;
                            }
                            var storeManagerConfig = editor.StorageManager.getConfig();
                            storeManagerConfig.params = {'EmailMarketingTemplate': '{"name": "' + templateName + '", "price": "' + templatePrice + '", "for_sale": "' + templateIsForSale + '"}'};
                            editor.StorageManager.init(storeManagerConfig);
                            var saveToDBCommand     = editor.Commands.get('save-to-db');
                            saveToDBCommand.run(editor, sender);
                        });
                        md.setTitle('{$saveTemplateTxt}');
                        md.setContent('');
                        md.setContent(savePopupContainer);
                        md.open();
                        md.getModel().once('change:open', function() {
                            mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
                        })
                    }
                });
            }
            
            pnm.addButton('options', [{
               id: (savePopupContainer ? 'open-save-popup' : 'save-to-db'),
               className: 'fa fa-floppy-o',
               command: (savePopupContainer ? 'open-save-popup' : 'save-to-db'),
               attributes: {
                   title: '{$saveTemplateTxt}',
                   'data-tooltip-pos': 'bottom'
               }
            }]);
EOF;
        
        if(!empty($customiseTemplateId)){
        
            $restorePurchasedTemplateTxt = __('Restore Purchased Template');
            $restoreTemplatePath = FULL_BASE_URL .DS .'admin' .DS .'email_marketing' .DS .'email_marketing_templates' .DS .'restore' .DS .$customiseTemplateId .DS .$emailMarketingUserRecordId;
            $restoreTemplateTxt = __('Restore Template');
        
            $inlineJS .= <<<EOF
                var restorePopupContainer = document.getElementById("restore-to-original-version");
                if(restorePopupContainer){
                    cmdm.add('open-restore-popup', {
                        run(editor, sender) {
                          sender.set('active', 0);
                          var mdlDialog = document.querySelector('.gjs-mdl-dialog');
                          restorePopupContainer.style.display = 'block';
                          md.setTitle('{$restorePurchasedTemplateTxt}');
                          md.setContent('');
                          md.setContent(restorePopupContainer);
                          md.open();
                          md.getModel().once('change:open', function() {
                            mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
                          });
                          $('#restore-to-original-version button.cancel').on('click', function(){
                            md.close();
                          });
                          $('#restore-to-original-version button.restore').on('click', function(){
                            $.ajax({
                                url: '{$restoreTemplatePath}',
                                type: 'POST',
                                headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                                contentType: false,
                                /*crossDomain: true,*/
                                processData: false,
                                success: function(result){
                                    if(!result){
                                        console.warn('{$unknownErrTxt}');
                                    }else{
                                        result = $.parseJSON(result); 
                                        if(result.error){
                                            console.error(result.error);
                                        }else if(result.success){
                                            console.success(result.success);
                                            window.location.reload();
                                        }else{
                                            console.warn('{$unknownErrTxt}');
                                        }
                                    }
                                }
                            });
                          });
                        }
                    });
                }
                pnm.addButton('options', [{
                     id: 'restore',
                     className: 'fa fa-bomb',
                     command: 'open-restore-popup',
                     attributes: {
                         title: '{$restoreTemplateTxt}',
                         'data-tooltip-pos': 'bottom',
                     }
                }]);
EOF;
        }
        
        $undoTxt = __('Undo');
        $redoTxt = __('Redo');
        $clearCanvasTxt = __('Clear canvas');
        $clearCanvasConfirmTxt = __('Are you sure to clean the canvas?');
        
        $inlineJS .= <<<EOF
            /* Add info command */
            var infoContainer = document.getElementById("info-cont");
            cmdm.add('open-info', {
                run(editor, sender) {
                    sender.set('active', 0);
                    var mdlDialog = document.querySelector('.gjs-mdl-dialog');
                    mdlDialog.className += ' ' + mdlClass;
                    infoContainer.style.display = 'block';
                    md.setTitle('FAQ');
                    md.setContent('');
                    md.setContent(infoContainer);
                    md.open();
                    md.getModel().once('change:open', function() {
                        mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
                    })
                }
            });
            pnm.addButton('options', [{
                id: 'undo',
                className: 'fa fa-undo',
                attributes: {
                    title: '{$undoTxt}',
                    'data-tooltip-pos': 'bottom',
                },
                command: function(){ editor.runCommand('core:undo') }
            },{
                id: 'redo',
                className: 'fa fa-repeat',
                attributes: {
                    title: '{$redoTxt}',
                    'data-tooltip-pos': 'bottom',
                },
                command: function(){ editor.runCommand('core:redo') }
            },{
                id: 'clear-all',
                className: 'fa fa-trash icon-blank',
                attributes: {
                    title: '{$clearCanvasTxt}',
                    'data-tooltip-pos': 'bottom',
                },
                command: {
                    run: function(editor, sender) {
                        sender && sender.set('active', false);
                        if(confirm('{$clearCanvasConfirmTxt}')){
                            editor.DomComponents.clear();
                            setTimeout(function(){
                                localStorage.clear();
                            },0);
EOF;
        
        if(empty($customiseTemplateId) && !empty($templateId)){
            $cleanTemplatePath = FULL_BASE_URL .DS .'admin' .DS .'email_marketing' .DS .'email_marketing_templates' .DS .'cleanTemplate' .DS .$templateId;
            
            $inlineJS .= <<<EOF
                $.ajax({
                    url: '{$cleanTemplatePath}',
                    type: 'POST',
                    dataType: "json",
                    headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                    success: function(result){
                        if(result.error){
                            console.error(result.error);
                        }else if(result.success){
                            console.success(result.success);
                            window.location.reload();
                        }else{
                            console.warn('{$unknownErrTxt}');
                        }
                    }
                });
EOF;
        }
        
        $faqTxt = __('FAQ');
        
        $inlineJS .= <<<EOF
                        }
                    }
                }
            },{
                id: 'view-info',
                className: 'fa fa-question-circle',
                command: 'open-info',
                attributes: {
                    'title': '{$faqTxt}',
                    'data-tooltip-pos': 'bottom',
                },
            }]);

            /* Simple notifiers */
            toastr.options = {
                closeButton: true,
                preventDuplicates: true,
                showDuration: 300,
                hideDuration: 150
            };
            console.warn = function (msg) {
                toastr.warning(msg);
            };
            console.success = function (msg) {
                toastr.success(msg);
            };
            console.error = function (msg) {
                toastr.error(msg);
            };
            
            $(document).ready(function () {

                /* Beautify tooltips */
                $('*[title]').each(function () {
                var el = $(this);
                var title = el.attr('title').trim();
                if(!title){
                    return;
                }
                el.attr('data-tooltip', el.attr('title'));
                el.attr('title', '');
            });

        });
EOF;
        
        echo $this->element('page/admin/load_inline_js', array(
            'inlineJS' => $inlineJS
        )); 
    ?>

  </body>
</html>