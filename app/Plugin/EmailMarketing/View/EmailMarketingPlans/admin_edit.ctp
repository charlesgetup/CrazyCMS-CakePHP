<div class="row">
    <div class="col-xs-12">
        <div>
            <div class="col-sm-offset-1 col-sm-10">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-16">
                        <li class="active">
                            <a data-toggle="tab" href="#edit-email-marketing-plan">
                                <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                                <?php echo __('Edit Plan Details'); ?>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#edit-email-marketing-plan-users">
                                <i class="purple ace-icon fa fa-group bigger-125"></i>
                                <?php echo __('Manage Plan Users'); ?>
                            </a>
                        </li>
                        <li class="add-plan-user">
                            <a data-toggle="tab" href="#add-email-marketing-plan-user">
                                <i class="blue ace-icon fa fa-user bigger-125"></i>
                                <?php echo __('Add Plan User'); ?>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane in active" id="edit-email-marketing-plan">
                            <?php
                                echo $this->Form->create('EmailMarketing.EmailMarketingPlan');
                                echo $this->element('plans/email_marketing_plan_form_fields');
                                echo $this->Form->end();
                            ?>
                        </div>
                        <div class="tab-pane" id="edit-email-marketing-plan-users">
                            <iframe src="/admin/email_marketing/email_marketing_users/index/<?php echo $plan['EmailMarketingPlan']['id']; ?>/<?php echo $this->request->params['action']; ?>?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
                        </div>
                        <div class="tab-pane" id="add-email-marketing-plan-user">
                            <iframe src="/admin/email_marketing/email_marketing_users/add/<?php echo $plan['EmailMarketingPlan']['id']; ?>?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        $('ul.nav.nav-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = e.target; 
            showLoaddingSpinner(target);
            setTimeout(function(){
                var li = $(target).parent('li');
                var tabContentId = $(target).attr('href');
                var iframe = li.closest('ul').next('.tab-content').children(tabContentId).children('iframe');
                if(iframe.length){
                    var submitBtn = window.parent.$('.modal-body').siblings("div.modal-footer").children('.submit-iframe-form-btn'); 
                    if(li.hasClass('add-plan-user')){
                        var iframeId = iframe.attr('id');
                        submitBtn.attr("data-iframeId", iframeId);
                        submitBtn.attr("data-submitFormId", getFormHolderIframe(iframeId).find("form:first").attr("id"));
                        submitBtn.removeClass("close-popup-after-submit");
                    }else{
                        submitBtn.removeAttr('data-iframeId');
                        submitBtn.removeAttr('data-submitFormId');
                        submitBtn.addClass("close-popup-after-submit");
                    }
                }
                hideLoaddingSpinner(target);
            }, 100);
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>