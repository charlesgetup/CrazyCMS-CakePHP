<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <!-- #section:pages/error -->
        <div class="error-container">
            <div class="well">
                <h1 class="grey lighter smaller">
                    <span class="blue bigger-125">
                        <i class="ace-icon fa fa-random"></i>
                        500
                    </span>
                    <?php echo __("Something Went Wrong"); ?>
                </h1>

                <hr />
                <h3 class="lighter smaller">
                    <?php echo __("But we are working %s on it!", '<i class="ace-icon fa fa-wrench icon-animated-wrench bigger-125"></i>'); ?>
                </h3>

                <div class="space"></div>

                <div>
                    <h4 class="lighter smaller"><?php echo __("Meanwhile, try one of the following:"); ?></h4>

                    <ul class="list-unstyled spaced inline bigger-110 margin-15">
                        <li>
                            <i class="ace-icon fa fa-hand-o-right blue"></i>
                            <?php echo __("Read the faq"); ?>
                        </li>

                        <li>
                            <i class="ace-icon fa fa-hand-o-right blue"></i>
                            <?php echo __("Give us more info on how this specific error occurred!"); ?>
                        </li>
                    </ul>
                </div>

                <hr />
                <div class="space"></div>

                <div class="center">
                    <a href="javascript:history.back()" class="btn btn-grey">
                        <i class="ace-icon fa fa-arrow-left"></i>
                        <?php echo __("Go Back"); ?>
                    </a>

                    <a href="javascript:gotoHomePage()" class="btn btn-primary">
                        <i class="ace-icon fa fa-tachometer"></i>
                        <?php echo __("Dashboard"); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- /section:pages/error -->

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->