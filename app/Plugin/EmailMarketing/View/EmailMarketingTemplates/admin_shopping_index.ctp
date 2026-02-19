<!-- page specific plugin scripts -->
<?php
    
    echo $this->element('page/admin/load_inline_js', array(
        'loadedScripts' => array('zoom.min.js'),
        'inlineJS' => ""
    )); 
?>

<div class="row">
    <div class="col-xs-12">
        <div class="row email-templates">
            <h4><?php echo __('All Templates For Sale'); ?></h4>
            <?php
                $s3Path     = Configure::read('EmailMarketing.email.html.template.preview.path');
                $s3Path     = str_replace("{user_id}", $userId, $s3Path);
                $s3Path     = str_replace("{template_id}", "%", $s3Path);
            
                $previewImageElement = '<img class="preview-template" src="' .Configure::read('System.aws.s3.bucket.link.prefix') .$s3Path .'/%.jpg" border="0" data-action="zoom" onerror="this.src=\'/img/admin/image_coming_soon.jpg\';this.removeAttribute(\'data-action\');" />';
                
                $displayFields = array(
                    'ITEM_NAME'                 => array('EmailMarketingTemplate.name'),
                    $previewImageElement        => array('EmailMarketingTemplate.id'),
                    '$%'                        => array('EmailMarketingTemplate.price', 'isFinalPrice' => TRUE),
                );
                
                $actions = array(
                    '<i class="ace-icon fa fa-shopping-cart bigger-110"></i><span>' .__('Purchase') .'</span>' => array('/admin/email_marketing/email_marketing_templates/buy/', 'EmailMarketingTemplate.id', '', array('class' => 'btn btn-block', 'escape' => false, 'onclick' => 'showPaymentBoxIniFrame(event, null, \'' .$csrfCookieName .'\')', 'data-title' => __('Pay Email Marketing Template Price'), 'data-closest-btn' => __('Close'), 'data-reload-params' => 'forceUpdatePurchasedTemplate=1'))
                );
                
                echo $this->PricingTable->createTable(
                    'EmailMarketingTemplate',
                    $otherTemplates,
                    $displayFields,
                    $actions,
                    __('No email marketing templates found.'),
                    $substitutions
                );
            ?>
        </div>
    </div><!-- /.col -->
    <span class="space-20">&nbsp;</span>
    <div class="paging center">
        <?php echo $this->Paginator->first('first', array('class' => 'sortable')); ?>
        <?php echo $this->Paginator->prev( __('previous'), array('class' => 'sortable'), null, array('class'=>'disabled'));?>
        <?php echo $this->Paginator->numbers(array('class' => 'sortable', 'separator' => ' '));?>
        <?php echo $this->Paginator->next(__('next'), array('class' => 'sortable'), null, array('class' => 'disabled'));?>
        <?php echo $this->Paginator->last('last', array('class' => 'sortable')); ?>
    </div>
</div><!-- /.row -->