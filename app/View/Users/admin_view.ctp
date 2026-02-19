<div class="row">
    <div class="col-xs-12">
        <div>
            <div id="user-profile-3" class="user-profile row">
                <div class="col-sm-offset-1 col-sm-10">
                
                    <div class="space"></div>

                    <div class="tabbable">
                        <ul class="nav nav-tabs padding-16">
                            <li class="active">
                                <a data-toggle="tab" href="#view-user-details">
                                    <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                                    <?php echo __('User Profile'); ?>
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" href="#view-contact-address">
                                    <i class="purple ace-icon fa fa-flag bigger-125"></i>
                                    <?php echo __('Contact Address'); ?>
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" href="#view-billing-address">
                                    <i class="blue ace-icon fa fa-credit-card bigger-125"></i>
                                    <?php echo __('Billing Address'); ?>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content profile-edit-tab-content">
                            <div id="view-user-details" class="tab-pane in active">
                                <?php
                                    echo $this->element('users/personal_info_view', array(
                                        "user" => $user
                                    )); 
                                ?>
                            </div>

                            <div id="view-contact-address" class="tab-pane">
                                <?php
                                    echo $this->element('users/contact_address_view', array(
                                        "user" => $user
                                    )); 
                                ?>
                            </div>

                            <div id="view-billing-address" class="tab-pane">
                                <?php
                                    echo $this->element('users/billing_address_view', array(
                                        "user" => $user
                                    )); 
                                ?>
                            </div>
                        </div>
                    </div>

                </div><!-- /.span -->
            </div><!-- /.user-profile -->
        </div>
    </div>
</div>