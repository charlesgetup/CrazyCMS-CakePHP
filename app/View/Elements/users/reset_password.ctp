<div id="reset-password-box" class="forgot-box <?php echo ($this->params['action'] == "resetPassword" && isset($token) && !empty($token)) ? "visible" : ""; ?> widget-box no-border">
    <div class="widget-body">
        <div class="widget-main">
            <h4 class="header blue lighter bigger">
                <i class="ace-icon fa fa-key"></i>
                <?php echo __('Reset Password'); ?>
            </h4>

            <div class="space-6"></div>

            <?php echo $this->Form->create('User', array('url' => DS .'account' .DS .'reset_password' .DS .$token, 'id' => 'UserResetPasswordForm')); ?>
                <fieldset>
                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <?php 
                                echo $this->Form->input('password', array(
                                    'autocomplete'  => 'off',
                                    'placeholder'   => __('Password'),
                                    'class'         => 'required form-control',
                                    'div'           => false,
                                    'label'         => false,
                                    'value'         => '',
                                    'tabindex'      => 1
                                ));
                            ?>
                            <i class="ace-icon fa fa-lock"></i>
                        </span>
                    </label>

                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <?php
                                echo $this->Form->input('password_confirm',array(
                                    'label'         => false,
                                    'placeholder'   => __("Repeat password"),
                                    'type'          => 'password',
                                    'autocomplete'  => 'off',
                                    'class'         => 'required form-control',
                                    'div'           => false,
                                    'value'         => '',
                                    'tabindex'      => 2
                                ));
                            ?>
                            <i class="ace-icon fa fa-retweet"></i>
                        </span>
                    </label>

                    <div class="space"></div>
                    <div class="g-recaptcha" data-sitekey="6LfPx1sUAAAAAPvDTWoRx_p-m2H3Q4pwxDV_CKmk"></div>
                    <div class="space"></div>

                    <div class="clearfix">
                        <button type="submit" class="width-35 pull-right btn btn-sm btn-danger">
                            <i class="ace-icon fa fa-lightbulb-o"></i>
                            <span class="bigger-110"><?php echo __('Submit!'); ?></span>
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
</div><!-- /.reset-password-box -->

<!-- page specific plugin scripts -->
<?php
    
    $inlineJS = <<<EOF
    
        $(document).ready(function(){
            $('form#UserResetPasswordForm').validate({
                rules: {
                        "data[User][password]": {
                            required: true
                        },
                        "data[User][password_confirm]": {
                            required: true,
                            equalTo: "#UserPassword"
                        },
                }
            });
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>