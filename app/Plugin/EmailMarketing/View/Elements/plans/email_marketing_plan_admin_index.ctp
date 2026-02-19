<?php
    $displayFields = array(
        'EmailMarketingPlan.id'                 => array('ColumnName' => __('ID'),                    'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'EmailMarketingPlan.name'               => array('ColumnName' => __('Name'),                  'Sortable' => true, 'Searchable' => true),
        'EmailMarketingPlan.email_limit'        => array('ColumnName' => __('Email Limit'),           'Sortable' => true, 'Searchable' => true),
        'EmailMarketingPlan.subscriber_limit'   => array('ColumnName' => __('Subscriber Limit'),      'Sortable' => true, 'Searchable' => true),
        'EmailMarketingPlan.unit_price'         => array('ColumnName' => __('Price per Email'),       'Sortable' => true, 'Searchable' => true),
        'EmailMarketingPlan.total_price'        => array('ColumnName' => __('Total Price per month'), 'Sortable' => true, 'Searchable' => true),
        'EmailMarketingPlan.total_users_amount' => array('ColumnName' => __('Total Users Amount'),    'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'View'      => array('/admin/email_marketing/email_marketing_plans/view/', 'EmailMarketingPlan.id', null, array('class' => 'blue popup-view')),
        'Edit'      => array('/admin/email_marketing/email_marketing_plans/edit/', 'EmailMarketingPlan.id', null, array('class' => 'green popup-edit')),
        'Delete'    => array('/admin/email_marketing/email_marketing_plans/delete/', 'EmailMarketingPlan.id', null, array('class' => 'red popup-delete')),
    );
    
    echo $this->JqueryDataTable->createTable(
        'EmailMarketingPlan',
        $displayFields,
        "/admin/email_marketing/email_marketing_plans/index.json",
        $actions,
        __('No email marketing plans found'),
        $defaultSortDir,
        'email_marketing'
    );
?>

<br />
<br />
<hr />
<section>
    <h3><?php echo __('Private Plan Calculator'); ?></h3>
    <div class="space-12">&nbsp;</div>
    <?php echo $this->Form->create('EmailMarketing.EmailMarketingPlan', array('action' => 'add')); ?>
        <?php
            $planDes = <<<DES
                <ul>
                    <li>Send up to %email-limit% emails per month</li>
                    <li>Store up to %subscriber-limit% subscribers</li>
                    <li>Blacklist up to %subscriber-limit% subscribers</li>
                    <li>Live chat and ticket support</li>
                    <li>Scheduled sending capability</li>
                    <li>Email template editor</li>
                    <li>Bulk responsive templates on sale</li>
                    <li>Email campaign, mailing list management</li>
                    <li>Blacklist subscribers capability</li> 
                    <li>Real-time analytics and statistics summary</li>
                    <li>Delivered, click, open, unsubscribe tracking</li>
                    <li>Bounce rate reporting</li>
                    <li class="optional-extra-attr">Bulk subscribers data (including email, name and %extra-attr-limit% extra attributes) upload</li>
                    <li class="optional-sender optional-extra-attr">Device, browser, geolocation tracking</li>
                    <li class="optional-sender">Custom branding for up to %sender-limit% domain(s)</li>
                    <li class="optional-spend-over-500">Email campaign, mailing list and subscribers management API</li>
                    <li>Cost per extra email is $%unit-price%</li>
                </ul>        
DES;
            $planDes = preg_replace('/\r|\n/m', '', $planDes);
            echo $this->Form->input('name', array(
                'type'          => 'hidden',
                'class'         => 'required',
                'value'         => __('Private Email Marketing Plan')
            ));
            echo $this->Form->input('description', array(
                'type'          => 'hidden',
                'class'         => 'required',
                'value'         => __($planDes)
            ));
            echo $this->Form->input('unit_price', array(
                'type'          => 'hidden',
                'class'         => 'required',
                'value'         => 0.0005
            ));
        ?>
        <h4></strong><?php echo __('Step 1:'); ?></strong> <?php echo __('Select client'); ?></h4>
        <div class="space-8">&nbsp;</div>
        <div class="row">
            <div class="col-sm-6">
                <?php
                    $options[] = __('Search by client name / email'); // Use ajax to dynamically load options. Add this option is only for security purpose. Only multiple drop down and drop down with options can be included in security hash.
                    echo $this->Form->select('private_email_user_id', $options, array(
                        'escape'            => false,
                        'class'             => 'chosen-select form-control tag-input-style required',
                        'data-placeholder'  => __('Choose a client...')
                    ));
                ?>
            </div>
        </div>
        <div class="space-12">&nbsp;</div>
        <h4></strong><?php echo __('Step 2:'); ?></strong> <?php echo __('Set plan details'); ?></h4>
        <dl class="dl-horizontal">
            <div class="space-12">&nbsp;</div>
            <dt><?php echo __('Subscriber Limit'); ?></dt>
            <dd>
                <?php
                    echo $this->Form->input('subscriber_limit', array(
                        'label'         => false,
                        'type'          => 'text',
                        'autocomplete'  => 'off',
                        'class'         => 'required',
                        'div'           => false,
                        'tabindex'      => 1
                    ));
                ?>
            </dd>
            <div class="space-8">&nbsp;</div>
            <dt><?php echo __('Email Limit (per month)'); ?></dt>
            <dd>
                <?php
                    echo $this->Form->input('email_limit', array(
                        'label'         => false,
                        'type'          => 'text',
                        'autocomplete'  => 'off',
                        'class'         => 'required',
                        'div'           => false,
                        'tabindex'      => 2
                    ));
                ?>
            </dd>
            <div class="space-8">&nbsp;</div>
            <dt><?php echo __('Extra Attribute Limit'); ?></dt>
            <dd>
                <?php
                    echo $this->Form->input('extra_attr_limit', array(
                        'label'         => false,
                        'type'          => 'text',
                        'autocomplete'  => 'off',
                        'class'         => 'required',
                        'div'           => false,
                        'tabindex'      => 3
                    ));
                ?>
            </dd>
            <div class="space-8">&nbsp;</div>
            <dt><?php echo __('Custom Domain Limit'); ?></dt>
            <dd>
                <?php
                    echo $this->Form->input('sender_limit', array(
                        'label'         => false,
                        'type'          => 'text',
                        'autocomplete'  => 'off',
                        'class'         => 'required',
                        'div'           => false,
                        'tabindex'      => 4
                    ));
                ?>
            </dd>
        </dl>
        <div class="space-8">&nbsp;</div>
        <h4></strong><?php echo __('Step 3:'); ?></strong> <?php echo __('Add / Update private plan'); ?></h4>
        <div class="space-8">&nbsp;</div>
        <div class="row">
            <div class="col-sm-6">
                <?php 
                    echo __('Quote price:') .'&nbsp;&nbsp;&nbsp;&nbsp;$'; 
                    // Hidden field value cannot be changed, so have to use a form field to hold total price
                    echo $this->Form->input('total_price', array(
                        'type'          => 'text',
                        'class'         => 'required no-input no-validation-symbol',
                        'label'         => false,
                        'div'           => false,
                        'size'          => 1,
                        'value'         => 0
                    ));    
                    echo ' / ' .__('month');
                ?>
            </div>
        </div>
        <div class="space-8">&nbsp;</div>
        <div class="row">
            <div class="col-sm-6">
                <?php
                    echo $this->Form->button('Add / Update private plan', array(
                        'type'      => 'submit',
                        'class'     => 'btn btn-sm btn-success',
                        'escape'    => true
                    ));
                ?>
            </div>
        </div>
    <?php echo $this->Form->end(); ?>
</section>
    
<!-- page specific plugin scripts -->
<?php 

    $inlineJS = <<<EOF

        var calculateQuotePrice = function(){
            var subscriberPrice = parseInt($('#EmailMarketingPlanSubscriberLimit').val());
            if(subscriberPrice > 0){
                subscriberPrice = {$subscriberUnitPrice} * subscriberPrice;
            }else{
                subscriberPrice = 0;
            }
            var emailPrice = parseInt($('#EmailMarketingPlanEmailLimit').val());
            if(emailPrice > 0){
                emailPrice = {$emailUnitPrice} * emailPrice;
            }else{
                emailPrice = 0;
            }
            var extraAttrPrice = parseInt($('#EmailMarketingPlanExtraAttrLimit').val());
            if(extraAttrPrice > 0){
                extraAttrPrice = {$extraAttributeUnitPrice} * extraAttrPrice;
            }else{
                extraAttrPrice = 0;
            }
            var senderPrice = parseInt($('#EmailMarketingPlanSenderLimit').val());
            if(senderPrice > 0){
                senderPrice = {$emailSenderUnitPrice} * senderPrice;
            }else{
                senderPrice = 0;
            }
            var totalPrice = 0;
            if(subscriberPrice && emailPrice && extraAttrPrice && senderPrice){
                totalPrice = (subscriberPrice + emailPrice + extraAttrPrice + senderPrice).toFixed(2);
                var inputLen = totalPrice.toString().length;
                $('#EmailMarketingPlanTotalPrice').attr('size', inputLen).val(totalPrice);
            }
        };

        $('#EmailMarketingPlanSubscriberLimit, #EmailMarketingPlanEmailLimit, #EmailMarketingPlanExtraAttrLimit, #EmailMarketingPlanSenderLimit').each(function(){
            var that = this;
            $(this).ace_spinner({
                value:0,
                min:0,
                max:99999999999,
                step: ($.inArray(that.id, ['EmailMarketingPlanSubscriberLimit', 'EmailMarketingPlanEmailLimit']) >= 0 ? 10000 : 1), 
                on_sides: true, 
                icon_up:'ace-icon fa fa-plus bigger-110', 
                icon_down:'ace-icon fa fa-minus bigger-110', 
                btn_up_class:'btn-success' , 
                btn_down_class:'btn-danger'
            }).closest('.ace-spinner')
            .on('changed.fu.spinbox', function(){
                calculateQuotePrice();
            });
        });

        $('.chosen-select').ajaxChosen({
            type: 'POST',
            url: '/admin/email_marketing/email_marketing_users/getClients.json',
            dataType: 'json',
            headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
            beforeStart: function(event){
            
            }
        }, function(data){
        
            /* Manually update option data here */
            return data;
            
        },{
            allow_single_deselect:true
        });

        $(window).off('resize.chosen').on('resize.chosen', function() {
            $('.chosen-select').each(function() {
                 var that = $(this);
                 that.next().css({'width': that.parent().width()});
                 that.next().next('span.ui-selectmenu-button').css('display', 'none');
            });
        }).trigger('resize.chosen');

        $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
            if(event_name != 'sidebar_collapsed'){ return; }
            $('.chosen-select').each(function() {
                 var that = $(this);
                 that.next().css({'width': that.parent().width()});
            });
        });
        
        $('#EmailMarketingPlanSubscriberLimit, #EmailMarketingPlanEmailLimit, #EmailMarketingPlanExtraAttrLimit, #EmailMarketingPlanSenderLimit').on('change', function(){
            calculateQuotePrice();
        });

        $('form[id^="EmailMarketingPlan"][id$="Form"]').validate({
            rules: {
                    "data[EmailMarketingPlan][private_email_user]": {
                        required: true
                    },
                    "data[EmailMarketingPlan][name]": {
                        required: true,
                        maxlength: 100
                    },
                    "data[EmailMarketingPlan][description]": {
                        required: true
                    },
                    "data[EmailMarketingPlan][email_limit]": {
                        required: true,
                        integer: true,
                        range: [20000, 99999999999]
                    },
                    "data[EmailMarketingPlan][extra_attr_limit]": {
                        required: true,
                        integer: true,
                        range: [1, 99999999999]
                    },
                    "data[EmailMarketingPlan][sender_limit]": {
                        required: true,
                        integer: true,
                        range: [1, 99999999999]
                    },
                    "data[EmailMarketingPlan][subscriber_limit]": {
                        required: true,
                        integer: true,
                        range: [5000, 99999999999]
                    },
                    "data[EmailMarketingPlan][unit_price]": {
                        required: true,
                        number: true,
                        max: 9999999999.99
                    },
                    "data[EmailMarketingPlan][total_price]": {
                        required: true,
                        number: true,
                        max: 9999999999.99
                    }
            }
        });
  
EOF;

    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>