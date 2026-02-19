<div class="row">
    <div class="col-xs-12">
        <div class="row email-templates">
            <h4><?php echo __('Purchased Templates'); ?></h4>
            <?php
                $s3Path     = Configure::read('EmailMarketing.email.html.template.preview.path');
                $s3Path     = str_replace("{user_id}", $userId, $s3Path);
                $s3Path     = str_replace("{template_id}", "%", $s3Path);
            
                $previewImageElement = '<img class="preview-template" src="' .Configure::read('System.aws.s3.bucket.link.prefix') .$s3Path .'/%.jpg" border="0" data-action="zoom" onerror="this.src=\'/img/admin/image_coming_soon.jpg\';this.removeAttribute(\'data-action\');" />';
            
                $displayFields = array(
                    'ITEM_NAME'                 => array('EmailMarketingTemplate.name'),
                    $previewImageElement        => array('EmailMarketingTemplate.id', 'isFinalRow' => TRUE)
                );
                
                $actions = array(
                    '<i class="ace-icon fa fa-edit bigger-110"></i><span>' .__('Customize') .'</span>' => array('/admin/email_marketing/email_marketing_templates/customiseTemplateHtml/', 'EmailMarketingTemplate.id', '', array('class' => 'btn btn-block', 'escape' => false, 'target' => '_blank'))
                );
                
                echo $this->PricingTable->createTable(
                    'EmailMarketingTemplate',
                    $purchasedTemplates,
                    $displayFields,
                    $actions,
                    __('You have no purchased templates for now.'),
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

<!-- page specific plugin scripts -->
<?php
    echo $this->element('page/admin/load_inline_js', array(
        'loadedScripts' => array('zoom.min.js'),
        'inlineJS' => ''
    )); 
?>