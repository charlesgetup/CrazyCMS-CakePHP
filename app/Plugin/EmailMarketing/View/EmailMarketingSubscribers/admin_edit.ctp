<?php echo $this->Form->create('EmailMarketing.EmailMarketingSubscriber'); ?>
    <?php echo $this->Form->hidden('id'); ?>
    
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('email_marketing_list_id', array(
                    'label'         => array('text' => __('Mailing List') .'&nbsp;<span class="mandatory">*</span>'),
                    'options'       => array($list['EmailMarketingMailingList']['id'] => $list['EmailMarketingMailingList']['name']),
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 1
                ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('first_name', array(
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 2
                ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('last_name', array(
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 3
                ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('email', array(
                    'label'         => array('text' => __('Email') .'&nbsp;<span class="mandatory">*</span>'),
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 4
                ));
            ?>
        </div>
    </div>
    
    <?php if($this->request->params['action'] == 'admin_add'): ?>
        <?php echo $this->Form->input('created' , array('type' => 'hidden', 'value' => date('Y-m-d H:i:s', strtotime('now')))); ?>
    <?php else: ?>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('excluded', array(
                        'label'         => array('text' => __('Excluded From This List') .'&nbsp;<span class="mandatory">*</span>'),
                        'options'       => array("0" => 'No', "1" => 'Yes'),
                        'default'       => (isset($this->request->data['EmailMarketingMailingList']['excluded']) ? $this->request->data['EmailMarketingMailingList']['excluded'] : "0"),
                        'autocomplete'  => 'off',
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 5
                    ));
                    echo $this->Form->input('modified' , array('type' => 'hidden', 'value' => date('Y-m-d H:i:s', strtotime('now'))));
                ?>
            </div>
        </div>
        <?php if(!empty($this->request->data["EmailMarketingSubscriber"]["extra_attr"])): ?>
            <?php 
                if(is_string($this->request->data["EmailMarketingSubscriber"]["extra_attr"])){
                    $this->request->data["EmailMarketingSubscriber"]["extra_attr"] = unserialize($this->request->data["EmailMarketingSubscriber"]["extra_attr"]);
                }
            ?>
            <?php if(!empty($this->request->data["EmailMarketingSubscriber"]["extra_attr"]) && is_array($this->request->data["EmailMarketingSubscriber"]["extra_attr"])): ?>
                <?php $tabIndex = 6; ?>
                <?php foreach($this->request->data["EmailMarketingSubscriber"]["extra_attr"] as $attr => $value): ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php
                                echo '<label for="EmailMarketingSubscriberExtraAttr[' .$attr .']" style="float: left;">' .$attr .'</label>';
                                echo '<input name="data[EmailMarketingSubscriber][extra_attr][' .$attr .']" value="' .$value .'" class="col-xs-12 col-sm-12" tabindex="' .($tabIndex++) .'" type="text" id="EmailMarketingSubscriberExtraAttr[' .$attr .']">';
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
    
<?php echo $this->Form->end(); ?>

<?php if($this->request->params['action'] == 'admin_add'): ?>
<div class="notice-area">
    <h3><?php echo __("Notice"); ?>:</h3>
    <ul>
        <li>
            <?php echo __('The extra attributes can only be added via upload. And after upload, they can be updated in edit subscriber page.'); ?>
        </li>
    </ul>
</div>
<?php endif; ?>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        $('form[id^="EmailMarketingSubscriber"][id$="Form"]').validate({
            rules: {
                    "data[EmailMarketingSubscriber][email_marketing_list_id]": {
                        required: true
                    },
                    "data[EmailMarketingSubscriber][email]": {
                        required: true,
                        email: true
                    }
            }
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>