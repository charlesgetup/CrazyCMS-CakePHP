<?php
App::uses('AclComponent', 'Controller/Component');
App::uses('CrazySecurityComponent', 'Controller/Component');
class JqueryDataTableHelper extends AppHelper{

    var $helpers = array('Html', 'ExtendedHtml', 'Session', 'Permissions', 'Minify');

    /**
     * Generate data table view
     * @param string $tableModelName
     * @param array  $tableDisplayFields
     * @param string $ajaxSourceUrl
     * @param array  $tableActions
     * 		Note: this action array can contain a special "add" action URL in string format if the add action URL need to pass some spacial parameter(s).
     * 			  And the rest actions can be sent using normal CakePHP way. Now only the folliwing actions can be used within the data table, "View", "Add", "Edit", "Delete" and "Filter".
     * 			  And "Filter" action is used internally, it cannot be customised by developer, it uses data table API.
     * @param string $noItemsMessage
     * @param string $defaultSortDir
     * @param string $cakePHPPlugin
     * 		Note: this param is used to pass CakePHP plugin name to the action URL
     * @param string $hasSelectionBox
     * @param string $paginate
     * @param number $paginatePageLimit
     * @return string
     */
    public function createTable($tableModelName, $tableDisplayFields, $ajaxSourceUrl, $tableActions = array(), $noItemsMessage = "There are no items to display", $defaultSortDir = "asc", $cakePHPPlugin = null, $hasSelectionBox = true, $paginate = true, $paginatePageLimit = 10){

        // Create header
        $output = '';
        $uniqueClass = 'id-' .time() .rand(100, 10000);

        // We need to give enough space above the table for the tool bar tooltips when data table is embeded in an iframe
        if(isset($this->_View->viewVars['loadInIframe']) && $this->_View->viewVars['loadInIframe'] === TRUE){
        	$output .= '<div class="space" style="margin: 12px 0 !important;"></div>';
        }

        $output .= '<div class="clearfix">';
        $output .= '	<div class="pull-right tableTools-container">';
        $output .= '		<div class="nav-search" id="nav-search">';
        $output .= '			<form class="form-search">';
        $output .= '				<span class="input-icon">';
        $output .= '					<input type="text" placeholder="' .__('Search ...') .'" class="nav-search-input" id="nav-search-input" autocomplete="off">';
        $output .= '					<i class="ace-icon fa fa-search nav-search-icon"></i>';
        $output .= '				</span>';
        $output .= '			</form>';
        $output .= '		</div>';
        $output .= '		<a class="dt-button btn btn-white btn-primary btn-bold search" tabindex="0">';
        $output .= '			<span><i class="fa fa-search bigger-110 purple"></i> <span class="hidden">' .__('Search table') .'</span></span>';
        $output .= '		</a>';
        $output .= '	</div>';
        $output .= '</div>';
        $output .= '<div>';
        $output .= "	<table id='" .$tableModelName ."-data-table' class='table table-striped table-bordered table-hover " .$uniqueClass ."'>";
        $output .= "		<thead>";
        $output .= "			<tr>";
        if($hasSelectionBox){
	        $output .= "			<th class=\"center\">";
	        $output .= "				<label class=\"pos-rel\">";
	        $output .= "					<input type=\"checkbox\" class=\"ace\" />";
	        $output .= "					<span class=\"lbl\"></span>";
	        $output .= "				</label>";
	        $output .= "			</th>";
        }

        if(is_array($tableDisplayFields) && !empty($tableDisplayFields)){
        	$userGroupName = $this->Session->read('Auth.User.Group.name');
	        foreach($tableDisplayFields as $tableDisplayFieldName => $tableDisplayField){
	        	if(isset($tableDisplayField['RestrictToGroups'])){
	        		if(!is_array($tableDisplayField['RestrictToGroups'])){
	        			$tableDisplayField['RestrictToGroups'] = array($tableDisplayField['RestrictToGroups']);
	        		}
	        		if(!in_array($userGroupName, $tableDisplayField['RestrictToGroups'])){
	        			continue;
	        		}
	        	}
	            $output .= "		<th>" . $tableDisplayField['ColumnName'] . "</th>";
	        }
        }

        $hasActions = (!empty($tableActions));
        if ($hasActions){
            $output .= "			<th></th>";
        }
        $output .= "				<th></th>";
        $output .= "			</tr>";
        $output .= "		</thead>";

        // Create entries
        $output .= "		<tbody>";
        $output .= "		</tbody>";
        $output .= "	</table>";
        $output .= '</div>';

        // Jquery DataTable
        $combineDataJs 		= array();
        $uniqueTableVarName = 'table_' .time() .rand(100, 10000);
        $output .= '<script type="text/javascript">';
        $inlineJavaScript = '		var loadDataTable_' .$uniqueTableVarName .' = function() {';
        $inlineJavaScript .= '		jQuery(function($) {';
        $inlineJavaScript .= '			var updateSecurityToken = function(event){';
        $inlineJavaScript .= '				$.ajaxSetup({';
        $inlineJavaScript .= '					headers: {"X-CSRF-Token" : window.getCookie(\'' .$this->_View->viewVars['csrfCookieName'] .'\')}';
        $inlineJavaScript .= '				});';
        $inlineJavaScript .= '			};';
        $inlineJavaScript .= '			var ' .$uniqueTableVarName .' = $("#' .$tableModelName .'-data-table.' .$uniqueClass .'").DataTable({';
        $inlineJavaScript .= '				"bAutoWidth": false,';
        $inlineJavaScript .= '				"bProcessing": true,';
        $inlineJavaScript .= '				"bServerSide": true,';
        $inlineJavaScript .= '				"bStateSave": false, ';
        $inlineJavaScript .= '				"bRegex": true, ';
        $inlineJavaScript .= '				"deferRender": true,';
        $inlineJavaScript .= '				"responsive": {';
        $inlineJavaScript .= '					"details": {';
        $inlineJavaScript .= '						"type": "column",';
        $inlineJavaScript .= '						"target":   -1';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '				},';
        $inlineJavaScript .= '				"select": {';
        $inlineJavaScript .= '					"style": "multi"';
        $inlineJavaScript .= '				},';
        $inlineJavaScript .= '				"sAjaxSource": "' .$ajaxSourceUrl .'",';
        $inlineJavaScript .= '				"sDom": \'<"row"<"col-xs-6"l><"col-xs-6"i>r>t<"row"<"#action_' .$uniqueTableVarName .'.col-xs-6"<"table-actions">><"col-xs-6"p>>\',';
        if($paginate){
        	$inlineJavaScript .= '			"iDisplayLength": ' .$paginatePageLimit .',';
        }
        $inlineJavaScript .= '				"aoColumns": [';
        if($hasSelectionBox){
        	$inlineJavaScript .= '				{"mData":"' .$tableModelName .'.selection", "bSortable": false, "bSearchable": false, "sClass": "center"},';
        }

        $actualDataColumns = 0;
        if(is_array($tableDisplayFields) && !empty($tableDisplayFields)){
        	if(empty($userGroupName)) { $userGroupName = $this->Session->read('Auth.User.Group.name'); }
        	foreach ($tableDisplayFields as $tableDisplayFieldName => $tableDisplayField){
        		if(isset($tableDisplayField['RestrictToGroups'])){
        			if(!is_array($tableDisplayField['RestrictToGroups'])){
        				$tableDisplayField['RestrictToGroups'] = array($tableDisplayField['RestrictToGroups']);
        			}
					if(!in_array($userGroupName, $tableDisplayField['RestrictToGroups'])){
						continue;
					}
        		}

        		$inlineJavaScript .= '			{"mData":"' .$tableDisplayFieldName .'"' .(empty($tableDisplayField['Sortable']) ? ', "bSortable": false' : ', "bSortable": true') .(empty($tableDisplayField['Searchable']) ? ', "bSearchable": false' : ', "bSearchable": true') .'},';
        		if(isset($tableDisplayField['CombineFields']) && is_array($tableDisplayField['CombineFields']) && !empty($tableDisplayField['CombineFields'])){
        			$combineJS = 'data.aaData[i].' .$tableDisplayFieldName .' = ';
        			$combineFields = array();
        			foreach($tableDisplayField['CombineFields'] as $cf){
        				array_push($combineFields, 'data.aaData[i].' .$cf);
        			}
        			$combineJS .= implode(' + ' .$tableDisplayField['CombineGlue'] .' + ', $combineFields) .';';
        			array_push($combineDataJs, $combineJS);
        		}
        		$actualDataColumns++;
        	}
        }
        if ($hasActions){
        	$inlineJavaScript .= '				{"mData":"' .$tableModelName .'.actions", "bSortable": false, "bSearchable": false, "sClass": "all"},'; // Always display action field at very beginning to let all action button to be initialised
        }
        $inlineJavaScript .= '					{"mData":"' .$tableModelName .'.responsiveControl", "bSortable": false, "bSearchable": false, "sClass": "center control"}';
        $inlineJavaScript .= '				],';
        $inlineJavaScript .= '				"fnDrawCallback": function (oSettings) {';
        $inlineJavaScript .= '					var dataTableObjForInternalUse = this;';
        $inlineJavaScript .= '					if($("a.popup-view").length){';
        $inlineJavaScript .= '						$("a.popup-view").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var iframeUrl = $(event.target).closest("a").attr("data-link");';
        $inlineJavaScript .= '							var title = "View ' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .'";'; // View popup can view anything
        $inlineJavaScript .= '							var classList = $(event.target).closest("a").prop("className").split(" ");'; // If not view its own model data, need to specify the view title
        $inlineJavaScript .= '							if(classList.length){';
        $inlineJavaScript .= '								for(var classIndex = 0; classIndex < classList.length; classIndex++){';
        $inlineJavaScript .= '									if(classList[classIndex].startsWith("title-")){';
        $inlineJavaScript .= '										title = classList[classIndex].replace("title-", "").toCamelCase().camelCaseToWords();';
        $inlineJavaScript .= '										break;';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							}';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: \'<iframe src="\'+iframeUrl+\'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
        $inlineJavaScript .= '								title: title,';
        $inlineJavaScript .= '								buttons: {';
        $inlineJavaScript .= '									"OK" : {';
        $inlineJavaScript .= '										"label" : "' .__("OK") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-primary"';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '					if($("a.popup-edit").length || $("a.popup-add").length){';
        $inlineJavaScript .= '						$("a.popup-edit, a.popup-add").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var iframeUrl = $(event.target).closest("a").attr("data-link");';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: \'<iframe src="\'+iframeUrl+\'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
        $inlineJavaScript .= '								title: ($(event.target).closest("a").hasClass("popup-add") ? "Add" : "Edit")+" ' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .'",';
        $inlineJavaScript .= '								onEscape: function(event) {';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.ajax.reload();';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.order( [ 1, "' .strtolower($defaultSortDir) .'" ] ).draw();';
        $inlineJavaScript .= '								},';
        $inlineJavaScript .= '								buttons: {';
        $inlineJavaScript .= '									"Submit" : {';
        $inlineJavaScript .= '										"label" : "' .__("Submit") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-success submit-iframe-form-btn",';
        $inlineJavaScript .= '										"callback" : function(event){';
        $inlineJavaScript .= '											submitIframeForm(event);';
        $inlineJavaScript .= '											return false;';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									},';
        $inlineJavaScript .= '									"Reset" : {';
        $inlineJavaScript .= '										"label" : "' .__("Reset") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-danger reset-iframe-form-btn",';
        $inlineJavaScript .= '										"callback" : function(event){';
        $inlineJavaScript .= '											resetIframeForm(event);';
        $inlineJavaScript .= '											return false;';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									},';
        $inlineJavaScript .= '									"Cancel" : {';
        $inlineJavaScript .= '										"label" : "' .__("Cancel") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-sm"';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '					if($("a.popup-import").length){';
        $inlineJavaScript .= '						$("a.popup-import").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var iframeUrl = $(event.target).closest("a").attr("data-link");';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: \'<iframe src="\'+iframeUrl+\'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
        $inlineJavaScript .= '								title: "Import ' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .'",';
        $inlineJavaScript .= '								onEscape: function(event) {';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.ajax.reload();';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.order( [ 1, "' .strtolower($defaultSortDir) .'" ] ).draw();';
        $inlineJavaScript .= '								},';
        $inlineJavaScript .= '								buttons: {';
        $inlineJavaScript .= '									"Submit" : {';
        $inlineJavaScript .= '										"label" : "' .__("Submit") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-success submit-iframe-form-btn",';
        $inlineJavaScript .= '										"callback" : function(event){';
        $inlineJavaScript .= '											submitIframeForm(event);';
        $inlineJavaScript .= '											return false;';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									},';
        $inlineJavaScript .= '									"Reset" : {';
        $inlineJavaScript .= '										"label" : "' .__("Reset") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-danger reset-iframe-form-btn",';
        $inlineJavaScript .= '										"callback" : function(event){';
        $inlineJavaScript .= '											resetIframeForm(event);';
        $inlineJavaScript .= '											return false;';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									},';
        $inlineJavaScript .= '									"Cancel" : {';
        $inlineJavaScript .= '										"label" : "' .__("Cancel") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-sm"';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '					if($("a.popup-delete").length){';
        $inlineJavaScript .= '						$("a.popup-delete").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var iframeUrl = $(event.target).closest("a").attr("data-link");';
        $inlineJavaScript .= '							var isBatchAction = $(event.target).closest("a").hasClass("batch-action");';
        $inlineJavaScript .= '							var batchDeletedIds = new Array();';
        $inlineJavaScript .= '							if(isBatchAction){';
        $inlineJavaScript .= '								var selectedObjs = ' .$uniqueTableVarName .'.rows(".selected").data();';
        $inlineJavaScript .= '								if(selectedObjs == null || selectedObjs == "" || selectedObjs.length < 1){';
        $inlineJavaScript .= '									messageBox({"status": ERROR, "message": "' .__("Please make some selections first.") .'"});';
        $inlineJavaScript .= '									return false;';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '								$.each(selectedObjs, function(){';
        $inlineJavaScript .= '									batchDeletedIds.push(this.' .$tableModelName .'.id);';
        $inlineJavaScript .= '								});';
        $inlineJavaScript .= '							}';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: \'<iframe src="\'+iframeUrl+\'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
        $inlineJavaScript .= '								title: (isBatchAction ? "Delete Selected ' .__(Inflector::pluralize(Inflector::humanize(Inflector::underscore($tableModelName)))) .'" : "Delete ' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .'"),';
        $inlineJavaScript .= '								onEscape: function(event) {';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.ajax.reload();';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.order( [ 1, "' .strtolower($defaultSortDir) .'" ] ).draw();';
        $inlineJavaScript .= '								},';
        $inlineJavaScript .= '								buttons: {';
        $inlineJavaScript .= '									"Confirmed" : {';
        $inlineJavaScript .= '										"label" : "' .__("Confirmed") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-success submit-iframe-form-btn",';
        $inlineJavaScript .= '										"callback" : function(event){';
        $inlineJavaScript .= '											if(isBatchAction && batchDeletedIds.length > 0){';
        $inlineJavaScript .= '												updateSecurityToken(event);';
        $inlineJavaScript .= '												$.ajax({';
        $inlineJavaScript .= '													async: false,';
        $inlineJavaScript .= '													cache: false,';
        $inlineJavaScript .= '													url: iframeUrl,';
        $inlineJavaScript .= '													type: "POST",';
        $inlineJavaScript .= '													data: {"batchIds": batchDeletedIds},';
        $inlineJavaScript .= '													beforeSend: function ( xhr ) { /* Do nothing for now */ },';
        $inlineJavaScript .= '												}).done(function ( data ) {';
        $inlineJavaScript .= '													window.location.reload(); /* Reload window to show flash message */';
        $inlineJavaScript .= '												}).fail(function(jqXHR, textStatus, errorThrown) {';
        $inlineJavaScript .= '													ajaxErrorHandler(jqXHR, textStatus, errorThrown);';
        $inlineJavaScript .= '												}).always(function() { ';
        $inlineJavaScript .= '													/* Do nothing for now */';
        $inlineJavaScript .= '												});';
        $inlineJavaScript .= '											}else{';
        $inlineJavaScript .= '												submitIframeForm(event.originalEvent);';
        $inlineJavaScript .= '												setTimeout(function(){actuateLink($(event.target).closest(".modal-footer").siblings(".modal-header").children(".bootbox-close-button.close"));}, 4000); /* Trigger close popup */';
        $inlineJavaScript .= '											}';
        $inlineJavaScript .= '											return false;';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									},';
        $inlineJavaScript .= '									"Cancel" : {';
        $inlineJavaScript .= '										"label" : "' .__("Cancel") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-sm"';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '					if($("a.popup-email").length){';
        $inlineJavaScript .= '						$("a.popup-email").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var iframeUrl = $(event.target).closest("a").attr("data-link");';
        $inlineJavaScript .= '							var isBatchAction = $(event.target).closest("a").hasClass("batch-action");';
        $inlineJavaScript .= '							var batchEmailIds = new Array();';
        $inlineJavaScript .= '							if(isBatchAction){';
        $inlineJavaScript .= '								var selectedObjs = ' .$uniqueTableVarName .'.rows(".selected").data();';
        $inlineJavaScript .= '								if(selectedObjs == null || selectedObjs == "" || selectedObjs.length < 1){';
        $inlineJavaScript .= '									messageBox({"status": ERROR, "message": "' .__("Please make some selections first.") .'"});';
        $inlineJavaScript .= '									return false;';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '								$.each(selectedObjs, function(){';
        $inlineJavaScript .= '									batchEmailIds.push(this.' .$tableModelName .'.id);';
        $inlineJavaScript .= '								});';
        $inlineJavaScript .= '							}';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: \'<iframe src="\'+iframeUrl+\'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
        $inlineJavaScript .= '								title: (isBatchAction ? "Email Selected ' .__(Inflector::pluralize(Inflector::humanize(Inflector::underscore($tableModelName)))) .'" : "Email ' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .'"),';
        $inlineJavaScript .= '								onEscape: function(event) {';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.ajax.reload();';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.order( [ 1, "' .strtolower($defaultSortDir) .'" ] ).draw();';
        $inlineJavaScript .= '								},';
        $inlineJavaScript .= '								buttons: {';
        $inlineJavaScript .= '									"Send" : {';
        $inlineJavaScript .= '										"label" : (isBatchAction ? "' .__("Confirm & Send emails") .'" : "' .__("Confirmed & Send email") .'"),';
        $inlineJavaScript .= '										"className" : "btn-sm btn-success submit-iframe-form-btn",';
        $inlineJavaScript .= '										"callback" : function(event){';
        $inlineJavaScript .= '											if(isBatchAction && batchEmailIds.length > 0){';
        $inlineJavaScript .= '												updateSecurityToken(event);';
        $inlineJavaScript .= '												$.ajax({';
        $inlineJavaScript .= '													async: false,';
        $inlineJavaScript .= '													cache: false,';
        $inlineJavaScript .= '													url: iframeUrl,';
        $inlineJavaScript .= '													type: "POST",';
        $inlineJavaScript .= '													data: {"batchIds": batchEmailIds},';
        $inlineJavaScript .= '													beforeSend: function ( xhr ) { /* Do nothing for now */ },';
        $inlineJavaScript .= '												}).done(function ( data ) {';
        $inlineJavaScript .= '													window.location.reload(); /* Reload window to show flash message */';
        $inlineJavaScript .= '												}).fail(function(jqXHR, textStatus, errorThrown) {';
        $inlineJavaScript .= '													ajaxErrorHandler(jqXHR, textStatus, errorThrown);';
        $inlineJavaScript .= '												}).always(function() { ';
        $inlineJavaScript .= '													/* Do nothing for now */';
        $inlineJavaScript .= '												});';
        $inlineJavaScript .= '											}else{';
        $inlineJavaScript .= '												submitIframeForm(event);';
        $inlineJavaScript .= '												setTimeout(function(){actuateLink($(event.target).closest(".modal-footer").siblings(".modal-header").children(".bootbox-close-button.close"));}, 4000); /* Trigger close popup */';
        $inlineJavaScript .= '											}';
        $inlineJavaScript .= '											return false;';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									},';
        $inlineJavaScript .= '									"Cancel" : {';
        $inlineJavaScript .= '										"label" : "' .__("Close") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-sm"';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '					if($("a.popup-pay").length){';
        $inlineJavaScript .= '						$("a.popup-pay").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var iframeUrl = $(event.target).closest("a").attr("data-link");';
        $inlineJavaScript .= '							var isBatchAction = $(event.target).closest("a").hasClass("batch-action");';
        $inlineJavaScript .= '							var batchPayIds = new Array();';
        $inlineJavaScript .= '							if(isBatchAction){';
        $inlineJavaScript .= '								var selectedObjs = ' .$uniqueTableVarName .'.rows(".selected").data();';
        $inlineJavaScript .= '								if(selectedObjs == null || selectedObjs == "" || selectedObjs.length < 1){';
        $inlineJavaScript .= '									messageBox({"status": ERROR, "message": "' .__("Please make some selections first.") .'"});';
        $inlineJavaScript .= '									return false;';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '								$.each(selectedObjs, function(){';
        $inlineJavaScript .= '									batchPayIds.push(this.' .$tableModelName .'.id);';
        $inlineJavaScript .= '								});';
        $inlineJavaScript .= '							}';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: \'<iframe src="\'+iframeUrl+\'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
        $inlineJavaScript .= '								title: (isBatchAction ? "Pay Selected ' .__(Inflector::pluralize(Inflector::humanize(Inflector::underscore($tableModelName)))) .'" : "Pay ' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .'"),';
        $inlineJavaScript .= '								onEscape: function(event) {';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.ajax.reload();';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.order( [ 1, "' .strtolower($defaultSortDir) .'" ] ).draw();';
        $inlineJavaScript .= '								},';
        $inlineJavaScript .= '								buttons: {';
        $inlineJavaScript .= '									"Close" : {';
        $inlineJavaScript .= '										"label" : "' .__("Close") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-sm",';
        $inlineJavaScript .= '										"callback" : function(event){';
        $inlineJavaScript .= '											window.location.reload(); /* Reload window to show flash message */';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '					if($("a.popup-refund").length){';
        $inlineJavaScript .= '						$("a.popup-refund").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var iframeUrl = $(event.target).closest("a").attr("data-link");';
        $inlineJavaScript .= '							var isBatchAction = $(event.target).closest("a").hasClass("batch-action");';
        $inlineJavaScript .= '							var batchRefundIds = new Array();';
        $inlineJavaScript .= '							if(isBatchAction){';
        $inlineJavaScript .= '								var selectedObjs = ' .$uniqueTableVarName .'.rows(".selected").data();';
        $inlineJavaScript .= '								if(selectedObjs == null || selectedObjs == "" || selectedObjs.length < 1){';
        $inlineJavaScript .= '									messageBox({"status": ERROR, "message": "' .__("Please make some selections first.") .'"});';
        $inlineJavaScript .= '									return false;';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '								$.each(selectedObjs, function(){';
        $inlineJavaScript .= '									batchRefundIds.push(this.' .$tableModelName .'.id);';
        $inlineJavaScript .= '								});';
        $inlineJavaScript .= '							}';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: \'<iframe src="\'+iframeUrl+\'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
        $inlineJavaScript .= '								title: (isBatchAction ? "Refund Selected ' .__(Inflector::pluralize(Inflector::humanize(Inflector::underscore($tableModelName)))) .'" : "Refund ' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .'"),';
        $inlineJavaScript .= '								onEscape: function(event) {';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.ajax.reload();';
        $inlineJavaScript .= '									updateSecurityToken(event);';
        $inlineJavaScript .= '									' .$uniqueTableVarName .'.order( [ 1, "' .strtolower($defaultSortDir) .'" ] ).draw();';
        $inlineJavaScript .= '								},';
        $inlineJavaScript .= '								buttons: {';
        $inlineJavaScript .= '									"Refund" : {';
        $inlineJavaScript .= '										"label" : "' .__("Refund") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-success submit-iframe-form-btn",';
        $inlineJavaScript .= '										"callback" : function(event){';
        $inlineJavaScript .= '											if(isBatchAction && batchRefundIds.length > 0){';
        $inlineJavaScript .= '												updateSecurityToken(event);';
        $inlineJavaScript .= '												$.ajax({';
        $inlineJavaScript .= '													async: false,';
        $inlineJavaScript .= '													cache: false,';
        $inlineJavaScript .= '													url: iframeUrl,';
        $inlineJavaScript .= '													type: "POST",';
        $inlineJavaScript .= '													data: {"batchIds": batchRefundIds},';
        $inlineJavaScript .= '													beforeSend: function ( xhr ) { /* Do nothing for now */ },';
        $inlineJavaScript .= '												}).done(function ( data ) {';
        $inlineJavaScript .= '													window.location.reload(); /* Reload window to show flash message */';
        $inlineJavaScript .= '												}).fail(function(jqXHR, textStatus, errorThrown) {';
        $inlineJavaScript .= '													ajaxErrorHandler(jqXHR, textStatus, errorThrown);';
        $inlineJavaScript .= '												}).always(function() { ';
        $inlineJavaScript .= '													/* Do nothing for now */';
        $inlineJavaScript .= '												});';
        $inlineJavaScript .= '											}else{';
        $inlineJavaScript .= '												ajaxSubmitIframeForm(event);';
        $inlineJavaScript .= '												return false;';
        $inlineJavaScript .= '											}';
        $inlineJavaScript .= '											return false;';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									},';
        $inlineJavaScript .= '									"Close" : {';
        $inlineJavaScript .= '										"label" : "' .__("Close") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-sm"';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '					if($("a.popup-filter").length){';
        $inlineJavaScript .= '						$("a.popup-filter").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var filterField = \'<input type="search" class="form-control input-sm" placeholder="" aria-controls="User-data-table" />\';';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: filterField,';
        $inlineJavaScript .= '								title: "Search ' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .'",';
        $inlineJavaScript .= '								buttons: {';
        $inlineJavaScript .= '									"Search" : {';
        $inlineJavaScript .= '										"label" : "' .__("Search") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-primary",';
        $inlineJavaScript .= '										"callback": function() {';
        $inlineJavaScript .= '											var filterText = $(\'.bootbox.modal[role="dialog"]\').filter(function(){ return $(this).css("display") == "block";}).first().find(\'input[type="search"]:first\').val();';
        $inlineJavaScript .= '											dataTableObjForInternalUse.fnFilter(filterText);';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '					if($("a.popup-statistics").length){';
        $inlineJavaScript .= '						$("a.popup-statistics").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var iframeUrl = $(event.target).closest("a").attr("data-link");';
        $inlineJavaScript .= '							var popupTitle = "' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .' Statistics";';
        $inlineJavaScript .= '							if($(event.target).closest("a").hasClass("subscriber-statistics")){';
        $inlineJavaScript .= '								popupTitle = "View Email Marketing Subscribers (with statistics) ";';
        $inlineJavaScript .= '							}else if($(event.target).closest("a").hasClass("email-link-statistics")){';
        $inlineJavaScript .= '								popupTitle = "View Email Marketing (Email Click) Statistics";';
        $inlineJavaScript .= '							}';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: \'<iframe src="\'+iframeUrl+\'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
        $inlineJavaScript .= '								title: popupTitle';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '					if($("a.popup-schedule").length){';
        $inlineJavaScript .= '						$("a.popup-schedule").on(ace.click_event, function(event) {';
        $inlineJavaScript .= '							event.preventDefault();';
        $inlineJavaScript .= '							event.stopPropagation();';
        $inlineJavaScript .= '							event.stopImmediatePropagation();';
        $inlineJavaScript .= '							var iframeUrl = $(event.target).closest("a").attr("data-link");';
        $inlineJavaScript .= '							var popupTitle = "Set up ' .__(Inflector::humanize(Inflector::underscore($tableModelName))) .' Schedule";';
        $inlineJavaScript .= '							bootbox.dialog({';
        $inlineJavaScript .= '								message: \'<iframe src="\'+iframeUrl+\'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
        $inlineJavaScript .= '								title: popupTitle,';
        $inlineJavaScript .= '								buttons: {';
        $inlineJavaScript .= '									"Set" : {';
        $inlineJavaScript .= '										"label" : "' .__("Set Schedule") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-success submit-iframe-form-btn",';
        $inlineJavaScript .= '										"callback": function(event) {';
        $inlineJavaScript .= '											submitIframeForm(event);';
        $inlineJavaScript .= '											return false;';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									},';
        $inlineJavaScript .= '									"Remove" : {';
        $inlineJavaScript .= '										"label" : "' .__("Remove Schedule") .'",';
        $inlineJavaScript .= '										"className" : "btn-sm btn-danger submit-iframe-form-btn",';
        $inlineJavaScript .= '										"callback": function(event) {';
        $inlineJavaScript .= '											$(event.target).closest(".modal-footer").siblings(".modal-body").find(\'iframe[src^="\' + iframeUrl + \'"]\').first().contents().find("input#EmailMarketingCampaignRemove").val(1);';
        $inlineJavaScript .= '											submitIframeForm(event);';
        $inlineJavaScript .= '											return false;';
        $inlineJavaScript .= '										}';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        $inlineJavaScript .= '							});';
        $inlineJavaScript .= '						});';
        $inlineJavaScript .= '					}';
        $inlineJavaScript .= '				},';
        $inlineJavaScript .= '				"fnServerData": function ( sSource, aoData, fnCallback, oSettings ) {';
        $inlineJavaScript .= '					updateSecurityToken(oSettings);';
        $inlineJavaScript .= '					oSettings.jqXHR = $.ajax( {';
        $inlineJavaScript .= '						"async": false,';
        $inlineJavaScript .= '						"cache": false,';
        $inlineJavaScript .= '						"dataType": "json",';
        $inlineJavaScript .= '						"type": "POST",';
        $inlineJavaScript .= '						"url": sSource,';
        $inlineJavaScript .= '						"data": aoData,';
        $inlineJavaScript .= '						"success": function(data){';
        $inlineJavaScript .= '							for(var i = 0; i < data.aaData.length; i++){';
        if($hasSelectionBox){
        	$inlineJavaScript .= '							data.aaData[i].' .$tableModelName .'.selection = \'<label class="pos-rel"><input type="checkbox" class="ace" /><span class="lbl"></span></label>\';';
        }
        $inlineJavaScript .= '								data.aaData[i].' .$tableModelName .'.responsiveControl = \'\';';
        $inlineJavaScript .= '								for(var field in data.aaData[i].' .$tableModelName .'){';
        $inlineJavaScript .= '									if(data.aaData[i].' .$tableModelName .'[field] == "true" || data.aaData[i].' .$tableModelName .'[field] === true){';
        $inlineJavaScript .= '										data.aaData[i].' .$tableModelName .'[field] = "<span class=\"green\">" + (field.toLowerCase() == "active" ? "' .__("Activated") .'" : "' .__("Yes") .'") + "</span>";';
        $inlineJavaScript .= '									}else if(data.aaData[i].' .$tableModelName .'[field] == "false" || data.aaData[i].' .$tableModelName .'[field] === false){';
        $inlineJavaScript .= '										data.aaData[i].' .$tableModelName .'[field] = "<span class=\"red\">" + (field.toLowerCase() == "active" ? "' .__("Inactivated") .'" : "' .__("No") .'") + "</span>";';
        $inlineJavaScript .= '									}';
        $inlineJavaScript .= '								}';
        if(!empty($combineDataJs)){
        	$inlineJavaScript .= implode("\n", $combineDataJs);
        }
        $specialAddActionURL = null;
        $specialAddActionURLOptions = null;

        if($hasActions){
        	if(is_array($tableActions)){
        		$actions = '						var actions = new Array();';
        		foreach($tableActions as $tableActionKey => $tableActionValue){
        			list($actionName, $actionUrlPrefix, $actionUrlFieldName) = array($tableActionKey, $tableActionValue[0], @$tableActionValue[1]);

        			// Check given action permission
        			if(!$this->__checkActionPermissionBasedOnURL($actionUrlPrefix)){
        				unset($tableActions[$tableActionKey]);
        				continue;
        			}

        			// Handle special "add" action URL
        			if(strtoupper($actionName) == "ADD" && !empty($actionUrlPrefix) && is_string($actionUrlPrefix)){
        				$specialAddActionURL = $actionUrlPrefix;
        				$specialAddActionURLOptions = @$tableActionValue[3];
        				continue;
        			}

        			$actionJSVar = str_replace("/", "_", $actionUrlPrefix);

        			$actionConfirm = false;
        			if (isset($tableActionValue[2]) && !empty($tableActionValue[2])){
        				$actionConfirm = $tableActionValue[2];
        			}

        			$actionUrlFieldNames = $actionUrlFieldName;
        			if(!is_array($actionUrlFieldNames)){
        				$actionUrlFieldNames = array($actionUrlFieldNames);
        			}
        			$actionUrl = $actionUrlPrefix;
        			foreach($actionUrlFieldNames as $actionUrlFieldName){
        				$actionUrl = $actionUrl ."{{$actionUrlFieldName}}/";
        			}
        			$actionUrl = substr($actionUrl, 0, (strlen($actionUrl) - 1));

        			$link_options = array('escape' => false, 'data-link' => $actionUrl);
        			if(isset($tableActionValue[3]) && !empty($tableActionValue[3]) && is_array($tableActionValue[3])){
        				$link_options = am($link_options, $tableActionValue[3]);
        			}

        			// Transfer action name to icon
        			switch(strtoupper($actionName)){
        				case 'VIEW':
        					$actionName = '<i class="ace-icon fa fa-search-plus bigger-130"></i>';
        					break;
        				case 'EDIT':
        					$actionName = '<i class="ace-icon fa fa-pencil bigger-130"></i>';
        					break;
        				case 'DELETE':
        					$actionName = '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
        					break;
        				case 'PAY':
        					$actionName = '<i class="ace-icon fa fa-credit-card bigger-130"></i>';
        					break;
        				case 'REFUND':
        					$actionName = '<i class="ace-icon fa fa-undo bigger-130"></i>';
        					break;
        				case 'EMAIL':
        					$actionName = '<i class="ace-icon fa fa-envelope-o bigger-130"></i>';
        					$emailBatch = true;
        					break;
        				case 'IMPORT':
        					$importLink = $this->Html->link('<i class="ace-icon fa fa-cloud-upload bigger-130"></i>', "#", $link_options, $actionConfirm);
        					$importLink = str_replace("/{}", "", $importLink);
        					break;
        				case 'STATISTICS':
        				case 'STATISTICS-SUBSCRIBER':
        				case 'STATISTICS-EMAIL-CLICK':
							if(isset($link_options['class']) && stristr($link_options['class'], "subscriber-statistics")){
								$actionName = '<i class="ace-icon fa fa-child bigger-130"></i>';
							}elseif(isset($link_options['class']) && stristr($link_options['class'], "email-link-statistics")){
								$actionName = '<i class="ace-icon fa fa-link bigger-130"></i>';
							}else{
								$actionName = '<i class="ace-icon fa fa-pie-chart bigger-130"></i>';
							}
        					break;
        				case 'SCHEDULE':
        					$actionName = '<i class="ace-icon fa fa-clock-o bigger-130"></i>';
        					break;
        			}

        			// Move import link to the bottom/footer of the data table. (not append to the action column in the row)
        			if(isset($importLink) && strtoupper($actionName) == 'IMPORT'){
        				continue;
        			}

        			$linkURL = (!empty($link_options['target']) && strtolower($link_options['target']) != "_self") ? $actionUrl : '#';
        			$actions .= '					var ' .$actionJSVar .' = \'' .$this->Html->link($actionName, $linkURL, $link_options, $actionConfirm) .'\';';
        			foreach($actionUrlFieldNames as $actionUrlFieldName){
        				$actions .= '					' .$actionJSVar .' = ' .$actionJSVar .'.replace(/{' .$actionUrlFieldName .'}/ig, data.aaData[i].' .$actionUrlFieldName .');';
        			}
        			$actions .= '					actions.push(' .$actionJSVar .');';
        		}
        		$inlineJavaScript .= $actions;
        		$inlineJavaScript .= '						data.aaData[i].' .$tableModelName .'.actions = actions.join("&nbsp;&nbsp;&nbsp;&nbsp;");';
        	}else{
        		$inlineJavaScript .= '						data.aaData[i].' .$tableModelName .'.actions = \'' .$tableActions .'\';';
        	}
        }
        $inlineJavaScript .= '							}';
        $inlineJavaScript .= '							fnCallback(data);'; // Continue jQuery Datatable process
        $inlineJavaScript .= '						}';
        $inlineJavaScript .= '					} );';
        $inlineJavaScript .= '				},';
        $inlineJavaScript .= '			});'; // end of dataTable

    	$inlineJavaScript .= '			var filter_' .$uniqueTableVarName .' = \'<a href="#" class="orange popup-filter"><i class="ace-icon fa fa-search bigger-130"></i></a>\';';
    	if(!empty($tableModelName)){
    		$addActionURL = (empty($specialAddActionURL) ? '/admin/' .(!empty($cakePHPPlugin) ? $cakePHPPlugin.'/' : '') .strtolower(Inflector::underscore(Inflector::pluralize($tableModelName))) .'/add' : $specialAddActionURL);
    		if($canAddRow = $this->__checkActionPermissionBasedOnURL($addActionURL)){
    			if(!empty($specialAddActionURLOptions) && is_array($specialAddActionURLOptions)){
    				$addLinkURL = (!empty($specialAddActionURLOptions['target']) && strtolower($specialAddActionURLOptions['target']) != "_self") ? $addActionURL : '#';
    				$specialAddActionURLOptionsTxt = '';
    				foreach($specialAddActionURLOptions as $k => $v){
    					$specialAddActionURLOptionsTxt .= $k .'="' .$v .'" ';
    				}
    				$inlineJavaScript .= 'var addRow_' .$uniqueTableVarName .' = \'<a href="' .$addLinkURL .'" data-link="' .$addActionURL .'" ' .$specialAddActionURLOptionsTxt .'><i class="ace-icon fa fa-plus-circle bigger-130"></i></a>\';';
    			}else{
    				$inlineJavaScript .= 'var addRow_' .$uniqueTableVarName .' = \'<a href="#" data-link="' .$addActionURL .'" class="purple popup-add"><i class="ace-icon fa fa-plus-circle bigger-130"></i></a>\';';
    			}
    		}
    		if(isset($importLink) && $importLink){
    			$copyOfTableActions = array_change_key_case($tableActions);
    			$importActionURL = @$copyOfTableActions['import'][0];
    			$canImportRow = array_key_exists('import', $copyOfTableActions) && !empty($importActionURL) && $this->__checkActionPermissionBasedOnURL($importActionURL);
    			if($canImportRow){
    				$inlineJavaScript .= 'var importRow_' .$uniqueTableVarName .' = \'' .$importLink .'\';';
    			}
    		}
    	}
    	if($hasSelectionBox && !empty($tableModelName)){
// 			$inlineJavaScript .= '		var batchEdit_' .$uniqueTableVarName .' = \'<a href="#" data-link="/admin/users/add" class="purple popup-edit batch-action"><i class="ace-icon fa fa-pencil bigger-130"></i></a>\';';

    		$formattedTableModelName = strtolower(Inflector::underscore(Inflector::pluralize($tableModelName)));
    		$batchDeleteActionURL 	 = '/admin/' .(!empty($cakePHPPlugin) ? $cakePHPPlugin.'/' : '') .$formattedTableModelName .'/batchDelete';

    		// This is a little bit tricky. We actually don't need to delete statistic records, but there is an exception for bounced records. Clients do need to delete a (HARD) bounce record.
    		// Becasue the bounced subscriber belongs to ANOTHER model, and bounced model has no controller. It cannot pass permission check if we use non-existing "bounce" controller by default.
    		// Here we need to do a little HACK to make this happen.
    		if("EmailMarketingSubscriberBounceRecord" == $tableModelName){
    			$newFormattedTableModelName = "email_marketing_subscribers";
    			$batchDeleteActionURL = str_replace($formattedTableModelName, $newFormattedTableModelName, $batchDeleteActionURL);
    		}

    		if($canBatchDeleteRow = $this->__checkActionPermissionBasedOnURL($batchDeleteActionURL)){
    			$inlineJavaScript .= '	var batchDelete_' .$uniqueTableVarName .' = \'<a href="#" data-link="' .$batchDeleteActionURL .'" class="purple popup-delete batch-action"><i class="ace-icon fa fa-trash bigger-130"></i></a>\';';
    		}
    	}
    	if(isset($emailBatch) && $emailBatch && !empty($tableModelName)){
    		$batchEmailActionURL = '/admin/' .(!empty($cakePHPPlugin) ? $cakePHPPlugin.'/' : '') .strtolower(Inflector::underscore(Inflector::pluralize($tableModelName))) .'/batchEmail';
    		if($canBatchEmail = $this->__checkActionPermissionBasedOnURL($batchEmailActionURL)){
    			$inlineJavaScript .= '	var batchEmail_' .$uniqueTableVarName .' = \'<a href="#" data-link="' .$batchEmailActionURL .'" class="pink popup-email batch-action"><i class="ace-icon fa fa-envelope-o bigger-130"></i></a>\';';
    		}
    	}

    	$inlineJavaScript .= '			$("#action_' .$uniqueTableVarName .' .table-actions").prepend(';
    	if(!empty($tableModelName)){
    		if($canAddRow){
    			$inlineJavaScript .= '		addRow_' .$uniqueTableVarName .'+"&nbsp;&nbsp;&nbsp;&nbsp;"+';
    		}
    		if(isset($importLink) && $importLink && $canImportRow){
    			$inlineJavaScript .= '		importRow_' .$uniqueTableVarName .'+"&nbsp;&nbsp;&nbsp;&nbsp;"+';
    		}
    	}
    	$inlineJavaScript .= '				filter_' .$uniqueTableVarName;
    	if($hasSelectionBox && !empty($tableModelName)){
//     		$inlineJavaScript .= '			+"&nbsp;&nbsp;&nbsp;&nbsp<a class=\'separator\'></a>&nbsp;&nbsp;&nbsp;&nbsp;"+batchEdit_' .$uniqueTableVarName .'+"&nbsp;&nbsp;&nbsp;&nbsp;"+batchDelete_' .$uniqueTableVarName .');';

    		if($canBatchDeleteRow){
    			$inlineJavaScript .= '		+"&nbsp;&nbsp;&nbsp;&nbsp<a class=\'separator\'></a>&nbsp;&nbsp;&nbsp;&nbsp;"+batchDelete_' .$uniqueTableVarName;
    		}
    	}
    	if(isset($emailBatch) && $emailBatch && !empty($tableModelName) && $canBatchEmail){
    		$inlineJavaScript .= '			+"&nbsp;&nbsp;&nbsp;&nbsp<a class=\'separator\'></a>&nbsp;&nbsp;&nbsp;&nbsp;"+batchEmail_' .$uniqueTableVarName;
    	}
    	$inlineJavaScript .= '		);';

		// calculate included columns (columns show up in visibility list - "colvis")
		$currentProcessingColumnIndex = $hasSelectionBox ? 1 : 0;
		$includedColumns = array($currentProcessingColumnIndex);
		$columnToBeProcessedAmount = $actualDataColumns - 1; // Because we just processed the first column in the list
		while ($columnToBeProcessedAmount > 0){
			$currentProcessingColumnIndex++;
			array_push($includedColumns, $currentProcessingColumnIndex);
			$columnToBeProcessedAmount--;
		}
		$includedColumns = json_encode($includedColumns);

		$inlineJavaScript .= $this->getTableToolsSettings($uniqueTableVarName, $includedColumns);

        if($hasSelectionBox){
	        $inlineJavaScript .= '		$(\'th input[type="checkbox"], td input[type="checkbox"]\').prop(\'checked\', false);';
	        $inlineJavaScript .= '		$(\'table.' .$uniqueClass .' > thead > tr > th input[type="checkbox"], #' .$tableModelName .'-data-table_wrapper input[type="checkbox"] \').eq(0).on(\'click\', function(){'; // select/deselect all rows according to table header checkbox
	        $inlineJavaScript .= '			var th_checked = this.checked;';
	        $inlineJavaScript .= '			$(this).closest(\'table\').find(\'tbody > tr\').each(function(){';
	        $inlineJavaScript .= '				var row = this;';
	        $inlineJavaScript .= '				if(th_checked) ' .$uniqueTableVarName .'.row(row).select();';
	        $inlineJavaScript .= '				else ' .$uniqueTableVarName .'.row(row).deselect();';
	        $inlineJavaScript .= '			});';
	        $inlineJavaScript .= '		});';
	        $inlineJavaScript .= '		$(\'table.' .$uniqueClass .'\').on(\'click\', \'td input[type="checkbox"]\' , function(){'; //select/deselect a row when the checkbox is checked/unchecked
	        $inlineJavaScript .= '			var row = $(this).closest(\'tr\').get(0);';
	        $inlineJavaScript .= '			if(!this.checked) ' .$uniqueTableVarName .'.row(row).deselect();';
	        $inlineJavaScript .= '			else ' .$uniqueTableVarName .'.row(row).select();';
	        $inlineJavaScript .= '		});';
	        $inlineJavaScript .= '		$(document).on(\'click\', \'table.' .$uniqueClass .' .dropdown-toggle\', function(e) {';
	        $inlineJavaScript .= '			e.stopImmediatePropagation();';
	        $inlineJavaScript .= '			e.stopPropagation();';
	        $inlineJavaScript .= '			e.preventDefault();';
	        $inlineJavaScript .= '		});';
        }

        $inlineJavaScript .= '			$.fn.dataTableExt.sErrMode = "throw";';
        $inlineJavaScript .= '		});';// close of "jQuery(function($) {"
        $inlineJavaScript .= '	};';// close of "loadDataTable_' .$uniqueTableVarName .'"

        $inlineJavaScript .= '	if($(\'[data-ajax-content="true"]\').length && $.fn.ace_ajax && false){';
        $inlineJavaScript .= '		var scripts = [null, null];';
        $inlineJavaScript .= '		$(\'[data-ajax-content="true"]\').ace_ajax(\'loadScripts\', scripts, function() {';
        $inlineJavaScript .= '			loadDataTable_' .$uniqueTableVarName .'();';
        $inlineJavaScript .= '		});';
        $inlineJavaScript .= '	}else{';
        $inlineJavaScript .= '			loadDataTable_' .$uniqueTableVarName .'();';
        $inlineJavaScript .= '	}';

        $output .= '$(function(){' .$this->Minify->minifyInlineJS($inlineJavaScript) .'});';
        $output .= '</script>';

        return $output;
    }

