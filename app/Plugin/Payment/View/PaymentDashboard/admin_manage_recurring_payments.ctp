<?php if(stristr($userGroupName, Configure::read('System.client.group.name')) === FALSE): ?> 

    <iframe src="/admin/payment/payment_recurring_agreements/index?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>

<?php else: ?>
        
    <?php
        $paypal = __("PayPal");
    ?>
        
    <div class="row">
        <div class="col-xs-12">
            <div class="tabbable">
                <ul class="nav nav-tabs" id="recurring-agreement-tab">
                    <li class="active">
                        <a data-toggle="tab" href="#<?php echo $paypal; ?>" aria-expanded="true">
                            <i class="blue ace-icon fa fa-paypal bigger-120"></i>
                            <?php echo $paypal; ?>
                        </a>
                    </li>
                </ul>
    
                <div class="tab-content">
                    <div id="<?php echo $paypal; ?>" class="tab-pane fade active in">
                        <img class="recurring-payment-content-loading" src="/css/admin/images/loading.gif" alt="loading" />
                        <iframe src="/admin/payment/payment_pay_pal_gateway/getRecurringAgreementDetails/<?php echo $userId; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- page specific plugin scripts -->
    <?php
        
        $inlineJS = <<<EOF
        
            $('#recurring-agreement-tab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                
            });
EOF;
        echo $this->element('page/admin/load_inline_js', array(
            'inlineJS' => $inlineJS
        )); 
    ?>
        
<?php endif; ?>