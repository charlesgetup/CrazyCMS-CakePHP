<?php $isPaid = isset($invoice['PaymentInvoice']['status']) && $invoice['PaymentInvoice']['status'] == Configure::read('Payment.invoice.status.paid'); ?>
<?php $isRefund = isset($invoice['PaymentInvoice']['status']) && $invoice['PaymentInvoice']['status'] == Configure::read('Payment.invoice.status.refund'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title><?php echo __("Customer Invoice"); ?></title>

        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <?php
            echo $this->Html->css(array('admin/bootstrap', 'admin/font-awesome', 'admin/ace-fonts', 'admin/ace'));
        ?>

        <!--[if lte IE 9]>
            <?php echo $this->Html->css('admin/ace-part2'); ?>
        <![endif]-->

        <!--[if lte IE 9]>
            <?php echo $this->Html->css('admin/ace-ie'); ?>
        <![endif]-->

        <!-- inline styles related to this page -->
        <style>
            ul.list-unstyled.spaced li ul.list-unstyled.spaced {
                text-indent: 1.6em;
            }
            ul.list-unstyled.spaced li ul.list-unstyled.spaced li span.title {
                font-weight: bold;
            }
            .title-small {
                width: 60px;
                display: inline-block;
            }
            .title-big {
                width: 150px;
                display: inline-block;
            }
        </style>
        
        <!-- ace settings handler -->
        <?php echo $this->Html->script(array('admin/ace-extra')); ?>

        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

        <!--[if lt IE 8]>
            <?php echo $this->Html->script(array('admin/html5shiv', 'admin/respond')); ?>
        <![endif]-->
    </head>

    <body class="no-skin invoice" style="width: 768px; max-width: 768px; min-width: 768px; background-color: white;">

        <!-- /section:basics/navbar.layout -->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try{ace.settings.check('main-container' , 'fixed')}catch(e){}
            </script>

            <!-- /section:basics/sidebar -->
            <div class="main-content">
                <div class="main-content-inner">

                    <!-- /section:basics/content.breadcrumbs -->
                    <div class="page-content">

                        <!-- /section:settings.box -->
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <div class="space-6"></div>

                                <div class="row">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <!-- #section:pages/invoice -->
                                        <div class="widget-box transparent" style="border: none;">
                                            <div class="widget-header widget-header-large" style="background: #fff; padding-bottom: 1em; margin-bottom: 1em;">
                                                <h3 class="widget-title grey lighter" style="width: 220px; display: inline-block; float: left;">
                                                    <img src="/img/admin/spinner.png" height="48" border="0" />
                                                    <?php echo __("Customer Invoice"); ?>
                                                </h3>

                                                <!-- #section:pages/invoice.info -->
                                                <div class="widget-toolbar no-border invoice-info" style="width: 217px; clear: none;">
                                                    <span class="invoice-info-label"><?php echo __("Invoice"); ?>:</span>
                                                    <span class="red"><?php echo $invoice['PaymentInvoice']['number']; ?></span>

                                                    <br />
                                                    
                                                    <span class="invoice-info-label"><?php echo $isPaid ? __("Paid Date") : ($isRefund ? __("Refund Date") : __("Due Date")); ?>:</span>
                                                    <span class="<?php echo $isRefund ? 'red' : 'blue'; ?>"><?php echo $this->Time->i18nFormat((($isPaid || $isRefund) ? $invoice['PaymentInvoice']['modified'] : $invoice['PaymentInvoice']['due_date']), '%x'); ?></span>
                                                </div>

                                                <div class="widget-toolbar hidden-480">
                                                    <a href="javascript:parent.window.print();">
                                                        <i class="ace-icon fa fa-print"></i>
                                                    </a>
                                                </div>

                                                <!-- /section:pages/invoice.info -->
                                            </div>

                                            <div class="widget-body">
                                                <div class="widget-main padding-24" style="padding-right: 0px;">
                                                    <div class="row">
                                                        <div class="col-sm-6" style="width: 289px;">
                                                            <div class="row" style="margin-bottom: 1em;">
                                                                <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                                                    <b><?php echo __('Company Info'); ?></b>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <ul class="list-unstyled spaced">
                                                                    <li>
                                                                        <i class="ace-icon fa fa-caret-right blue"></i>
                                                                        <?php echo $companyName; ?>
                                                                    </li>

                                                                    <li>
                                                                        <i class="ace-icon fa fa-caret-right blue"></i>
                                                                        <span class="title-small"><?php echo __("Address"); ?>:</span>
                                                                        <span class="content"><?php echo $companyAddress; ?></span>
                                                                    </li>

                                                                    <li>
                                                                        <i class="ace-icon fa fa-caret-right blue"></i>
                                                                        <span class="title-small"><?php echo __("Email"); ?>:</span>
                                                                        <span class="content"><a href="mailto:<?php echo $companyEmail; ?>?Subject=Invoice%20enquire(#<?php echo $invoice['PaymentInvoice']['number']; ?>)" target="_top"><?php echo $companyEmail; ?></a></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div><!-- /.col -->

                                                        <div class="col-sm-6" style="width: 289px; padding-right: 0;">
                                                            <div class="row" style="margin-bottom: 1em;">
                                                                <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                                                    <b><?php echo __("Customer Info"); ?></b>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <ul class="list-unstyled  spaced">
                                                                    <li>
                                                                        <i class="ace-icon fa fa-caret-right green"></i>
                                                                        <?php echo $invoice["ClientUser"]["name"]; ?>
                                                                    </li>

                                                                    <?php if(isset($invoice["ClientUser"]["company"]) && !empty($invoice["ClientUser"]["company"])): ?>
                                                                        <li>
                                                                            <i class="ace-icon fa fa-caret-right green"></i>
                                                                            <span class="title-small"><?php echo __("Company"); ?>:</span>
                                                                            <span class="content"><?php echo $invoice["ClientUser"]["company"]; ?></span>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if(isset($invoice["ClientUser"]["abn_acn"]) && !empty($invoice["ClientUser"]["abn_acn"])): ?>
                                                                        <li>
                                                                            <i class="ace-icon fa fa-caret-right green"></i>
                                                                            <span class="title-small"><?php echo __("ABN/ACN"); ?>:</span>
                                                                            <span class="content"><?php echo $invoice["ClientUser"]["abn_acn"]; ?></span>
                                                                        </li>
                                                                    <?php endif; ?>

                                                                    <?php
                                                                        $user           = $invoice["ClientUser"];
                                                                        $index          = (isset($user['Address'][1]['same_as']) && intval($user['Address'][1]['same_as']) != 0) ? 0 : 1;
                                                                        $streetAddress  = isset($user['Address'][$index]['street_address']) ? $user['Address'][$index]['street_address'] : "";
                                                                        $suburb         = isset($user['Address'][$index]['suburb']) ? $user['Address'][$index]['suburb'] : "";
                                                                        $state          = isset($user['Address'][$index]['state']) ? $user['Address'][$index]['state'] : "";
                                                                        $postcode       = isset($user['Address'][$index]['postcode']) ? $user['Address'][$index]['postcode'] : "";
                                                                        $country        = isset($user['Address'][$index]['Country']['name']) ? $user['Address'][$index]['Country']['name'] : "";
                                                                    ?>
                                                                    <li>
                                                                        <i class="ace-icon fa fa-caret-right green"></i>
                                                                        <span class="title-small"><?php echo __("Address"); ?>:</span>
                                                                        <span class="content"><?php echo "{$streetAddress}, {$suburb}, {$state}, {$postcode}, {$country}"; ?></span>
                                                                    </li>

                                                                    <?php if(isset($invoice["ClientUser"]["phone"]) && !empty($invoice["ClientUser"]["phone"])): ?>
                                                                        <li>
                                                                            <i class="ace-icon fa fa-caret-right green"></i>
                                                                            <span class="title-small"><?php echo __("Phone"); ?>:</span>
                                                                            <span class="content"><?php echo $invoice["ClientUser"]["phone"]; ?></span>
                                                                        </li>
                                                                    <?php endif; ?>
        
                                                                    <li>
                                                                        <i class="ace-icon fa fa-caret-right green"></i>
                                                                        <span class="title-small"><?php echo __("Email"); ?>:</span>
                                                                        <span class="content"><a href="mailto:<?php echo $invoice["ClientUser"]["email"]; ?>" target="_top"><?php echo $invoice["ClientUser"]["email"]; ?></a></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div><!-- /.col -->
                                                    </div><!-- /.row -->

                                                    <div class="row">
                                                        <div class="col-sm-6" style="width: 289px; padding-right: 0;">
                                                            <div class="row" style="margin-bottom: 1em;">
                                                                <div class="col-xs-11 label label-lg label-pink arrowed-in arrowed-right">
                                                                    <b><?php echo __("Paymant Info"); ?></b>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <ul class="list-unstyled spaced">
                                                                    <?php if(isset($invoice['PaymentInvoice']['is_auto_created']) && empty($invoice['PaymentInvoice']['is_auto_created'])): ?>
                                                                        <li>
                                                                            <i class="ace-icon fa fa-caret-right pink"></i>
                                                                            <span class="title-small"><?php echo __("Bank"); ?>:</span>
                                                                            <span class="content">National Australia Bank</span>
                                                                        </li>
                                                                        <li>
                                                                            <i class="ace-icon fa fa-caret-right pink"></i>
                                                                            <span class="title-small"><?php echo __("BSB"); ?>:</span>
                                                                            <span class="content">082-001</span>
                                                                        </li>
                                                                        <li>
                                                                            <i class="ace-icon fa fa-caret-right pink"></i>
                                                                            <span class="title-big"><?php echo __("Account Number"); ?>:</span>
                                                                            <span class="content">19-261-2481</span>
                                                                        </li>
                                                                        <li>
                                                                            <i class="ace-icon fa fa-caret-right pink"></i>
                                                                            <span class="title-big"><?php echo __("Account Name"); ?>:</span>
                                                                            <span class="content">Yanfeng Li</span>
                                                                        </li>
                                                                    <?php else: ?>
                                                                        <li>
                                                                            <i class="ace-icon fa fa-caret-right pink"></i>
                                                                            <span class="title-big"><?php echo __("Payment method"); ?>:</span>
                                                                            <span class="content">PayPal</span>
                                                                        </li>
                                                                        <li>
                                                                            <i class="ace-icon fa fa-caret-right pink"></i>
                                                                            <span class="title-big"><?php echo __("Payer Email"); ?>:</span>
                                                                            <span class="content"><a href="mailto:<?php echo $invoice["ClientUser"]["email"]; ?>" target="_top"><?php echo $invoice["ClientUser"]["email"]; ?></a></span>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div><!-- /.col -->
                                                    </div><!-- /.row -->

                                                    <div class="space"></div>

                                                    <div>
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th align="left"><?php echo __("Description"); ?></td>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <tr>
                                                                    <td class="content">
                                                                        <?php echo $invoice['PaymentInvoice']['content']; ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="hr hr8 hr-double hr-dotted"></div>

                                                    <div class="row">
                                                        <div class="col-sm-5 pull-right">
                                                            <h4 class="pull-right">
                                                                <span class="title-big"><?php echo __('Pay this amount'); ?>:</span>
                                                                <span class="<?php echo $isPaid ? "green" : "red"; ?>"><strong><?php echo $invoice['PaymentInvoice']['currency']; ?>$<?php echo $invoice['PaymentInvoice']['amount']; ?></strong></span>
                                                            </h4>
                                                            <?php $notPaidInFull = ($invoice['PaymentInvoice']['paid_amount'] < $invoice['PaymentInvoice']['amount']); ?>
                                                            <?php if($invoice['PaymentInvoice']['paid_amount'] > 0): ?>
                                                                <h4 class="pull-right">
                                                                    <span class="title-big"><?php echo __('Paid amount'); ?>:</span>
                                                                    <span class="green"><strong><?php echo $invoice['PaymentInvoice']['currency']; ?>$<?php echo $invoice['PaymentInvoice']['paid_amount']; ?></strong></span>
                                                                </h4>
                                                                <?php if($taxGSTRate > 0): ?>
                                                                    <h4 class="pull-right">
                                                                        <span class="title-big"><?php echo __('Tax paid'); ?>:</span>
                                                                        <span class="green"><strong><?php echo $invoice['PaymentInvoice']['currency']; ?>$<?php echo $invoice['PaymentInvoice']['paid_amount'] * (1 - $taxGSTRate); ?></strong></span>
                                                                    </h4>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <?php if($invoice['PaymentInvoice']['refund_amount'] > 0): ?>
                                                                <?php $notPaidInFull = false; // If transaction is refuneded, no further process needed ?>
                                                                <h4 class="pull-right">
                                                                    <span class="title-big"><?php echo __('Refund amount'); ?>:
                                                                    <span class="red"><strong><?php echo $invoice['PaymentInvoice']['currency']; ?>$<?php echo $invoice['PaymentInvoice']['refund_amount']; ?></strong></span>
                                                                </h4>
                                                            <?php endif; ?>
                                                            <?php if($notPaidInFull): ?>
                                                                <h4 class="pull-right">
                                                                    <span class="title-big"><?php echo __('Balance'); ?>:</span>
                                                                    <span class="red"><strong><?php echo $invoice['PaymentInvoice']['currency']; ?>$<?php echo ($invoice['PaymentInvoice']['amount'] - $invoice['PaymentInvoice']['paid_amount']); ?></strong></span>
                                                                </h4>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-sm-7 pull-left"> <?php echo __('Extra Information'); ?> </div>
                                                    </div>

                                                    <div class="space-6"></div>

                                                    <div class="well" style="-webkit-box-shadow: none; box-shadow: none;">
                                                        <?php echo __("Thank you for choosing") ." " .$companyName .". " .__("We believe you will be satisfied by our services."); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- /section:pages/invoice -->
                                    </div>
                                </div>

                                <!-- PAGE CONTENT ENDS -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <div class="footer">
                <div class="footer-inner">
                    <!-- #section:basics/footer -->
                    <div class="footer-content">
                        <span class="bigger-120">
                           <?php echo $companyName; ?>&nbsp;&copy;&nbsp;<?php echo date('Y'); ?>
                        </span>
                    </div>

                    <!-- /section:basics/footer -->
                </div>
            </div>

        </div><!-- /.main-container -->

        <!-- basic scripts -->

    </body>
</html>