/**
 *
 * @param string $uniqueTableVarName	data table JS object name
 * @param string $excludedExportColumns	data table excluded export columns' index
 * @return string
 */
    public function getTableToolsSettings($uniqueTableVarName, $includeColumnArr = null){

    	if(empty($uniqueTableVarName)){
    		return '';
    	}

    	$output = '			$.fn.dataTable.Buttons.swfPath = "/js/admin/dataTables/extensions/buttons/swf/flashExport.swf";';
    	$output .= '		$.fn.dataTable.Buttons.defaults.dom.container.className = "dt-buttons btn-overlap btn-group btn-overlap";';
    	$output .= '		var exportColumns = function () {';
    	if(!empty($includeColumnArr)){
    		$output .= '		var activeColumnIndexes = ' .$includeColumnArr .';';
    		$output .= '		var exportColumnIndexes = new Array();';
    		$output .= '		if(Object.prototype.toString.call( activeColumnIndexes ) === \'[object Array]\' && activeColumnIndexes.length > 0){';
	    	$output .= '			var activeColumnHeaders = ' .$uniqueTableVarName .'.table().context[0].aoColumns;';
	    	$output .= '			if(Object.prototype.toString.call( activeColumnHeaders ) === \'[object Array]\' && activeColumnHeaders.length > 0){';
	    	$output .= '				for(var i = 0; i < activeColumnIndexes.length; i++){';
	    	$output .= '					if(activeColumnHeaders[activeColumnIndexes[i]] && activeColumnHeaders[activeColumnIndexes[i]].hasOwnProperty("bVisible") && activeColumnHeaders[activeColumnIndexes[i]].bVisible === true){';
	    	$output .= '						exportColumnIndexes.push(activeColumnIndexes[i]);';
	    	$output .= '					}';
	    	$output .= '				}';
	    	$output .= '			}';
	    	$output .= '		}';
	    	$output .= '		return exportColumnIndexes;';
    	}else{
    		$output .= '		return ":visible";';
    	}
    	$output .= '		};';
    	$output .= '		var tableTools_' .$uniqueTableVarName .' = new $.fn.dataTable.Buttons( ' .$uniqueTableVarName .', {';
    	$output .= '			"buttons": [';
    	$output .= '				{';
    	$output .= '					"extend": "colvis",';
    	$output .= '					"text": "<i class=\'fa fa-eye-slash bigger-110 blue\'></i> <span class=\'hidden\'>' .__("Show/hide columns") .'</span>",';
    	$output .= '					"className": "btn btn-white btn-primary btn-bold",';
    	if(!empty($includeColumnArr)){
    		$output .= '				"columns": ' .$includeColumnArr;
    	}
    	$output .= '				},';
    	$output .= '				{';
    	$output .= '					"extend": "copyHtml5",';
    	$output .= '					"text": "<i class=\'fa fa-copy bigger-110 pink\'></i> <span class=\'hidden\'>' .__("Copy to clipboard") .'</span>",';
    	$output .= '					"className": "btn btn-white btn-primary btn-bold",';
    	$output .= '					"exportOptions": {';
    	$output .= '						"columns": exportColumns,';
    	$output .= '						"modifier": {';
    	$output .= '							"selected": true';
    	$output .= '						}';
    	$output .= '					}';
    	$output .= '				},';
    	$output .= '				{';
    	$output .= '					"extend": "csvHtml5",';
    	$output .= '					"text": "<i class=\'fa fa-database bigger-110 orange\'></i> <span class=\'hidden\'>' .__("Export to CSV") .'</span>",';
    	$output .= '					"className": "btn btn-white btn-primary btn-bold",';
    	$output .= '					"exportOptions": {';
    	$output .= '						"columns": exportColumns';
    	$output .= '					}';
    	$output .= '				},';
    	$output .= '				{';
    	$output .= '					"extend": "excelHtml5",';
    	$output .= '					"text": "<i class=\'fa fa-file-excel-o bigger-110 green\'></i> <span class=\'hidden\'>' .__("Export to Excel") .'</span>",';
    	$output .= '					"className": "btn btn-white btn-primary btn-bold",';
    	$output .= '					"exportOptions": {';
    	$output .= '						"columns": exportColumns';
    	$output .= '					}';
    	$output .= '				},';
    	$output .= '				{';
    	$output .= '					"extend": "pdfHtml5",';
    	$output .= '					"text": "<i class=\'fa fa-file-pdf-o bigger-110 red\'></i> <span class=\'hidden\'>' .__("Export to PDF") .'</span>",';
    	$output .= '					"className": "btn btn-white btn-primary btn-bold",';
    	$output .= '					"exportOptions": {';
    	$output .= '						"columns": exportColumns';
    	$output .= '					}';
    	$output .= '				},';

    	// Cannot load print feature when data table is rendered in an iframe, because print feature can only remove the elements inside its own window (the iframe), the elements in parent window cannot be removed and those elements will be printed out with the data table.
//     	if(!isset($this->_View->viewVars['loadInIframe']) || $this->_View->viewVars['loadInIframe'] !== TRUE){
	    	$output .= '			{';
	    	$output .= '				"extend": "print",';
	    	$output .= '				"text": "<i class=\'fa fa-print bigger-110 grey\'></i> <span class=\'hidden\'>' .__("Print") .'</span>",';
	    	$output .= '				"className": "btn btn-white btn-primary btn-bold",';
	    	$output .= '				"autoPrint": false,';
	    	$output .= '				"exportOptions": {';
	    	$output .= '					"columns": exportColumns';
	    	$output .= '				}';
	    	$output .= '			}';
//     	}

    	$output .= '			]';
    	$output .= '		} );';

    	$output .= '		var tableToolsContainer = $(\'.tableTools-container\');';
    	$output .= '		' .$uniqueTableVarName .'.buttons().container().appendTo(tableToolsContainer);'; // we put a container before our table and append TableTools element to it

    	//style the message box
    	$output .= '		var defaultCopyAction = ' .$uniqueTableVarName .'.button(1).action();';
    	$output .= '		' .$uniqueTableVarName .'.button(1).action(function (e, dt, button, config) {';
    	$output .= '			defaultCopyAction(e, dt, button, config);';
    	$output .= '			$(".dt-button-info").addClass("gritter-item-wrapper gritter-info gritter-center white");';
    	$output .= '		});';

    	$output .= '		var defaultColvisAction = ' .$uniqueTableVarName .'.button(0).action();';
    	$output .= '		' .$uniqueTableVarName .'.button(0).action(function (e, dt, button, config) {';
    	$output .= '			defaultColvisAction(e, dt, button, config);';
    	$output .= '			if($(".dt-button-collection > .dropdown-menu").length == 0) {';
    	$output .= '				$(".dt-button-collection").wrapInner(\'<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />\').find("a").attr("href", "#").wrap("<li />");';
    	$output .= '			}';
    	$output .= '			$(".dt-button-collection").appendTo(".tableTools-container .dt-buttons");';
    	$output .= '		});';

    	$output .= '		tableToolsContainer.find("a.search").first().find("i.fa-search").first().on("click", function(){';
    	$output .= '			if($(this).closest("a").prev(".nav-search").css("display") != "inline-block"){';
    	$output .= '				$(this).closest("a").prev(".nav-search").css({"display": "inline-block"});';
    	$output .= '			}else{';
    	$output .= '				$(this).closest("a").prev(".nav-search").css({"display": "none"});';
    	$output .= '			}';
    	$output .= '		});';

    	$output .= '		tableToolsContainer.find("input#nav-search-input").first().on("keyup", function(){';
    	$output .= '			if(this.value.length > 3){';
    	$output .= '				var globalRegex = true;';
    	$output .= '				var globalSmart = true;';
    	$output .= '				' .$uniqueTableVarName .'.search(this.value, globalRegex, globalSmart).draw();';
    	$output .= '			}';
    	$output .= '		});';

    	$output .= '		setTimeout(function() {';
    	$output .= '			$($(".tableTools-container")).find("a.dt-button").each(function() {';
    	$output .= '				var div = $(this).find(" > div").first();';
    	$output .= '				if(div.length == 1){ ';
    	$output .= '					div.tooltip({container: "body", title: div.parent().text()});';
    	$output .= '				}else{ ';
    	$output .= '					$(this).tooltip({container: "body", title: $(this).text()});';
    	$output .= '				}';
    	$output .= '			});';
    	$output .= '		}, 500);';

    	$output .= '		' .$uniqueTableVarName .'.on( "select", function ( e, dt, type, index ) {';
    	$output .= '			if ( type === "row" ) {';
    	$output .= '				$( ' .$uniqueTableVarName .'.row( index ).node() ).find("input:checkbox").prop("checked", true);';
    	$output .= '			}';
    	$output .= '		});';

    	$output .= '		' .$uniqueTableVarName .'.on( "deselect", function ( e, dt, type, index ) {';
    	$output .= '			if ( type === "row" ) {';
    	$output .= '				$( ' .$uniqueTableVarName .'.row( index ).node() ).find("input:checkbox").prop("checked", false);';
    	$output .= '			}';
    	$output .= '		});';

    	return $output;
    }

    private function __checkActionPermissionBasedOnURL($url = ''){
    	if(empty($url) || !is_string($url)){
    		return false;
    	}

    	// Init ACL component
    	$collection = new ComponentCollection();
    	$acl 		= new AclComponent($collection);

    	try {
    		$urlDetails = Router::parse($url);
    		$pluginName = empty($urlDetails['plugin']) ? '' : Inflector::camelize($urlDetails['plugin']);
    		$controllerName = empty($urlDetails['controller']) ? '' : Inflector::camelize($urlDetails['controller']);
    		$controllerActionName = @$urlDetails['action'];
    		$actionUrl = (empty($pluginName) ? "" : $pluginName .DS) .$controllerName .DS .$controllerActionName;
    		return $this->Permissions->check($acl, $actionUrl);
    	} catch (Exception $e) {
    		error_log('JQueryDataTable permission check (' .$url .'): ' .$e->getMessage());
    		return false;
    	}
    }

}
?>
