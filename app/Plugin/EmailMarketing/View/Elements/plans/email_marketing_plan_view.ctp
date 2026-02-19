<div class="row">
    <div class="col-xs-12">
        <div class="col-sm-offset-1 col-sm-10">

            <dl class="dl-horizontal">
                <dt><?php echo __('Id'); ?></dt>
                <dd>
                    <?php echo h($plan['EmailMarketingPlan']['id']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Name'); ?></dt>
                <dd>
                    <?php echo h($plan['EmailMarketingPlan']['name']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Description'); ?></dt>
                <dd>
                    <?php echo $plan['EmailMarketingPlan']['description']; ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Email Limit'); ?></dt>
                <dd>
                    <?php echo h($plan['EmailMarketingPlan']['email_limit']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Unit Price'); ?></dt>
                <dd>
                    <?php echo h($plan['EmailMarketingPlan']['unit_price']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Total Price'); ?></dt>
                <dd>
                    <?php echo h($plan['EmailMarketingPlan']['total_price']); ?>
                    &nbsp;
                </dd>
            </dl>
            
        </div>
    </div>
</div>