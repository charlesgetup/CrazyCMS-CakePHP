<?php $type = strtoupper($type); ?>
<?php if($type == Configure::read('EmailMarketing.email.type.text')): ?>

    <?php
        echo $campaign['EmailMarketingCampaign']['text_message'];
    ?>

<?php elseif($type == Configure::read('EmailMarketing.email.type.html')): ?>

    <?php
        if(isset($externalContent) && !empty($externalContent)){
            $emailPreviewContent = $externalContent;
        }elseif(!empty($campaign ["EmailMarketingCampaign"] ["email_marketing_template_id"])){
            $emailPreviewContent = unserialize($campaign['EmailMarketingTemplate']['html']);
        }elseif (!empty($campaign ["EmailMarketingCampaign"] ["email_marketing_purchased_template_id"])){
            $emailPreviewContent = unserialize($campaign['EmailMarketingPurchasedTemplate']['customized_html']);
        }else{
            $emailPreviewContent = html_entity_decode($campaign['EmailMarketingCampaign']['template_data'], ENT_QUOTES, 'UTF-8');
        }

        if(is_array($emailPreviewContent) || (strtolower(substr($emailPreviewContent, 0, 5)) !== "<html" && strtolower(substr($emailPreviewContent, 0, 9)) !== "<!DOCTYPE")){
            $templateCss = empty($emailPreviewContent['gjs-css']) ? '' : '<style type="text/css">' .$emailPreviewContent['gjs-css'] .'</style>';
            $emailPreviewContent = $emailContentHeader .(empty($emailPreviewContent['gjs-html']) ? $emailPreviewContent : $emailPreviewContent['gjs-html'] .$templateCss) ."</body></html>";
        }
        
        $emailPreviewContent = preg_replace('/^\s+|\n|\r|\s+$/m', '', addcslashes($emailPreviewContent, '"\\/')); // not add slashes to single quotes
    ?>
    
    <script>
        window.document.write("<?php echo $emailPreviewContent; ?>");
    </script>

<?php endif; ?>