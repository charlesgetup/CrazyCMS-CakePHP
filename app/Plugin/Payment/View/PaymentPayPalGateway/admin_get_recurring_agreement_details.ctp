<div class="row">
    <div class="col-xs-12">
        
        <div id="accordion" class="accordion-style1 panel-group accordion-style2">
            
            <?php foreach($recurringAgreementsDetails as $name => $details): ?>
                <?php $slug = Inflector::slug($name); ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $slug; ?>" aria-expanded="false">
                                <i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                                &nbsp;<?php echo __($name); ?>
                            </a>
                        </h4>
                    </div>
    
                    <div class="panel-collapse collapse" id="<?php echo $slug; ?>" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body">
                            <?php foreach($details as $detail): ?>
                                <blockquote>
                                    <p class="lighter line-height-125">
                                        <?php
                                            $stateCssClass = '';
                                            switch(strtolower($detail['state'])){
                                                case 'active':
                                                    $stateCssClass = 'green';
                                                    break;
                                                case 'suspended':
                                                    $stateCssClass = 'orange';
                                                    break;
                                                case 'cancelled':
                                                    $stateCssClass = 'red';
                                                    break;
                                                case 'pending':
                                                    $stateCssClass = 'orange';
                                                    break;
                                            }
                                            $detail['state'] = Inflector::humanize($detail['state']);
                                        ?>
                                        <?php echo __("Start Time"); ?>: <?php echo $detail['startTime']; ?> <span class="<?php echo $stateCssClass; ?>">(<?php echo __($detail['state']); ?>)</span>
                                    </p>
                                </blockquote>
                                <dl id="dt-list-1" class="dl-horizontal">
                                    <dt><?php echo __("Active"); ?></dt>
                                    <dd><?php echo empty($detail['active']) ? '<span class="red">' .__("No") .'</span>' : '<span class="green">' .__("Yes") .'</span>'; ?></dd>
                                    <dt><?php echo __("Final Payment Date"); ?></dt>
                                    <dd><?php echo $detail['finalPaymentDate']; ?></dd>
                                    <dt><?php echo __("Outstanding Balance"); ?></dt>
                                    <dd><?php echo $detail['outstandingBalance']; ?></dd>
                                    <dt><?php echo __("Last Payment Date"); ?></dt>
                                    <dd><?php echo $detail['lastPaymentDate']; ?></dd>
                                    <dt><?php echo __("Last Payment Amount"); ?></dt>
                                    <dd><?php echo $detail['lastPaymentAmount']; ?></dd>
                                    <dt><?php echo __("Next Payment Date"); ?></dt>
                                    <dd><?php echo $detail['nextPaymentDate']; ?></dd>
                                    <dt><?php echo __("Failed Payment Count"); ?></dt>
                                    <dd><?php echo $detail['failedPaymentCount']; ?></dd>
                                    <dt><?php echo __("Cycles Completed"); ?></dt>
                                    <dd><?php echo $detail['cyclesCompleted']; ?></dd>
                                    <dt><?php echo __("Cycles Remaining"); ?></dt>
                                    <dd><?php echo $detail['cyclesRemaining']; ?></dd>
                                </dl>
                                <div class="space-12 left clear width-100">&nbsp;</div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <script>
                    if($('img.recurring-payment-content-loading', window.parent.document).length){
                        $('img.recurring-payment-content-loading', window.parent.document).remove();
                    }
                </script>
            <?php endforeach; ?>

        </div>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    
    $inlineJS = <<<EOF
    
        $('.collapse').collapse({
            parent: '#accordion',
            toggle: false
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>