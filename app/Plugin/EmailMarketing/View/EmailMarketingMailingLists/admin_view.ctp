<div class="row">
    <div class="col-xs-12">
        <div>
            <div class="col-sm-offset-1 col-sm-10">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-16">
                        <li class="active">
                            <a data-toggle="tab" href="#view-email-marketing-mailing-list-details">
                                <i class="green ace-icon fa fa-list-alt bigger-125"></i>
                                <?php echo __('List Details'); ?>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#view-email-marketing-mailing-list-subscribers">
                                <i class="purple ace-icon fa fa-group bigger-125"></i>
                                <?php echo __('Subscribers in List'); ?>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane active" id="view-email-marketing-mailing-list-details">
                            <?php
                                echo $this->element('lists/email_marketing_mailing_list_view', array(
                                    "list" => $list
                                )); 
                            ?>
                        </div>
                        <div class="tab-pane" id="view-email-marketing-mailing-list-subscribers">
                            <iframe src="/admin/email_marketing/email_marketing_subscribers/index/<?php echo $list['EmailMarketingMailingList']['id']; ?>/<?php echo $this->request->params['action']; ?>?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
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