<div id="forgot-box" class="forgot-box <?php echo ($this->params['action'] == "forgetPassword") ? "visible" : ""; ?> widget-box no-border">
    <div class="widget-body">
        <div class="widget-main">
            <h4 class="header red lighter bigger">
                <i class="ace-icon fa fa-key"></i>
                <?php echo __('Retrieve Password'); ?>
            </h4>

            <div class="space-6"></div>
            <p>
                <?php echo __('Enter your email and to receive instructions'); ?>
            </p>

            <?php echo $this->Form->create('User', array('url' => '/account/forget_password', 'id' => 'UserForgetPasswordForm')); ?>
                <fieldset>
                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <?php
                                echo $this->Form->input('email', array(
                                    'label'         => false,
                                    'placeholder'   => __('Email'),
                                    'autocomplete'  => 'off',
                                    'class'         => 'required form-control',
                                    'id'            => 'UserEmailForgetPassword',
                                    'div'           => false,
                                    'value'         => ''
                                ));
                            ?>
                            <i class="ace-icon fa fa-envelope"></i>
                        </span>
                    </label>

                    <div class="space"></div>
                    <div class="g-recaptcha" data-sitekey="6LfPx1sUAAAAAPvDTWoRx_p-m2H3Q4pwxDV_CKmk"></div>
                    <div class="space"></div>

                    <div class="clearfix">
                        <button type="submit" class="width-35 pull-right btn btn-sm btn-danger">
                            <i class="ace-icon fa fa-lightbulb-o"></i>
                            <span class="bigger-110"><?php echo __('Send Me!'); ?></span>
                        </button>
                    </div>
                </fieldset>
            <?php echo $this->Form->end(); ?>
        </div><!-- /.widget-main -->

        <div class="toolbar center">
            <a href="#" data-target="#login-box" class="back-to-login-link">
                <?php echo __("Back to login"); ?>
                <i class="ace-icon fa fa-arrow-right"></i>
            </a>
        </div>
    </div><!-- /.widget-body -->
</div><!-- /.forgot-box -->

<!-- page specific plugin scripts -->
<?php
    
    $inlineJS = <<<EOF
    
        $(document).ready(function(){
            $('form#UserForgetPasswordForm').validate({
                rules: {
                        "data[User][email]": {
                            required: true,
                            email: true
                        }
                }
            });
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>