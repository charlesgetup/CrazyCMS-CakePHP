<?php
class UtilHelper extends AppHelper{

	var $helpers = array('Time', 'Form', 'Minify');

	public function randomColor($rgb = false, $alpha = false){
		$color = '#' .$this->randomColorPart() .$this->randomColorPart() .$this->randomColorPart();
		if($rgb || $alpha !== false){
			$color = mt_rand(0, 255) .',' .mt_rand(0, 255) .',' .mt_rand(0, 255);
			if(is_numeric($alpha)){
				$color = "rgba({$color},{$alpha})";
			}else{
				$color = "rgb({$color})";
			}
		}
		return $color;
	}

	public function formatCreatedDate($dateStr){

		if(empty($dateStr) || !$this->isValidDate($dateStr)){
			return false;
		}

		$today = strtotime(date('Y-m-d 00:00:00'));
		$yesterday = strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')));
		$tomorrow = strtotime(date('Y-m-d 00:00:00', strtotime('+1 day')));

		$givenDatetime = strtotime($dateStr);

		// Created date or timestamp should be a past timestamp
		if($givenDatetime >= $tomorrow){
			return false;
		}

		$result = false;
		if($givenDatetime >= $today && $givenDatetime < $tomorrow){

			$result = __('Today') .' ' .__('at') .' ' .date('H:i', $givenDatetime);


		}elseif ($givenDatetime >= $yesterday && $givenDatetime < $today){

			$result = __('Yesterday') .' ' .__('at') .' ' .date('H:i', $givenDatetime);

		}elseif ($givenDatetime < $yesterday){

			$reldays = ($givenDatetime - $today) / 86400;
			if(abs($reldays) < 10){

				$reldays = abs(floor($reldays));
				$result = $reldays .' ' .__('day' .($reldays != 1 ? 's ' : ' ') .'ago');

			}else{

				$result = $this->Time->i18nFormat($dateStr, '%x %X');

			}

		}

		return $result;
	}

/**
 *
 * @param string $modelName
 * @param string $actionUrl
 * @param string $fileFieldName
 * @param int $uploadFileMaxSize (5M)
 * @param string $acceptedFiles
 * @param array $otherHiddenFields
 *
 * e.g. $otherHiddenFields = array('id' => 5)
 * And this will be tranformed into (input) hidden field
 *
 */
	public function setFullpageDragNDropUpload($modelName, $actionUrl = null, $fileFieldName = 'upload_file', $uploadFileMaxSize = 5242880, $acceptedFiles = '.xlsx,.xls,.csv,pdf,.jpeg,.jpg,.png,.gif,.bmp,.doc,.docx,.txt', $otherHiddenFields = array()){

		if(empty($modelName)){
			return '';
		}

		$fileFieldName = empty($fileFieldName) ? 'upload_file' : $fileFieldName;

		$uploadFileMaxSize = empty($uploadFileMaxSize) ? 5242880 : $uploadFileMaxSize;

		$acceptedFiles = empty($acceptedFiles) ? '.xlsx,.xls,.csv,pdf,.jpeg,.jpg,.png,.gif,.bmp,.doc,.docx,.txt' : $acceptedFiles;

		if(empty($actionUrl)){
			$html = $this->Form->create($modelName, array('type' => 'file', 'class' => "dropzone keep-action-btns", 'id' => "DropzoneUpload"));
		}else{
			$html = $this->Form->create(false, array(
				'type' => 'file',
			    'url' 	=> $actionUrl,
				'class' => 'dropzone keep-action-btns',
			    'id' 	=> 'DropzoneUpload'
			));
		}

		$html .= '<div class="row">';
		$html .= '	<div class="col-xs-12 fallback">';
		$html .= 		$this->Form->file($fileFieldName, array('class' => 'upload_field'));
		$html .= '	</div>';
		$html .= '</div>';

		if(!empty($otherHiddenFields) && is_array($otherHiddenFields)){
			foreach($otherHiddenFields as $name => $value){
				$html .= $this->Form->hidden($name, array('value' => $value));
			}
		}
		$html .= $this->Form->end();

		$html = '<div class="full-page-dropzone">' .$html .'</div>';

		$uploadFileMaxSize = ($uploadFileMaxSize / 1024 / 1024);

		$html .= '<script>';
		$inlineJavaScript = '	var triggerDragleaveEvent = true;';
		$inlineJavaScript .= '	var fileDropzone = loadDragNDropZone("#DropzoneUpload", "' .$fileFieldName .'", ' .$uploadFileMaxSize .', "' .$acceptedFiles .'");';
		$inlineJavaScript .= '	fileDropzone.on("complete", function(file) {';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone").style.visibility = "hidden";';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone").style.opacity = 0;';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone").style.width = "1px";';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone").style.height = "1px";';
		$inlineJavaScript .= '		try{';
		$inlineJavaScript .= '			var responseJson = $.parseJSON(file.xhr.responseText);';
		$inlineJavaScript .= '			parent.messageBox({"status": ((responseJson.status == "success" || responseJson.status == "SUCCESS") ? SUCCESS : ERROR), "message": responseJson.message});';
		$inlineJavaScript .= '		}catch(e){';
		$inlineJavaScript .= '			parent.messageBox({"status": ((file.status == "success" || file.status == "SUCCESS") ? SUCCESS : ERROR), "message": file.xhr.responseText});';
		$inlineJavaScript .= '		}';
		$inlineJavaScript .= '		window.location.reload();';
		$inlineJavaScript .= '	});';
		$inlineJavaScript .= '	fileDropzone.on("addedfile", function(file) {';
		$inlineJavaScript .= '		triggerDragleaveEvent = false;';
		$inlineJavaScript .= '	});';
		$inlineJavaScript .= '	var lastTarget = null;';
		$inlineJavaScript .= '	window.addEventListener("dragenter", function(e){';
		$inlineJavaScript .= '		lastTarget = e.target;';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone").style.visibility = "visible";';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone").style.opacity = 1;';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone").style.width = "100%";';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone").style.height = "100%";';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone").style.background = "rgba(0,0,0,0.8)";';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone .dz-default.dz-message span").style.color = "white";';
		$inlineJavaScript .= '		document.querySelector(".full-page-dropzone .dz-default.dz-message span span").style.color = "white";';
		$inlineJavaScript .= '		if(document.querySelector(".full-page-dropzone .dz-default.dz-message span span span")){ document.querySelector(".full-page-dropzone .dz-default.dz-message span span span").style.color = "white";}';
		$inlineJavaScript .= '	});';
		$inlineJavaScript .= '	window.addEventListener("dragleave", function(e){';
		$inlineJavaScript .= '		if(e.target === lastTarget || e.target === document){';
		$inlineJavaScript .= '			setTimeout(function(){';
		$inlineJavaScript .= '				if(triggerDragleaveEvent){';
		$inlineJavaScript .= '					document.querySelector(".full-page-dropzone").style.visibility = "hidden";';
		$inlineJavaScript .= '					document.querySelector(".full-page-dropzone").style.opacity = 0;';
		$inlineJavaScript .= '					document.querySelector(".full-page-dropzone").style.width = "1px";';
		$inlineJavaScript .= '					document.querySelector(".full-page-dropzone").style.height = "1px";';
		$inlineJavaScript .= '				}';
		$inlineJavaScript .= '			}, 3000);'; // Let the element to keep displaying for some time until the upload job is done
		$inlineJavaScript .= '		}';
		$inlineJavaScript .= '	});';
		$html .= $this->Minify->minifyInlineJS('$(function(){' .$inlineJavaScript .'});');
		$html .= '</script>';

		return $html;

	}

/**
 * Encode an email address to display on your website
 * @param string $email
 * @return string
 */
	public function encodeEmailAddress( $email ) {
		$output = '';
		for ($i = 0; $i < strlen($email); $i++){
			$output .= '&#'.ord($email[$i]).';';
		}
		return $output;
	}

	private function randomColorPart(){
		return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
	}

	private function isValidDate($dateStr){
		return (bool)strtotime($dateStr);
	}
}
?>