<div id="signup-box" class="signup-box <?php echo ($this->params['action'] == "register") ? "visible" : ""; ?> widget-box no-border">
    <div class="widget-body">
        <div class="widget-main">
            <h4 class="header green lighter bigger">
                <i class="ace-icon fa fa-users blue"></i>
                <?php echo __('New User Registration'); ?>
            </h4>

            <div class="space-6"></div>

            <?php echo $this->Form->create('User', array('url' => '/register', 'id' => 'UserRegisterForm')); ?>
                <fieldset>
                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <?php
                                echo $this->Form->input('email', array(
                                    'label'         => false,
                                    'placeholder'   => __("Email"),
                                    'autocomplete'  => 'off',
                                    'class'         => 'required form-control',
                                    'id'            => 'UserEmailRegister',
                                    'div'           => false,
                                    'value'         => '',
                                    'tabindex'      => 1
                                ));
                            ?>
                            <i class="ace-icon fa fa-envelope"></i>
                        </span>
                    </label>

                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <?php
                                echo $this->Form->input('password', array(
                                    'label'         => false,
                                    'placeholder'   => __("Password"),
                                    'autocomplete'  => 'off',
                                    'class'         => 'required form-control',
                                    'id'            => 'UserPasswordRegister',
                                    'div'           => false,
                                    'value'         => '',
                                    'tabindex'      => 2
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
                                    'id'            => 'UserPasswordConfirmRegister',
                                    'div'           => false,
                                    'value'         => '',
                                    'tabindex'      => 3
                                ));
                            ?>
                            <i class="ace-icon fa fa-retweet"></i>
                        </span>
                    </label>

                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <?php
                                echo $this->Form->input('service_id', array(
                                    'type'          => 'select',
                                    'options'       => $services,
                                    'class'         => 'required form-control',
                                    'div'           => false,
                                    'empty'         => __('Choose service'),
                                    'label'         => false
                                ));
                            ?>
                        </span>
                    </label>

                    <label class="block clearfix">
                        <input type="checkbox" class="ace required" id="agreement" name="data[User][agreement]" required="required" aria-required="true" value="1" />
                        <span class="lbl">
                            <?php echo __('I accept the'); ?>
                            <a href="#"><?php echo __('User Agreement'); ?></a>
                        </span>
                    </label>

                    <div class="space"></div>
                    <div class="g-recaptcha" data-sitekey="6LfPx1sUAAAAAPvDTWoRx_p-m2H3Q4pwxDV_CKmk"></div>
                    <div class="space"></div>

                    <div class="clearfix">
                        <button type="reset" class="width-30 pull-left btn btn-sm">
                            <i class="ace-icon fa fa-refresh"></i>
                            <span class="bigger-110"><?php echo __('Reset'); ?></span>
                        </button>

                        <button type="submit" class="width-65 pull-right btn btn-sm btn-success">
                            <span class="bigger-110"><?php echo __('Register'); ?></span>

                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                        </button>
                    </div>
                </fieldset>
            <?php echo $this->Form->end(); ?>
        </div>

        <div class="toolbar center">
            <a href="#" data-target="#login-box" class="back-to-login-link">
                <i class="ace-icon fa fa-arrow-left"></i>
                <?php echo __("Back to login"); ?>
            </a>
        </div>
    </div><!-- /.widget-body -->
</div><!-- /.signup-box -->

<!-- page specific plugin scripts -->
<?php
    
    $inlineJS = <<<EOF
    
        $(document).ready(function(){
            $('form#UserRegisterForm').validate({
                rules: {
                        "data[User][email]": {
                            required: true,
                            email: true
                        },
                        "data[User][password]": {
                            required: true
                        },
                        "data[User][password_confirm]": {
                            required: true,
                            equalTo: "#UserPasswordRegister"
                        },
                        "data[User][service_id]": {
                            required: true
                        },
                        "data[User][agreement]": {
                            required: true
                        }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("id") == "agreement") {
                        error.insertAfter('body'); // Try not to display the error <label>
                    }
               }
            });
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>