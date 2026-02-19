setTimeout(function(){
    if ('parentIFrame' in window){ 
        window.parentIFrame.sendMessage({"action":"SWITCH_ADD_EDIT_TITLE","data":"<?php echo $this->request->params['action']; ?>"}); 
    }else{
        var win = findParentWindowHasParentIFrameAttr();
        if ('parentIFrame' in win){
            win.parentIFrame.sendMessage({"action":"SWITCH_ADD_EDIT_TITLE","data":"<?php echo $this->request->params['action']; ?>", "sourceIframeId": window.frameElement.id});
        }else{
            /* If cannot find a window which has "parentIFrame" (iFrameResize) attribute, close the popup bootbox by triggering the "onEscape" event. */
            setTimeout(function(){
                actuateLink($(window.frameElement).closest(".modal-body").siblings(".modal-header").children(".bootbox-close-button.close"));
            }, 4000);
        }
    }
}, 500);