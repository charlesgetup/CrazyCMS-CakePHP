<!-- Alter Email Marketing Plans Popup -->
<div id="alterLiveChatPlan" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="alterLiveChatPlanLabel" aria-hidden="true" style="display:none;z-index:1050;width:1001px;height:600px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="alterLiveChatPlanLabel">Switch Email Marketing Plan</h3>
    </div>
    <div class="modal-body" style="max-height:565px;overflow:hidden;">
        <iframe src="/live_chat/live_chat_plans/alter/<?php echo $planId; ?>" frameborder="0" scrolling="no" seamless="seamless" width="100%" id="alter-live-chat-plan" onLoad="adjustIframeAndModalWindow('alter-live-chat-plan');"></iframe>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div> 
</div>