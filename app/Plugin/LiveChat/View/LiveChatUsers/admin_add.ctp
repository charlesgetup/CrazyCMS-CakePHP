<?php echo $this->Form->create('LiveChat.LiveChatUser'); ?>
    <?php if(empty($userId)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('user_id', array(
                        'label'         => array('text' => __('Client') .'&nbsp;<span class="mandatory">*</span>'),
                        'options'       => $clients,
                        'empty'         => true,
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 1
                    ));
                ?>
            </div>
        </div>
    <?php else: ?>
        <?php echo $this->Form->hidden('user_id', array('value' => $userId)); ?>
    <?php endif; ?> 
    <?php
        echo $this->element('plans/live_chat_plan_user_form_fields', array(
            "plan"      => $plan,
            "userId"    => @$userId
        ));
    ?>
<?php echo $this->Form->end(); ?>