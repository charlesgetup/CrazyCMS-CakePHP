<?php if(!empty($plans)): ?>
    <?php
        echo $this->element('plans/email_marketing_plan_table', array(
            'plans' => $plans,
            'plan'  => $plan
        )); 
    ?>
<?php endif; ?>

<hr />
<section>
    <h3><?php echo __('Current Plan Settings'); ?></h3>
    <dl class="dl-horizontal">
        <div class="space-12">&nbsp;</div>
        <dt><?php echo __('Payment Cycle'); ?></dt>
        <dd><?php echo ($plan['EmailMarketingUser']['payment_cycle'] == Configure::read('Payment.pay.cycle.manual')) ? __('Prepay') : Inflector::humanize(Inflector::underscore(strtolower($plan['EmailMarketingUser']['payment_cycle']))); ?></dd>
        <div class="space-8">&nbsp;</div>
        <dt><?php echo __('Free Email Amount'); ?></dt>
        <dd><?php echo empty($plan['EmailMarketingUser']['free_email']) ? 0 : $plan['EmailMarketingUser']['free_email']; ?></dd>
        <div class="space-8">&nbsp;</div>
        <dt><?php echo __('Total Sent Email Amount'); ?></dt>
        <dd><?php echo $plan['EmailMarketingUser']['total_sent_email_amount']; ?></dd>
        <div class="space-8">&nbsp;</div>
        <dt><?php echo __('Prepaid Amount'); ?></dt>
        <dd class="input-group">
            <?php
                echo $this->Form->input('prepaid_amount', array(
                    'label'         => false,
                    'value'         => $plan['EmailMarketingUser']['prepaid_amount'],
                    'disabled'      => true,
                    'div'           => false,
                    'class'         => 'input-small'
                ));
            ?>
            <a class="btn btn-sm btn-primary deposit" href="/admin/email_marketing/email_marketing_users/addPrepaidFunds/<?php echo $plan['EmailMarketingUser']['id']; ?>" data-title='<?php echo __('Add prepaid funds'); ?>' data-closest-btn='<?php echo __('Close'); ?>'><?php echo __('Deposit'); ?></a>
        </dd>
        <div class="space-8">&nbsp;</div>
        <?php if(!empty($plan['EmailMarketingUser']['last_pay_date'])): ?>
            <dt><?php echo __('Last Payment Date'); ?></dt>
            <dd><?php echo $this->Time->i18nFormat($plan['EmailMarketingUser']['last_pay_date'], '%x %X'); ?></dd>
            <div class="space-8">&nbsp;</div>
        <?php endif; ?>
        <?php if(!empty($plan['EmailMarketingUser']['next_pay_date'])): ?>
            <dt><?php echo __('Next Payment Date'); ?></dt>
            <dd><?php echo $this->Time->i18nFormat($plan['EmailMarketingUser']['next_pay_date'], '%x %X'); ?></dd>
            <div class="space-8">&nbsp;</div>
        <?php endif; ?>
    </dl>
</section>

<!-- page specific plugin scripts -->
<?php 
    $closeBtn       = __("Close");
    $depositBtn     = __("Deposit");
    $iframeUrl      = DS .'admin' .DS .'email_marketing' .DS .'email_marketing_users' .DS .'updateEmailWarningLimit' .DS .$plan['EmailMarketingUser']['id'];
    
    $prepaidPlanId  = Configure::read('EmailMarketing.plan.prepaid');
    
    $inlineJS = <<<EOF
        
        /* Only show payment popup for one time payment */ 
        $('a[href="/admin/email_marketing/email_marketing_plans/alter/{$prepaidPlanId}"]').on("click", function(event){
            showPaymentBoxIniFrame(event, null, '{$csrfCookieName}');
        });
       
        $('a.btn.deposit').on('click', function(event){
            var link            = $(this);
            var iframeUrl       = link.attr("href");
            
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
            
            bootbox.dialog({
                message: '<iframe src="'+iframeUrl+'/?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                title: link.attr("data-title"),
                onEscape: false,
                buttons: {
                    "Deposit" : {
                        "label" : "{$depositBtn}",
                        "className" : "btn-sm btn-success submit-iframe-form-btn",
                        "callback" : function(event){
                            submitIframeForm(event);
                            return false;
                        }
                    },
                    "Close" : {
                        "label" : link.attr("data-closest-btn"),
                        "className" : "btn-sm btn-sm",
                        "callback" : function(event){
                            window.location.reload(); /* Reload window to show flash message */
                        }
                    }
                }
            });
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>