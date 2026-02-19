<div class="slider">
    <div class="camera_wrap camera_azure_skin" id="camera_wrap_1">
        <div data-src="/img/slide-1.jpg">
            <div class="camera_caption fadeIn">
                <div class="slider-text">
                    <div class="container_12">
                        <div class="row">
                            <div class="row_12">
                                <span class="text-1"><?php echo __('<span class="color-2">FULL STACK</span><br> WEB DEVELOPMENT <br>EXPERIENCE'); ?></span>
                                <span class="text-2"><?php echo __('We take you from beginning to success.'); ?></span>
                                <a href="#" class="btn"><?php echo __('read more'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div data-src="/img/slide-2.jpg">
            <div class="camera_caption fadeIn">
                <div class="slider-text">
                    <div class="container_12">
                        <div class="row">
                            <div class="row_12">
                                <span class="text-1"><?php echo __('<span class="color-2">DIGITAL MARKETING</span><br>WITH VARIOUS<br>INNOVATIVE SOLUTIONS'); ?></span>
                                <span class="text-2"><?php echo __('The more you integrate, the more you draw traffic.'); ?></span>
                                <a href="#" class="btn"><?php echo __('read more'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div data-src="/img/slide-3.jpg">
            <div class="camera_caption fadeIn">
                <div class="slider-text">
                    <div class="container_12">
                        <div class="row">
                            <div class="row_12">
                                <span class="text-1"><?php echo __('<span class="color-2">GET TO KNOW</span><br>HOW TO INCREASE <br>YOUR SALES WITH US'); ?></span>
                                <span class="text-2"><?php echo __('Don\'t hesitate to ask, we are always ready to help.'); ?></span>
                                <a href="#" class="btn"><?php echo __('read more'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(window).load(function() {
        
        jQuery('#camera_wrap_1').camera({
            height: '39.5%',
            playPause: false,
            time: 8000,
            transPeriod: 1000,
            fx: 'simpleFade',
            loader: 'none',
            minHeight:'150px',
            navigation: false,
            pagination: true,
        });
        
    });
</script>