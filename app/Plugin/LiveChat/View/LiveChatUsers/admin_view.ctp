<dl class="dl-horizontal">
    <dt><?php echo __('Plan'); ?></dt>
    <dd>
        <?php echo h($user['LiveChatPlan']['name']); ?>
    </dd>
    <dt><?php echo __('Operator Amount'); ?></dt>
    <dd>
        <?php echo h($user['LiveChatUser']['operator_amount']); ?>
    </dd>
    <hr />
    <dt><?php echo __('Payment Cycle'); ?></dt>
    <dd>
        <?php echo h($user['LiveChatUser']['payment_cycle']); ?>
    </dd>
    <dt><?php echo __('Last Pay Date'); ?></dt>
    <dd>
        <?php echo h($user['LiveChatUser']['last_pay_date']); ?>
    </dd>
    <dt><?php echo __('Next Pay Date'); ?></dt>
    <dd>
        <?php echo h($user['LiveChatUser']['next_pay_date']); ?>
    </dd>
</dl>