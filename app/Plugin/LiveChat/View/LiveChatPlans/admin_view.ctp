<div class="row">
    <div class="col-xs-12">
        <div>
            <div class="col-sm-offset-1 col-sm-10">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-16">
                        <li class="active">
                            <a data-toggle="tab" href="#view-live-chat-plan">
                                <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                                <?php echo __('Plan Details'); ?>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#view-live-chat-plan-users">
                                <i class="purple ace-icon fa fa-group bigger-125"></i>
                                <?php echo __('Plan Users'); ?>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane in active" id="view-live-chat-plan">
                            <?php
                                echo $this->element('plans/live_chat_plan_view', array(
                                    "plan" => $plan
                                )); 
                            ?>
                        </div>
                        <div class="tab-pane" id="view-live-chat-plan-users">
                            <iframe src="/admin/live_chat/live_chat_users/index/<?php echo $plan['LiveChatPlan']['id']; ?>?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => ''
    )); 
?>