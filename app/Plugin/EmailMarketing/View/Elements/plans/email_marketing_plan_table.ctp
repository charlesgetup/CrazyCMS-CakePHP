<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <?php
                $displayFields = array(
                    'ITEM_NAME'                                                                 => array('EmailMarketingPlan.name'),
                    '%'                                                                         => array('EmailMarketingPlan.description'),
                    '$% <small>/month</small>'                                                  => array('EmailMarketingPlan.total_price', 'isFinalPrice' => TRUE),
                );
                if(!empty($plan)){
                    $currentPlanId = $plan['EmailMarketingUser']['email_marketing_plan_id'];
                }
                $actions = array(
                    '<i class="ace-icon fa fa-shopping-cart bigger-110"></i><span>Switch to this plan</span>' => array('/admin/email_marketing/email_marketing_plans/alter/', 'EmailMarketingPlan.id', '', array('class' => 'btn btn-block', 'escape' => false, 'data-title' => __('Pay Email Marketing Plan Switch Fee'), 'data-closest-btn' => __('Close')), @$currentPlanId)
                );
                $substitutions = array(
                    '$% <small>/month</small>' => array('0.00000' => __('No monthly fee')),
                    '%'                        => array(
                                                      '%unit-price%'        => 'unit_price',
                                                      '%email-limit%'       => 'email_limit',
                                                      '%subscriber-limit%'  => 'subscriber_limit',
                                                      '%sender-limit%'      => 'sender_limit',
                                                      '%extra-attr-limit%'  => 'extra_attr_limit'
                                                  )
                );
                
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
                    'EmailMarketingPlan',
                    $plans,
                    $displayFields,
                    $actions,
                    __('No email marketing plans found'),
                    $substitutions,
                    $headerColorCssClass,
                    $footerColorCssClass
                );
            ?>
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->