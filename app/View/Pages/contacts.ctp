<?php
    echo $this->Html->css(array('contacts'));
    echo $this->fetch('css');
    
    echo $this->Html->script(array('forms'));
    echo $this->fetch('script');
?>
<script>
    jQuery(window).load(function() {
        jQuery('form').forms({
            ownerEmail:'#'
        });
    });
</script>
<div class="container_12 pad-bot">
    <div class="row">
        <div class="map_wrapper grid_7 suffix_1">
            <h2>How to Find Us</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13260.561946620126!2d151.06251316028275!3d-33.80868747728547!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12a45e102673f3%3A0xe4f3d4043521b63e!2s19+Wharf+Rd%2C+Melrose+Park+NSW+2114!5e0!3m2!1sen!2sau!4v1550216662635" width="540" height="335" frameborder="0" style="border:0" allowfullscreen></iframe>
            <div class="clearfix">
                <h3><?php echo $companyName; ?></h3>
                <div class="address">
                    <dl>    
                        <dt>
                            <?php echo $companyAddress; ?>
                        </dt>
                        <?php if(false): ?>
                            <dd><span>Freephone:</span>+1 800 559 6580</dd>
                            <dd><span>Telephone:</span>+1 800 603 6035</dd>
                            <dd><span>FAX:</span>+1 800 889 9898</dd>
                        <?php endif; ?>
                        <dd><span class="e-mail">E-mail: <a href="mailto:<?php echo $companyEmail; ?>" class="demo"><?php echo $companyEmail; ?></a></span></dd>
                    </dl>
                </div>
                <?php if(false): ?>
                    <div class="address">
                        <dl>    
                            <dt>9863 - 9867 Mill Road,<br> 
                                Cambridge, MG09 99HT
                            </dt>
                            <dd><span>Freephone:</span>+1 800 559 6580</dd>
                            <dd><span>Telephone:</span>+1 800 603 6035</dd>
                            <dd><span>FAX:</span>+1 800 889 9898</dd>
                            <dd><span class="e-mail">E-mail: <a href="#" class="demo">mail&#64;demolink.org</a></span></dd>
                        </dl>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <article class="grid_4">
            <h2>Contact Form</h2>
            <form id="contact-form">
                <div class="success">Contact form submitted!<br>
                    <strong>We will be in touch soon.</strong>
                </div>
                <fieldset>
                    <label class="name">
                        <input type="text" value="Name:" id="author" name="author" />
                        <span class="error">*This is not a valid name.</span> 
                        <span class="empty">*This field is required.</span>
                    </label>
                    <label class="email">
                        <input type="email" value="E-mail:" id="email" name="email" />
                        <span class="error">*This is not a valid email address.</span> 
                        <span class="empty">*This field is required.</span>
                    </label>
                    <label class="phone">
                        <input type="tel" value="Phone:" id="phone" name="phone" />
                        <span class="error">*This is not a valid phone number.</span> 
                        <span class="empty">*This field is required.</span>
                    </label>
                    <label class="message">
                        <textarea id="text" name="text">Message:</textarea>
                        <span class="error">*The message is too short.</span> 
                        <span class="empty">*This field is required.</span>
                    </label>
                    <div class="form_buttons">
                        <a href="#" class="btn v1" data-type="reset">Clear</a>
                        <a href="#" class="btn v1" data-type="submit">send</a>
                    </div>
                </fieldset>
            </form>
        </article>
    </div>
</div>