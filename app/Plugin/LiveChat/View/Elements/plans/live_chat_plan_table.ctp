<style>
    .pricing-box .price {height: auto;}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <?php
                $displayFields = array(
                    'ITEM_NAME'                                 => array('LiveChatPlan.name'),
                    '%'                                         => array('LiveChatPlan.description'),
                    '$% <small>monthly<div class="space-2">&nbsp;</div>per operator</small><div class="space-4">&nbsp;</div>' => array('LiveChatPlan.price', 'isFinalPrice' => TRUE),
                );
                if(!empty($plan)){
                    $currentPlanId = $plan['LiveChatUser']['live_chat_plan_id'];
                }
                $actions = array(
                    '<i class="ace-icon fa fa-shopping-cart bigger-110"></i><span>Switch to this plan</span>' => array('/admin/live_chat/live_chat_plans/alter/', 'LiveChatPlan.id', '', array('class' => 'btn btn-block', 'escape' => false, 'data-title' => __('Pay Live Chat Plan Switch Fee'), 'data-closest-btn' => __('Close')), @$currentPlanId)
                );
                $substitutions = array();
                
                $headerColorCssClass = array(
                    'widget-color-dark',
                    'widget-color-orange',
                    'widget-color-blue',
                    'widget-color-green'
                );
                
                $footerColorCssClass = array(
                    'btn-inverse',
                    'btn-warning',
                    'btn-primary',
                    'btn-success'
                );
                
                echo $this->PricingTable->createTable(
                    'LiveChatPlan',
                    $plans,
                    $displayFields,
                    $actions,
                    __('No live chat plans found'),
                    $substitutions,
                    $headerColorCssClass,
                    $footerColorCssClass
                );
            ?>
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->