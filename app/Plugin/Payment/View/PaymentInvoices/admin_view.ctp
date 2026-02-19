<?php
    $invoiceHTML = $this->element('invoices/invoice_template', array(
        'invoice'           => $invoice,
        'companyAddress'    => $companyAddress,
        'companyName'       => $companyName
    )); 
    $invoiceHTML = UtilComponent::sanitizeHTML($invoiceHTML);
    $invoiceHTML = preg_replace('/^\s+|\n|\r|\s+$/m', '', addcslashes($invoiceHTML, '"\\/'));
?>

<div id="preview-invoice-area" style="width: 100%; overflow-x:hidden;">
    <iframe frameborder="0" scrolling="no" seamless="seamless"></iframe>
</div>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
    
        $('#preview-invoice-area').children('iframe:first')
                .css({'min-height':'1100px','width':'100%','min-width':'768px'}).get(0)
                .contentWindow.document.write("{$invoiceHTML}");
        $('#preview-invoice-area').children('iframe:first').css({'height': $('#preview-invoice-area').children('iframe:first').contents().find("body").outerHeight()+'px'});
        $('#preview-invoice-area').children('iframe:first').contents().find(".col-sm-offset-1").css({'margin-left': '0'});
        var bootbox = $(window.parent.document).find('.bootbox.modal').filter(function(){ return $(this).css('display') == 'block'; }).first();
        bootbox.children('.modal-dialog').css({'min-width': '768px'});
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    ));
?>