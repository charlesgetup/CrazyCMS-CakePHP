<dl class="dl-horizontal">
    <dt><?php echo __('First Name'); ?></dt>
    <dd>
        <?php echo h($user['User']['first_name']); ?>
    </dd>
    <dt><?php echo __('Last Name'); ?></dt>
    <dd>
        <?php echo h($user['User']['last_name']); ?>
    </dd>
    <dt><?php echo __('Active'); ?></dt>
    <dd>
        <?php echo ((intval($user['User']['active']) == 1) ? __('Yes') : "<b style='color:red'>" .__('No') ."</b>"); ?>
    </dd>
    <dt><?php echo __('Email'); ?></dt>
    <dd>
        <?php echo h($user['User']['email']); ?>
    </dd>
    <dt><?php echo __('Group'); ?></dt>
    <dd>
        <?php echo h($user['Group']['name']); ?>
    </dd>
    <dt><?php echo __('Phone'); ?></dt>
    <dd>
        <?php echo h($user['User']['phone']); ?>
    </dd>
    <dt data-rel="tooltip" data-placement="right" data-original-title="Used for invoicing purpose" class="tooltip-text"><?php echo __('Company'); ?>&nbsp;</dt>
    <dd>
        <?php echo h($user['User']['company']); ?>
    </dd>
    <dt data-rel="tooltip" data-placement="right" data-original-title="Used for invoicing purpose" class="tooltip-text"><?php echo __('ABN/ACN (Australian business only)'); ?>&nbsp;</dt>
    <dd>
        <?php echo h($user['User']['abn_acn']); ?>
    </dd>
</dl>