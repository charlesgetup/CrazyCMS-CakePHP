<?php
    $isAdd = $this->params['action'] == 'admin_add';
?>
<div class="row">
    <div class="col-xs-12">
        <div>
            <div class="col-sm-offset-1 col-sm-10">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-16">
                        <li class="active">
                            <a data-toggle="tab" href="#edit-email-marketing-mailing-list">
                                <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                                <?php echo __('Edit List'); ?>
                            </a>
                        </li>
                        <?php if(!$isAdd): ?>
                            <li>
                                <a data-toggle="tab" href="#edit-email-marketing-mailing-list-subscribers">
                                    <i class="purple ace-icon fa fa-group bigger-125"></i>
                                    <?php echo __('Manage Subscribers'); ?>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#import-email-marketing-mailing-list-subscribers">
                                    <i class="blue ace-icon fa fa-user bigger-125"></i>
                                    <?php echo __('Import Subscribers'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane in active" id="edit-email-marketing-mailing-list">
                            <?php
                                echo $this->Form->create('EmailMarketing.EmailMarketingMailingList');
                                echo $this->element('lists/email_marketing_mailing_list_form_fields', array(
                                    "userList"      => $userList
                                ));
                                echo $this->Form->end();
                            ?>
                        </div>
                        <?php if(!$isAdd): ?>
                            <div class="tab-pane" id="edit-email-marketing-mailing-list-subscribers">
                                <iframe src="/admin/email_marketing/email_marketing_subscribers/index/<?php echo $this->request->data['EmailMarketingMailingList']['id']; ?>/<?php echo $this->request->params['action']; ?>?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
                            </div>
                            <div class="tab-pane" id="import-email-marketing-mailing-list-subscribers">
                                <iframe src="/admin/email_marketing/email_marketing_subscribers/import/<?php echo $this->request->data['EmailMarketingMailingList']['id']; ?>?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
                            </div>
                        <?php endif; ?>
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