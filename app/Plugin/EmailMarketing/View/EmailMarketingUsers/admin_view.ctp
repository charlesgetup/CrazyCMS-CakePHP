<dl class="dl-horizontal">
    <dt><?php echo __('Plan'); ?></dt>
    <dd>
        <?php echo h($user['EmailMarketingPlan']['name']); ?>
    </dd>
    <dt><?php echo __('Email Sender Limit'); ?></dt>
    <dd>
        <?php echo h($user['EmailMarketingUser']['email_sender_limit']); ?>
    </dd>
    <dt><?php echo __('Remaining Free Email Amount'); ?></dt>
    <dd>
        <?php echo h($user['EmailMarketingUser']['free_emails']); ?>
    </dd>
    <dt data-rel="tooltip" data-placement="right" data-original-title="How much of the Monthly Limit is used. This includes the free email usage, too." class="tooltip-text"><?php echo __('Email Sent Amount Within Monthly Limit'); ?>&nbsp;</dt>
    <dd>
        <?php echo h($user['EmailMarketingUser']['used_email_count']); ?>
    </dd>
    <dt><?php echo __('Total Email Sent Amount'); ?></dt>
    <dd>
        <?php echo h($user['EmailMarketingUser']['total_sent_email_amount']); ?>
    </dd>
    <hr />
    <dt><?php echo __('Payment Cycle'); ?></dt>
    <dd>
        <?php echo h($user['EmailMarketingUser']['payment_cycle']); ?>
    </dd>
    <dt><?php echo __('Prepaid Amount'); ?></dt>
    <dd>
        <?php echo h($user['EmailMarketingUser']['prepaid_amount']); ?>
    </dd>
    <dt><?php echo __('Last Pay Date'); ?></dt>
    <dd>
        <?php echo h($user['EmailMarketingUser']['last_pay_date']); ?>
    </dd>
    <dt><?php echo __('Next Pay Date'); ?></dt>
    <dd>
        <?php echo h($user['EmailMarketingUser']['next_pay_date']); ?>
    </dd>
</dl>