<?php if(empty($sender)): ?>
    <div class="row">
        <?php echo __('No sender details found'); ?>
    </div>
<?php else: ?>
    <div class="row">
        <?php if($userGroupName === Configure::read('System.admin.group.name')): ?>
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <?php echo __('Id'); ?>
                </div>
                <div class="col-xs-6">
                    <?php echo h($sender['EmailMarketingSender']['id']); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Sender Domain'); ?>
            </div>
            <div class="col-xs-6">
                <?php echo h($sender['EmailMarketingSender']['sender_domain']); ?>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Public Key Download'); ?>
                <!--img src="/img/Hint.gif" data-toggle="tooltip" data-placement="right" data-original-title="Download the key and add it to domain DNS settings" class="hint-icon" alt="Hint" /-->
            </div>
            <div class="col-xs-6">
                <?php echo $sender['EmailMarketingSender']['public_key_download_link']; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- page specific plugin scripts -->
<?php echo $this->element('page/admin/load_inline_js'); ?>