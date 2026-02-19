<?php echo $this->Form->create('Payment.PaymentInvoice'); ?>
    <?php
        $isAdd = $this->request->params['action'] == 'admin_add';
        $this->request->data = (isset($invoice) && !empty($invoice)) ? $invoice : $this->request->data;
        echo $this->Form->input('number', array('type' => 'hidden', 'value' => (empty($this->request->data["PaymentInvoice"]["number"]) ? '' : $this->request->data["PaymentInvoice"]["number"])));
        if(!$isAdd){
            echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->request->data["PaymentInvoice"]["user_id"]));
            echo $this->Form->input('created_by', array('type' => 'hidden', 'value' => $this->request->data["PaymentInvoice"]["created_by"]));
        }
    ?>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('user_id', array(
                    'label'         => array('text' => __('Payor') .'&nbsp;<span class="mandatory">*</span>'),
                    'options'       => $userList,
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 1,
                    'disabled'      => ($isAdd ? false : true)
                ));
            ?>
        </div>
    </div>
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-xs-12">
            <?php
                if(empty($this->request->data['PaymentInvoice']['number']) && $isAdd){
                    echo $this->Form->input('button', array(
                        'label'         => false,
                        'value'         => __('Generate Invoice Number'),
                        'class'         => 'required col-xs-12 col-sm-12 btn btn-warning ' .((!empty($this->request->data['PaymentInvoice']['number'])) ? 'hide' : ''),
                        'div'           => false,
                    ));
                }
                echo $this->Form->input('number', array(
                    'label'         => array('text' => __('Invoice Number') .'&nbsp;<span class="mandatory">*</span>', 'class' => ((empty($this->request->data['PaymentInvoice']['number']) && $isAdd) ? 'hide' : '')),
                    'class'         => 'required col-xs-12 col-sm-12 ' .((empty($this->request->data['PaymentInvoice']['number']) && $isAdd) ? 'hide' : ''),
                    'div'           => false,
                    'disabled'      => true
                ));
            ?>
        </div>
    </div>
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('amount', array(
                    'label'         => array('text' => __('Amount') .'&nbsp;<span class="mandatory">*</span>'),
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 2
                ));
            ?>
        </div>
    </div>
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('paid_amount', array(
                    'label'         => array('text' => __('Paid Amount')),
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'disabled'      => $isAdd,
                    'tabindex'      => 3
                ));
            ?>
        </div>
    </div>
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-xs-12 date-field-wrapper">
            <?php
                echo $this->Form->input('due_date', array(
                    'label'         => array('text'  => __('Due Date') .'&nbsp;<span class="mandatory">*</span>'),
                    'type'          => 'date',
                    'autocomplete'  => 'off',
                    'dateFormat'    => 'DMY',
                    'minYear' => date('Y'),
                    'maxYear' => date('Y') + 5,
                    'separator'     => '<span>-</span>',
                    'class'         => 'required col-xs-12 col-sm-12 no-selectmenu',
                    'div'           => false,
                    'style'         => 'width:8em;',
                    'tabindex'      => 4
                ));
            ?>
        </div>
    </div>
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('content', array(
                    'type'          => 'textarea',
                    'label'         => array('text' => __('Invoice Content') .'&nbsp;<span class="mandatory">*</span>'),
                    'class'         => 'required col-xs-12 col-sm-12',
                    'style'         => 'height:220px;',
                    'rows'          => false,
                    'cols'          => false,
                    'tabindex'      => 5
                ));
            ?>
        </div>
    </div>
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php
    $minDueDateYear = date('Y');
    $generateInvoiceNumberTitleTxt = __("Generate Invoice Number");
    $submitBtnTxt = __('Submit');
    $closeBtnTxt = __('Cancel');
    
    $inlineJS = <<<EOF
    
        $('form[id^="PaymentInvoice"][id$="Form"]').validate({
            rules: {
                    "data[PaymentInvoice][amount]": {
                        required: true,
                        number: true
                    },
                    "data[PaymentInvoice][user_id]": {
                        required: true,
                        number: true
                    },
                    "data[PaymentInvoice][due_date][month]": {
                        required: true,
                        number: true,
                        max: 12
                    },
                    "data[PaymentInvoice][due_date][day]": {
                        required: true,
                        number: true,
                        max: 31
                    },
                    "data[PaymentInvoice][due_date][year]": {
                        required: true,
                        number: true,
                        min: {$minDueDateYear}
                    },
                    "data[PaymentInvoice][content]": {
                        required: true
                    },
                    "data[PaymentInvoice][number]": {
                        required: true
                    }
            }
        });
        
        $(document).ready(function(){
            loadTinymce("PaymentInvoiceContent");
        });
        
        $('#PaymentInvoiceButton').on('click', function(){
            bootbox.dialog({
                message: '<iframe src="/admin/payment/payment_invoices/generateInvoiceNumber?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                title: '{$generateInvoiceNumberTitleTxt}',
                buttons: {
                    'Submit' : {
                        'label' : '{$submitBtnTxt}',
                        'className' : 'btn-sm btn-success submit-iframe-form-btn',
                        'otherBtnAttrs': 'data-reload=false',
                        'callback' : function(event){
                            submitIframeForm(event, true);
                            return false;
                        }
                    },
                    'Cancel' : {
                        'label' : '{$closeBtnTxt}',
                        'className' : 'btn-sm btn-sm',
                        'callback': function(){
                            
                        }
                    }
                },
                onEscape: function(){
                    
                }
            });
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>