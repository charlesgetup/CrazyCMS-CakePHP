<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <!-- #section:pages/error -->
        <div class="error-container">
            <div class="well">
                <h1 class="grey lighter smaller">
                    <span class="blue bigger-125">
                        <i class="ace-icon fa fa-sitemap"></i>
                        404
                    </span>
                    <?php echo __("Page Not Found"); ?>
                </h1>

                <hr />
                <h3 class="lighter smaller"><?php echo __("We looked everywhere but we couldn't find it!"); ?></h3>

                <div>
                    <?php if(false): ?>
                    <form class="form-search">
                        <span class="input-icon align-middle">
                            <i class="ace-icon fa fa-search"></i>

                            <input type="text" class="search-query" placeholder="<?php echo __("Give it a search..."); ?>" />
                        </span>
                        <button class="btn btn-sm" type="button"><?php echo __("Go!"); ?></button>
                    </form>
                    <?php endif; ?>

                    <div class="space"></div>
                    <h4 class="smaller"><?php echo __("Try one of the following:"); ?></h4>

                    <ul class="list-unstyled spaced inline bigger-110 margin-15">
                        <li>
                            <i class="ace-icon fa fa-hand-o-right blue"></i>
                            <?php echo __("Re-check the url for typos"); ?>
                        </li>

                        <li>
                            <i class="ace-icon fa fa-hand-o-right blue"></i>
                            <?php echo __("Read the faq"); ?>
                        </li>

                        <li>
                            <i class="ace-icon fa fa-hand-o-right blue"></i>
                            <?php echo __("Tell us about it"); ?>
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