<div class="row">
    <div class="col-xs-12">
        <dl>
            <dt><?php echo __('Subscriber'); ?></dt>
            <dd><?php echo $subscriber['EmailMarketingSubscriber']['first_name'] ." " .$subscriber['EmailMarketingSubscriber']['last_name']; ?></dd>
        </dl>
    </div>
    <div class="col-xs-12">
        <dl>
            <dt><?php echo __('Email'); ?></dt>
            <dd><?php echo $subscriber['EmailMarketingSubscriber']['email']; ?></dd>
        </dl>
    </div>
</div>
<div class="row hr hr-16 hr-dotted"></div>
<div class="row">
    <div class="col-xs-12">
        <h5><?php echo __("Open campaign email details"); ?></h5>
    </div>
    <div class="col-xs-12">
        <iframe src="/admin/email_marketing/email_marketing_statistics/getSubscriberOpenRecord/<?php echo $subscriberId; ?>/<?php echo $statisticId; ?>?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
    </div>
</div>
<div class="row hr hr-16 hr-dotted"></div>
<div class="row">
    <div class="col-xs-12">
        <h5><?php echo __("Click campaign email details"); ?></h5>
    </div>
    <div class="col-xs-12">
        <iframe src="/admin/email_marketing/email_marketing_statistics/getSubscriberClickRecord/<?php echo $subscriberId; ?>/<?php echo $statisticId; ?>?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
    </div>
</div>
<div class="row hr hr-16 hr-dotted"></div>
<div class="row">
    <div class="col-xs-12">
        <h5><?php echo __("Bounce campaign email details"); ?></h5>
    </div>
    <div class="col-xs-12">
        <iframe src="/admin/email_marketing/email_marketing_statistics/getSubscriberBounceRecord/<?php echo $subscriberId; ?>/<?php echo $statisticId; ?>?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
    </div>
</div>