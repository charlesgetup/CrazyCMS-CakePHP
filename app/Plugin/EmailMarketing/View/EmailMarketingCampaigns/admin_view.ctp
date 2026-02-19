<div class="row">
    <div class="col-xs-12">
        <h5><?php echo __('Campaign Details'); ?></h5>
    </div>
    <?php if($userGroupName === Configure::read('System.admin.group.name')): ?>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Id'); ?>
            </div>
            <div class="col-xs-6">
                <?php echo h($campaign['EmailMarketingCampaign']['id']); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Name'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($campaign['EmailMarketingCampaign']['name']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Template'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($campaign['EmailMarketingCampaign']['template_name']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Email Format'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($campaign['EmailMarketingCampaign']['send_format']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Status'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($campaign['EmailMarketingCampaign']['status']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Created By'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($campaign['EmailMarketingCampaign']['user_name']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Created  Timestamp'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($campaign['EmailMarketingCampaign']['created']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Modified  Timestamp'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($campaign['EmailMarketingCampaign']['modified']); ?>
        </div>
    </div>
</div>
<div class="row hr hr-16 hr-dotted"></div>
<div class="row">
    <div class="col-xs-12">
        <h5><?php echo __('Preview Campaign Email'); ?></h5>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-12">
            <?php if(!empty($campaign['EmailMarketingCampaign']['text_message'])): ?>
                <a href="/admin/email_marketing/email_marketing_campaigns/preview/<?php echo Configure::read('EmailMarketing.email.type.text'); ?>/<?php echo $campaign['EmailMarketingCampaign']['id']; ?>" target="_blank">
                    <i class="pink ace-icon fa fa-file-text-o bigger-125"></i>
                    <?php echo __('Preview Text Email'); ?>
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;
            <?php endif; ?>
            <a href="/admin/email_marketing/email_marketing_campaigns/preview/<?php echo Configure::read('EmailMarketing.email.type.html'); ?>/<?php echo $campaign['EmailMarketingCampaign']['id']; ?>" target="_blank">
                <i class="pink ace-icon fa fa-html5 bigger-125"></i>
                <?php echo __('Preview HTML Email'); ?>
            </a>
        </div>
    </div>
</div>
<div class="row hr hr-16 hr-dotted"></div>
<div class="row">
    <div class="col-xs-12">
        <h5><?php echo __('Included Lists'); ?></h5>
    </div>
    <div class="col-xs-12">
        <ul>
            <?php foreach($campaign['EmailMarketingMailingList'] as $list): ?>
                <li>
                    <?php 
                        echo isset($list['name']) ? h("{$list['name']}") : "";
                        echo ($userGroupName === Configure::read('System.admin.group.name')) ? "&nbsp;&nbsp;&nbsp;&nbsp;" .h("[ ID: {$list['id']} ]") : "";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;" .__("Total subscribers") .":&nbsp;&nbsp;" .count($list['EmailMarketingSubscriber']) ."&nbsp;)";
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => ''
    )); 
?>