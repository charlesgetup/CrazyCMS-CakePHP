<div class="row">
    <div class="col-xs-12">
        <div class="col-sm-offset-1 col-sm-10">

            <dl class="dl-horizontal">
                <dt><?php echo __('Id'); ?></dt>
                <dd>
                    <?php echo h($plan['LiveChatPlan']['id']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Name'); ?></dt>
                <dd>
                    <?php echo h($plan['LiveChatPlan']['name']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Description'); ?></dt>
                <dd>
                    <?php echo $plan['LiveChatPlan']['description']; ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Price'); ?></dt>
                <dd>
                    <?php echo h($plan['LiveChatPlan']['price']); ?>
                    &nbsp;
                </dd>
            </dl>
            
        </div>
    </div>
</div>