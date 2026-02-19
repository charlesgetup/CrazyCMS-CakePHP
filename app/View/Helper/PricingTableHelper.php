<?php
class PricingTableHelper extends AppHelper{

    var $helpers = array('Html','Paginator', 'Session');

    private function __getField($defaultModelName, $entry, $entryDisplayField){

        // Determine model names and field name
        $modelsAndFields = explode('.', $entryDisplayField);
        if ((sizeof($modelsAndFields)) == 1){

            // Field doesn't have any model names in it (i.e. 'allows_pets')
            $entryModelNames = array($defaultModelName);
            $entryField = $entryDisplayField;

        }else{

            // Field has model names (i.e. 'Property.Address.address1')
            $modelsAndFieldsLastItemIndex = sizeof($modelsAndFields) - 1;

            $entryModelNames = array_slice($modelsAndFields, 0, $modelsAndFieldsLastItemIndex );
            $entryField = $modelsAndFields[$modelsAndFieldsLastItemIndex];
        }

        // Get field
        $fieldToDisplay = $entry;
        foreach ($entryModelNames as $entryModelName){
            $fieldToDisplay = $fieldToDisplay[$entryModelName];
        }

        return $fieldToDisplay[$entryField];
    }

    private function __getRandomColor(){
    	$websiteUsedColor = ['#438eb9', '#337ab7', '#4c8fbd', '#5cb85c', '#4cae4c', '#87b87f', '#6fb3e0', '#abbac3', '#ffb752', '#eea236', '#f0ad4e', '#d15b47', '#d43f3a', '#d9534f', '#892e65', '#2e6589', '#a069c3', '#2e8965', '#69aa46', '#3c763d', '#d6487e', '#404040', '#666666'];
    	$limit = count($websiteUsedColor);
    	$colorIndex = mt_rand(0, $limit);
    	return $websiteUsedColor[$colorIndex];
    }

