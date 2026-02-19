<div class="row">
    <div class="col-xs-12">
        <div>
            <div class="col-sm-offset-1 col-sm-10">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-16">
                        <li class="active">
                            <a data-toggle="tab" href="#view-invoice-details">
                                <i class="green ace-icon fa fa-money bigger-125"></i>
                                <?php echo __('Invoice Details'); ?>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#view-billing-address">
                                <i class="purple ace-icon fa fa-address-book-o bigger-125" aria-hidden="true"></i>
                                <?php echo __('Billing Address'); ?>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane in active" id="view-invoice-details">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <dl class="dl-horizontal">
                                            <?php if($userGroupName === Configure::read('System.admin.group.name') && !$isTempInvoice): ?>
                                                <dt><?php echo __('Id'); ?></dt>
                                                <dd>
                                                    <?php echo h($paymentInvoiceId); ?>
                                                    &nbsp;
                                                </dd>
                                            <?php endif; ?>
                                            <dt><?php echo __('Name'); ?></dt>
                                            <dd>
                                                <?php echo h($userInfo['first_name'] .' ' .$userInfo['last_name']); ?>
                                                &nbsp;
                                            </dd>
                                            <dt><?php echo __('Contact'); ?></dt>
                                            <dd>
                                                <?php echo h($userInfo['email']) .(empty($userInfo['phone']) ? '' : " / " .h($userInfo['phone'])); ?>
                                                &nbsp;
                                            </dd>
                                            <dt><?php echo __('Amount'); ?></dt>
                                            <dd>
                                                <?php $paymentInfo['paid_amount'] = empty($paymentInfo['paid_amount']) ? 0 : $paymentInfo['paid_amount']; ?>
                                                <?php echo h($paymentInfo['amount'] - $paymentInfo['paid_amount']); ?>
                                                &nbsp;
                                            </dd>
                                            <dt><?php echo __('Currency'); ?></dt>
                                            <dd>
                                                <?php echo $currency; ?>
                                                &nbsp;
                                            </dd>
                                            <?php if(!empty($paymentInfo['number']) && !$isTempInvoice): ?>
                                                <dt><?php echo __('Invoice Number'); ?></dt>
                                                <dd>
                                                    <?php echo h($paymentInfo['number']); ?>
                                                    &nbsp;
                                                </dd>
                                            <?php endif; ?>
                                            <dt><?php echo __('Description'); ?></dt>
                                            <dd>
                                                <?php echo html_entity_decode($paymentInfo['content']); ?>
                                                &nbsp;
                                            </dd>
                                            <dt><?php echo __('Due Date'); ?></dt>
                                            <dd>
                                                <?php echo $this->Time->i18nFormat($paymentInfo['due_date'], '%x %X'); ?>
                                                &nbsp;
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="view-billing-address">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <dl class="dl-horizontal">
                                            <dt><?php echo __('Street'); ?></dt>
                                            <dd>
                                                <?php echo h($billingAddress['street_address']); ?>
                                                &nbsp;
                                            </dd>
                                            <dt><?php echo __('Suburb'); ?></dt>
                                            <dd>
                                                <?php echo h($billingAddress['suburb']); ?>
                                                &nbsp;
                                            </dd>
                                            <dt><?php echo __('State'); ?></dt>
                                            <dd>
                                                <?php echo h($billingAddress['state']); ?>
                                                &nbsp;
                                            </dd>
                                            <dt><?php echo __('Postcode'); ?></dt>
                                            <dd>
                                                <?php echo h($billingAddress['postcode']); ?>
                                                &nbsp;
                                            </dd>
                                            <dt><?php echo __('Country'); ?></dt>
                                            <dd>
                                                <?php echo h($country['Country']['name'] . " / " .$country['Country']['code']); ?>
                                                &nbsp;
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr />
<h6><?php echo __('If the above information is correct, please pay the amount using one of the following options.'); ?></h6>
<hr />