<div id="login-box" class="login-box <?php echo ($this->params['action'] == "admin_login" || $this->params['action'] == "activateAccount") ? "visible" : ""; ?> widget-box no-border">
    <div class="widget-body">
        <div class="widget-main">
            <h4 class="header blue lighter bigger">
                <i class="ace-icon fa fa-coffee green"></i>
                <?php echo __('Please Enter Your Information'); ?>
            </h4>

            <div class="space-6"></div>

            <?php echo $this->Form->create('User', array('url' => '/login', 'id' => 'UserLoginForm')); ?>
                <fieldset>
                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <?php 
                                echo $this->Form->input('email', array(
                                    'autocomplete'  => 'off',
                                    'placeholder'   => __('Email'),
                                    'class'         => 'required form-control',
                                    'id'            => 'UserEmailLogin',
                                    'div'           => false,
                                    'label'         => false,
                                    'value'         => '',
                                ));
                            ?>
                            <i class="ace-icon fa fa-user"></i>
                        </span>
                    </label>

                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <?php 
                                echo $this->Form->input('password', array(
                                    'autocomplete'  => 'off',
                                    'placeholder'   => __('Password'),
                                    'class'         => 'required form-control',
                                    'id'            => 'UserPasswordLogin',
                                    'div'           => false,
                                    'label'         => false,
                                    'value'         => '',
                                ));
                            ?>
                            <i class="ace-icon fa fa-lock"></i>
                        </span>
                    </label>

                    <div class="space"></div>
                    <div class="g-recaptcha" data-sitekey="6LfPx1sUAAAAAPvDTWoRx_p-m2H3Q4pwxDV_CKmk"></div>
                    <div class="space"></div>

                    <div class="clearfix">
                        <?php if(false): ?>
                            <label class="inline">
                                <input type="checkbox" class="ace" />
                                <span class="lbl"> Remember Me</span>
                            </label>
                        <?php endif; ?>

                        <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                            <i class="ace-icon fa fa-key"></i>
                            <span class="bigger-110"><?php echo __("Login");?></span>
                        </button>
                    </div>

                    <div class="space-4"></div>
                </fieldset>
            <?php echo $this->Form->end(); ?>
            
            <?php if(false): ?>
                <div class="social-or-login center">
                    <span class="bigger-110">Or Login Using</span>
                </div>
    
                <div class="space-6"></div>
    
                <div class="social-login center">
                    <a class="btn btn-primary">
                        <i class="ace-icon fa fa-facebook"></i>
                    </a>
    
                    <a class="btn btn-info">
                        <i class="ace-icon fa fa-twitter"></i>
                    </a>
    
                    <a class="btn btn-danger">
                        <i class="ace-icon fa fa-google-plus"></i>
                    </a>
                </div>
            <?php endif; ?>
            
        </div><!-- /.widget-main -->

        <div class="toolbar clearfix">
            <div>
                <a href="#" data-target="#forgot-box" class="forgot-password-link">
                    <i class="ace-icon fa fa-arrow-left"></i>
                    <?php echo __("I forgot my password"); ?>
                </a>
            </div>

            <div>
                <a href="#" data-target="#signup-box" class="user-signup-link">
                    <?php echo __("I want to register"); ?>
                    <i class="ace-icon fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div><!-- /.widget-body -->
</div><!-- /.login-box -->

<!-- page specific plugin scripts -->
<?php
    
    $inlineJS = <<<EOF
    
        $(document).ready(function(){
            $('form#UserLoginForm').validate({
                rules: {
                        "data[User][password]": {
                            required: true
                        },
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