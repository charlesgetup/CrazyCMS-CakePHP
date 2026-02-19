<div class="row col-xs-12">
    <?php
        echo $this->Form->create(false, array(
            'url' => array(
                'controller' => 'payment_pay_pal_gateway', 
                'action' => 'recurringPayment', 
                'plugin' => 'payment', 
                'prefix' => 'admin',
                $pendingInvoiceId,
                $isTempInvoice ? 1 : 0
            )
        ));
        
        $paymentCycle = array(
            Configure::read('Payment.pay.cycle.monthly'),
            Configure::read('Payment.pay.cycle.quarterly'),
            Configure::read('Payment.pay.cycle.half_year'),
            Configure::read('Payment.pay.cycle.annually'),
        );
        
        $paymentType = Configure::read('Config.type.payment');
        $paymentCycleOption = array();
        foreach($paymentCycle as $v){
            $k          = $v;
            $vFlag      = Inflector::camelize(strtolower($v));
            $v          = Inflector::humanize(strtolower($v));
            $discount   = $this->App->_getSystemDefaultConfigSetting($vFlag .'Discount', $paymentType);
            if(!empty($discount)){
                $v     .= ' - ' .($discount * 100) .'% ' .__('discount');
            }
            $paymentCycleOption[$k] = $v;
        }

        echo $this->Form->input('payment_cycle', array(
            'label'         => array('text' => __('Select payment cycle') .'&nbsp;<span class="mandatory">*</span>'),
            'options'       => $paymentCycleOption,
            'empty'         => false,
            'class'         => 'required col-xs-12 col-sm-12',
            'div'           => false,
            'tabindex'      => 1
        ));
        
        echo '<div class="space-12 left clear width-100">&nbsp;</div>';

        $paypalPaymentMethods = Configure::read('Payment.method.paypal');

        echo $this->Form->input('payment_method', array(
            'label'         => array('text' => __('Select payment method') .'&nbsp;<span class="mandatory">*</span>'),
            'options'       => array(
                                    $paypalPaymentMethods['paypal'] => 'PayPal', 
                                    //$paypalPaymentMethods['credit_card'] => __('Credit card'), 
                                    //$paypalPaymentMethods['direct_debit'] => __('Bank Direct Debit')
                               ),
            'empty'         => false,
            'class'         => 'required col-xs-12 col-sm-12',
            'div'           => false,
            'tabindex'      => 2
        ));
        
        echo '<div class="space-12 left clear width-100">&nbsp;</div>';
    ?>
    
    <div class="row col-xs-12 hide credit-card">
        <h5><?php echo __("Credit Card Information"); ?>:</h5>
        <?php
            echo $this->Form->input('cc_type', array(
                'label'         => array('text' => __('Card Type') .'&nbsp;<span class="mandatory">*</span>'),
                'options'       => Configure::read('Payment.method.paypal.allowed_cc_type'),
                'class'         => 'required col-xs-12',
                'div'           => array('class' => 'input text col-sm-5 no-padding-left'),
                'tabindex'      => 3
            ));
            
            echo $this->Form->input('cc_first_name', array(
                'label'         => array('text' => __('Card Holder First Name') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'text',
                'class'         => 'required col-xs-12',
                'div'           => array('class' => 'input text col-sm-5 no-padding-left clear'),
                'tabindex'      => 4
            ));
            
            echo $this->Form->input('cc_last_name', array(
                'label'         => array('text' => __('Card Holder Last Name') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'text',
                'class'         => 'required col-xs-12',
                'div'           => array('class' => 'input text col-sm-5 no-padding-left'),
                'tabindex'      => 5
            ));
            
            echo $this->Form->input('cc_number', array(
                'label'         => array('text' => __('Card Number') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'text',
                'class'         => 'required col-xs-12',
                'div'           => array('class' => 'input text col-sm-5 no-padding-left clear'),
                'tabindex'      => 6
            ));
            
            echo $this->Form->input('cc_cvv', array(
                'label'         => array('text' => __('Card CVV') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'password',
                'class'         => 'required col-xs-12',
                'div'           => array('class' => 'input text col-sm-5 no-padding-left'),
                'tabindex'      => 7
            ));
            
            $expMonth = array();
            for($i = 1; $i <= 31; $i++){
                $expMonth[$i] = $i;
            }
            echo $this->Form->input('cc_exp_month', array(
                'label'         => array('text' => __('Card Expiry Month') .'&nbsp;<span class="mandatory">*</span>'),
                'options'       => $expMonth,
                'class'         => 'required col-xs-12',
                'div'           => array('class' => 'input text col-sm-5 no-padding-left clear'),
                'tabindex'      => 8
            ));
            
            $expYear = array();
            for($i = intval(date('Y')); $i <= intval(date('Y', strtotime("+10 years"))); $i++){
                $expYear[$i] = $i;
            }
            echo $this->Form->input('cc_exp_year', array(
                'label'         => array('text' => __('Card Expiry Year') .'&nbsp;<span class="mandatory">*</span>'),
                'options'       => $expYear,
                'class'         => 'required col-xs-12',
                'div'           => array('class' => 'input text col-sm-5 no-padding-left'),
                'tabindex'      => 9
            ));
        ?>
    </div>
    
    <div class="row col-xs-12 center">
        <br />
        <?php 
            echo $this->Html->link(
                $this->Html->image("https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif", array("alt" => "PayPal Buy Now")),
                "#",
                array('escape' => false, 'class' => 'paypal-subscribe')
            );
            echo $this->Html->image("/img/admin/loading.gif", array("alt" => "Loading", "class" => array("loading hide")));
        ?>
    </div>
    
    <?php
        echo $this->Form->end();
    ?>
</div>

<div class="row col-xs-12 notice-area">
    <h3><?php echo __("Notice"); ?>:</h3>
    <ul>
        <li>
            <?php echo __('Discount will automatically apply to the original price.'); ?>
        </li>
    </ul>
</div>

<!-- page specific plugin scripts -->
<?php
    
    $errMsg = __('PayPal recurring payment agreement cannot be created. Please report this to ' .$companyName .'.') .'<br />' .__('Sorry about the inconvenience.');
    
    $inlineJS = <<<EOF
        
        $( "#payment_method" ).on( "selectmenuchange", function( event, ui ) {
            if(this.value == "{$paypalPaymentMethods['credit_card']}"){
                $('div.credit-card').removeClass('hide');
            }else{
                if(!$('div.credit-card').hasClass('hide')){
                    $('div.credit-card').addClass('hide');
                }
            }
        } );
   
        $('a.paypal-subscribe').click(function(){
            
            $('img.loading').removeClass("invisible");
            $('img.loading').addClass("visible");
            
            var that = this;
            
            $.ajax({
            
                async: false,
                cache: false,
                headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                url:   $(that).closest('form').attr('action'),
                type:  "POST",
                data:  $(that).closest('form').serialize()
                
            }).done(function( data ) {

                if(validURL(data)){
                    window.location.href = data;
                    return false;
                }else{
                    messageBox({"status": ERROR, "message": "{$errMsg}"});
                }
            
            }).fail(function( jqxhr, settings, exception ) {
                ajaxErrorHandler( jqxhr, settings, exception );
            }).always(function() {
                $('img.loading').removeClass("visible");
                $('img.loading').addClass("invisible");
            });
            
        });
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>