    /**
     * Note:
     * 	Because this is a price table and it is not suitable for large amount pricing items. All items must display in one page, no pagination applied.
     *
     * @param string $tableModelName
     * @param unknown $tableEntries
     * @param array $tableDisplayFields
     * @param array $tableActions
     * @param string $noItemsMessage
     * @param array $substitutions
     * @param array $headerColorCssClass
     * @param array $footerColorCssClass
     * @return string
     */
    public function createTable($tableModelName, $tableEntries, $tableDisplayFields, $tableActions = array(), $noItemsMessage = "There are no items to display", $substitutions = array(), $headerColorCssClass = array(), $footerColorCssClass = array()){

        if (empty($tableEntries)){
            return '<span>' .$noItemsMessage .'</span>';
        }

        // Get general settings
        $hasActions 		= (!empty($tableActions));
		$itemAmount			= count($tableEntries);
		$lastRowIndex 		= count($tableDisplayFields);

        // Create header
        $output = array();

        // Set common headers
        $randomColor = [];
        for($i = 0; $i < $itemAmount; $i++){
        	if(!isset($output[$i])){ $output[$i] = ''; } // loop using pre-defined CSS styles
        	$output[$i] .= '<div class="col-xs-6 col-sm-3 pricing-box">';

        	if(empty($headerColorCssClass) || empty($headerColorCssClass[$i])){
        		$randomColor[$i] = $this->__getRandomColor();
        	}

        	$output[$i] .= '<div class="widget-box ' .@$headerColorCssClass[$i] .'" ' .(empty($headerColorCssClass[$i]) ? 'style="border-color: ' .$randomColor[$i] .';"' : '') .'>';
        }

        // Set pricing content
        $rowIndex 			= 0;
        array_push($tableDisplayFields, ''); // make one extra row to add actions. This will only add one more loop while building the table
        foreach($tableDisplayFields as $tableDisplayFieldName => $tableDisplayField){

        	$entryIndex = 0;
        	foreach ($tableEntries as $entry){

        		// Add actions to the last row
        		if ($hasActions && $rowIndex == $lastRowIndex){

        			$output[$entryIndex] .= '<div>';
        			foreach ($tableActions as $tableActionKey => $tableActionValue){
        				list($actionName, $actionUrlPrefix,$actionUrlFieldName) = array($tableActionKey, $tableActionValue[0], $tableActionValue[1]);
        				$actionConfirm = false;
        				if(!empty($actionName)){

        					if (isset($tableActionValue[2]) && !empty($tableActionValue[2])){
        						$actionConfirm = $tableActionValue[2];
        					}

        					$actionUrlFieldNames = $actionUrlFieldName;
        					if(!is_array($actionUrlFieldNames)){
        						$actionUrlFieldNames = array($actionUrlFieldNames);
        					}
        					$actionUrl = $actionUrlPrefix;
        					foreach($actionUrlFieldNames as $actionUrlFieldName){
        						$fieldValue = $this->__getField($tableModelName,$entry,$actionUrlFieldName);
        						if(!empty($tableActionValue[4]) && $fieldValue == $tableActionValue[4]){
        							$actionUrl = '#/';
        							$actionConfirm = null;
        							$actionName = '&nbsp;';
        						}else{
        							$actionUrl = $actionUrl .$fieldValue ."/";
        						}
        					}
        					$actionUrl = substr($actionUrl, 0, (strlen($actionUrl) - 1));

        					$linkOptions = array();
        					if(isset($tableActionValue[3]) && !empty($tableActionValue[3]) && is_array($tableActionValue[3])){
        						$linkOptions = $tableActionValue[3];
        						if(isset($linkOptions['class'])){
        							$linkOptions['class'] .= ' ' .@$footerColorCssClass[$entryIndex];
        							if($actionUrl == '#'){
        								$linkOptions['class'] .= ' no-link';
        								$linkOptions = array('class' => $linkOptions['class'], 'escape' => $linkOptions['escape']); // IF no link, only keep link style
        							}
        						}
        						if(empty($footerColorCssClass) || empty($footerColorCssClass[$entryIndex])){
        							$linkOptions['style'] = 'border-color: ' .$randomColor[$entryIndex] .'; background-color: ' .$randomColor[$entryIndex] .' !important;';
        						}
        					}
        					$output[$entryIndex] .= $this->Html->link($actionName, $actionUrl, $linkOptions, $actionConfirm);
        				}
        			}
        			$output[$entryIndex] .= '</div>';
        			$output[$entryIndex] .= '</div>';

        		}else{

        			$fieldToDisplay = $this->__getField($tableModelName, $entry, @$tableDisplayField[0]); // Get field for the non-action column/row

        			if($tableDisplayFieldName !== "ITEM_NAME"){

        				// Handle substitutions
        				if(!empty($substitutions) && isset($substitutions[$tableDisplayFieldName]) && !empty($substitutions[$tableDisplayFieldName])){
        					foreach($substitutions[$tableDisplayFieldName] as $search => $replace){

        						$replace = isset($entry[$tableModelName][$replace]) ? $entry[$tableModelName][$replace] : $replace;
        						if(is_numeric($replace)){
        							$decPoint = strlen(strrchr($replace, '.')) - 1;
        							$replace  = number_format($replace, $decPoint, '.', ',');
        						}

        						if($fieldToDisplay == $search){

        							// Substitute the whole thing
        							$fieldToDisplay = $replace;

        						}elseif(strstr($fieldToDisplay, $search)){

        							// Partically replace
        							$fieldToDisplay = str_replace($search, $replace, $fieldToDisplay);
        						}

        					}
        				}

        				// "isFinalPrice" flag means this is the last row of the product description (content displayed in the middle)
        				if(isset($tableDisplayField['isFinalPrice']) && $tableDisplayField['isFinalPrice'] === TRUE){

        					// No further display text
        					if(is_numeric($fieldToDisplay)){
        						$fieldToDisplay = number_format($fieldToDisplay, 2);
        						$fieldToDisplay = str_replace("%", $fieldToDisplay, $tableDisplayFieldName);
        					}

        					$output[$entryIndex] .= '	</ul>';
        					$output[$entryIndex] .= '	<hr />';
        					$output[$entryIndex] .= '	<div class="price">' .$fieldToDisplay .'</div>';
        					$output[$entryIndex] .= '</div>';

        				}elseif(isset($tableDisplayField['isFinalRow']) && $tableDisplayField['isFinalRow'] === TRUE){

        					// Further adjust display text
        					$fieldToDisplay = str_replace("%", $fieldToDisplay, $tableDisplayFieldName);

        					$output[$entryIndex] .= '		<li>';
        					$output[$entryIndex] .= 			$fieldToDisplay;
        					$output[$entryIndex] .= '		</li>';

        					$output[$entryIndex] .= '	</ul>';
        					$output[$entryIndex] .= '</div>';

        				}else{

        					// Further adjust display text
        					$fieldToDisplay = str_replace("%", $fieldToDisplay, $tableDisplayFieldName);

        					$output[$entryIndex] .= '<li>';
        					$output[$entryIndex] .= 	$fieldToDisplay;
        					$output[$entryIndex] .= '</li>';

        				}

        			}else{

        				$output[$entryIndex] .= '<div class="widget-header" ' .(empty($headerColorCssClass[$entryIndex]) ? 'style="border-color: ' .$randomColor[$entryIndex] .'; background: ' .$randomColor[$entryIndex] .';"' : '') .'>';
        				$output[$entryIndex] .= '	<h5 class="widget-title bigger lighter white">' .$fieldToDisplay .'</h5>';
        				$output[$entryIndex] .= '</div>';
        				$output[$entryIndex] .= '<div class="widget-body">';
        				$output[$entryIndex] .= '	<div class="widget-main">';
        				$output[$entryIndex] .= '		<ul class="list-unstyled spaced2">';

        			}

        		}

        		$entryIndex++;
        	}

        	$rowIndex++;
        }

        // Set common headers
        for($j = 0; $j < $itemAmount; $j++){
        	$output[$j] .= '</div>';
        	$output[$j] .= '</div>';
        }

        $output = implode("", $output);

        return $output;
    }
}
?>
