<ul class="nav nav-tabs" id="email-marketing-campaign-view">
    <li class="active"><a href="#view-email-marketing-campaign-details">Campaign Details</a></li>
    <li><a href="#view-email-marketing-campaign-history">Statistics</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="view-email-marketing-campaign-details">
        <?php
            echo $this->element('campaigns/email_marketing_campaign_view', array(
                "campaign" => $campaign
            )); 
        ?>
    </div>
    <!--div class="tab-pane" id="view-email-marketing-campaign-subscribers">
        <iframe src="/email_marketing/email_marketing_statistics/view/<?php echo $campaign['EmailMarketingCampaign']['id']; ?>" frameborder="0" scrolling="no" seamless="seamless" width="100%" height="100%" id="manage-email-marketing-campaign-statistics-table" onLoad="adjustIframeAndModalWindow('manage-email-marketing-campaign-statistics-table');"></iframe>
    </div-->
</div>
<script>
    $(function () {
        $('div#spinner, window.parent.document').css({"display":"none"});
        
        $('#email-marketing-campaign-view a').on('click',function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>