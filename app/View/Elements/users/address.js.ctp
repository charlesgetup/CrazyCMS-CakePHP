<?php
    $lineBreak = '\n\n';
    $setAddress0 = (isset($this->request->data["Address"][0]["same_as"]) && $this->request->data["Address"][0]["same_as"] == 1) ? "set_address(0, true);" : "";
    $setAddress1 = (isset($this->request->data["Address"][1]["same_as"]) && $this->request->data["Address"][1]["same_as"] == 1) ? "set_address(1, true);" : "";
    $setAddrMsg = __("The existing address will be replaced or removed if you choose to use the other address instead." .$lineBreak .'After you choose "Yes", if you changed your mind and wanted to use the previous address, do not click "Save User Profile", just simply refresh the page and the previous address will appear.');
    $inlineJS = <<<EOF
        var set_address = function(address_order, is_same_as){
            var address_type = (address_order == 1) ? "Contact" : "Billing";
            if(is_same_as){
                var copyAddreeeOrder = (address_order == 1) ? 0 : 1;
                $("#Address"+address_order+"StreetAddress").attr({
                    "placeholder": "",
                    "disabled": false,
                    "required": "required"
                }).val($("#Address"+copyAddreeeOrder+"StreetAddress").val());
                $("#Address"+address_order+"Suburb").attr({
                    "placeholder": "",
                    "disabled": false,
                    "required": "required"
                }).val($("#Address"+copyAddreeeOrder+"Suburb").val());
                $("#Address"+address_order+"State").attr({
                    "placeholder": "",
                    "disabled": false,
                    "required": "required"
                }).val($("#Address"+copyAddreeeOrder+"State").val());
                $("#Address"+address_order+"Postcode").attr({
                    "placeholder": "",
                    "disabled": false,
                    "required": "required"
                }).val($("#Address"+copyAddreeeOrder+"Postcode").val());
                $("#Address"+address_order+"CountryId").attr("disabled", false).children("option").each(function(){
                    if($(this).attr("value") == "13" && !$("#Address"+copyAddreeeOrder+"CountryId").val()){
                        $(this).attr({"selected": "selected"});
                    }else if($(this).attr("value") == $("#Address"+copyAddreeeOrder+"CountryId").val() && $("#Address"+copyAddreeeOrder+"CountryId").val()){
                        $(this).attr({"selected": "selected"});
                    }else{
                        $(this).attr({"selected": false});
                    }
                });
            }else{
                $("#Address"+address_order+"StreetAddress").attr({
                    "placeholder": "",
                    "disabled": false,
                    "required": "required"
                }).val("");
                $("#Address"+address_order+"Suburb").attr({
                    "placeholder": "",
                    "disabled": false,
                    "required": "required"
                }).val("");
                $("#Address"+address_order+"State").attr({
                    "placeholder": "",
                    "disabled": false,
                    "required": "required"
                }).val("");
                $("#Address"+address_order+"Postcode").attr({
                    "placeholder": "",
                    "disabled": false,
                    "required": "required"
                }).val("");
                $("#Address"+address_order+"CountryId").attr("disabled", false).children("option").each(function(){
                    if($(this).attr("value") == "13"){
                        $(this).attr({"selected": "selected"});
                    }else{
                        $(this).attr({"selected": false});
                    }
                });
            }
        };
        var address_change_handler = function(address_order, show_error){
            var address_type   = (address_order == 0) ? "contact" : "billing";
            var opposite_order = (address_order == 0) ? 1 : 0;
            var tab_order      = (address_order == 0) ? 2 : 1;
                
            set_address(address_order, true);
            if(show_error == true){
                messageBox({"status":WARNING, "message": "Please fill in " + address_type + " address details."});
            }
            if($('#Address' + opposite_order + 'SameAs').is(':checked')){
                $('#Address' + opposite_order + 'SameAs').attr('checked', false);
            }
        };
            
        $('#Address0SameAs').change(function(){
            if($(this).is(":checked")){
                if($('#Address0StreetAddress').val()){
                    var r = confirm('{$setAddrMsg}');
                    if (r == true){
                        if(!$('#Address1StreetAddress').val()){
                            address_change_handler(0, true);
                        }else{
                            address_change_handler(0, false);
                        }
                    }else{
                        $(this).attr("checked", false);
                    }
                }else{
                    address_change_handler(0, false);
                }
            }else{
                set_address(0,false);
            }
        });
        $('#Address1SameAs').change(function(){
            if($(this).is(":checked")){
                if($('#Address1StreetAddress').val()){
                    var r = confirm('{$setAddrMsg}');
                    if (r == true){
                        if(!$('#Address0StreetAddress').val()){
                            address_change_handler(1, true);
                        }else{
                            address_change_handler(1, false);
                        }
                    }else{
                        $(this).attr("checked", false);
                    }
                }else{
                    address_change_handler(1, false);
                }
            }else{
                set_address(1,false);
            }
        });
        
        {$setAddress0}
        {$setAddress1}
            
        /* Keep address tab the same style */
        var wi = $(window).width();
        if(wi <= 768 && $(document).find("div#edit-settings").length){
            $(document).find("div#edit-settings:first").find("div.col-xs-12.col-sm-6").each(function(){
                $(this).removeClass("col-xs-12").addClass("col-xs-6");
            });
            $(document).find("div#edit-settings:first").find("div.space-10:first").removeClass("space-10");
            $($(document).find("div#edit-settings:first").find("div.space").get(1)).removeClass("space").addClass("space-10");
        }
EOF;

    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    ));
?>