<?php
class TimelineHelper extends AppHelper{

	var $helpers = array('Html', 'ExtendedHtml', 'Session', 'Paginator', 'Permissions', 'Time', 'Minify');

/**
 *
 * @param string $modelName
 * @param array $displayFields
 * @param array $dataCollection
 * @param string $timelineContainerCssSelector
 * @param string $pageRedirectBaseUrl (optional this is used for the filters)
 * @param string $level (optional this is used for the filters)
 * @param string $startDate (optional this is used for the filters)
 * @param string $endDate (optional this is used for the filters)
 * @param string $displayType
 * @return string
 */
	public function createTimeline($modelName, $displayFields, $dataCollection, $timelineContainerCssSelector, $displayTypeCheckerCssSelector, $pageRedirectBaseUrl = '', $level = "ALL", $startDate = null, $endDate = null, $displayType = ""){

		$modelNameTxt 			= strtolower(Inflector::humanize(Inflector::underscore(Inflector::pluralize($modelName))));
		$modelNameTxtSingular 	= strtolower(Inflector::humanize(Inflector::underscore($modelName)));

		$datePickerId			= $modelNameTxtSingular .'-' .time();

		if(empty($displayType)){
			$displayType = "PREMIUM"; // Make sure we can send a display type to controller, not let that argument be empty
		}

		$output = $this->Html->css('/css/admin/datepicker.css', array('block' => null));
		$output .= $this->Html->css('/css/admin/bootstrap-timepicker.css', array('block' => null));
		$output .= $this->Html->css('/css/admin/daterangepicker.css', array('block' => null));
		$output .= $this->Html->css('/css/admin/bootstrap-datetimepicker.css', array('block' => null));

		$output .= '<br />';
		$output .= '<div class="row timeline-container no-border">';
		$output .= '	<div class="col-xs-12 col-sm-10 col-sm-offset-1">';
		$output .= '		<div class="col-xs-8 col-sm-2">';
		$output .= '			<div class="btn-group level-selection">';
		$output .= '				<button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle" aria-expanded="false">';
		$output .= '					<span>' .((empty($level) || $level == "ALL") ? __('Any ' .$modelNameTxtSingular .' level') : __(ucfirst(strtolower($level)))) .'</span><i class="ace-icon fa fa-angle-down icon-on-right"></i>';
		$output .= '				</button>';
		$output .= '				<ul class="dropdown-menu">';
		$output .= '					<li><a href="#" data-value="ALL"><i class="timeline-indicator ace-icon fa fa-adn btn btn-primary no-hover"></i><span>' .__('Any ' .$modelNameTxtSingular .' level') .'</span></a>';
		$output .= '					<li><a href="#" data-value="' .Configure::read('System.log.level.critical') .'"><i class="timeline-indicator ace-icon fa fa-exclamation btn btn-danger no-hover"></i><span>' .__(ucfirst(strtolower(Configure::read('System.log.level.critical')))) .'</span></a>';
		$output .= '					<li><a href="#" data-value="' .Configure::read('System.log.level.error') .'"><i class="timeline-indicator ace-icon fa fa-close btn btn-danger no-hover"></i><span>' .__(ucfirst(strtolower(Configure::read('System.log.level.error')))) .'</span></a>';
		$output .= '					<li><a href="#" data-value="' .Configure::read('System.log.level.warning') .'"><i class="timeline-indicator ace-icon fa fa-exclamation-triangle btn btn-warning no-hover"></i><span>' .__(ucfirst(strtolower(Configure::read('System.log.level.warning')))) .'</span></a>';
		$output .= '					<li><a href="#" data-value="' .Configure::read('System.log.level.info') .'"><i class="timeline-indicator ace-icon fa fa-info btn btn-light no-hover"></i><span>' .__(ucfirst(strtolower(Configure::read('System.log.level.info')))) .'</span></a>';
		if(!$this->Permissions->isClient() && !$this->Permissions->isStaff()){
			$output .= '				<li><a href="#" data-value="' .Configure::read('System.log.level.debug') .'"><i class="timeline-indicator ace-icon fa fa-wrench btn btn-inverse no-hover"></i><span>' .__(ucfirst(strtolower(Configure::read('System.log.level.debug')))) .'</span></a>';
		}
		$output .= '				</ul>';
		$output .= '			</div>';
		$output .= '		</div>';
		$output .= '		<div class="col-xs-8 col-sm-4">';
		$output .= '			<div class="input-group">';
		$output .= '				<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>';
		$output .= '				<input class="form-control active" type="text" name="date-range-picker" id="' .$datePickerId .'" placeholder="&nbsp;' .__('Jump to date') .'" value="' .((!empty($startDate) && !empty($endDate)) ? $this->Time->i18nFormat($startDate, '%x') .' - ' .$this->Time->i18nFormat($endDate, '%x') : "") .'">';
		$output .= '			</div>';
		$output .= '		</div>';
		$output .= '	</div>';
		$output .= '</div>';

		$dateContainerStart = '<div class="row">';
		$dateContainerStart .= '	<div class="col-xs-12 col-sm-10 col-sm-offset-1">';
		$dateContainerStart .= '		<div class="timeline-container' .(($displayType == "SIMPLE") ? " timeline-style2" : "") .'">';
		$dateContainerStart .= '			<div class="timeline-label">';
		if($displayType == "SIMPLE"){
			$dateContainerStart .= '			<b>[DATE]</b>';
		}else{
			$dateContainerStart .= '			<span class="label label-primary arrowed-in-right label-lg"><b>[DATE]</b></span>';
		}
		$dateContainerStart .= '			</div>';
		$dateContainerStart .= '			<div class="timeline-items">';

		$dateContainerEnd = '				</div><!-- timeline-items -->';
		$dateContainerEnd .= '			</div>';
		$dateContainerEnd .= '		</div>';
		$dateContainerEnd .= ' </div>';

		$dateStart = false; // Used to tell whether we need to start a new date structure
		$dateEnd = true; // Used to tell whether the current date structure has done
		$dateText = null;

		foreach($dataCollection as $data){

			if(!$dateStart && $dateEnd){

				$dateStart = true;
				$dateEnd = false;

				$dateText = $this->getDateText($data[$modelName][$displayFields['TIMESTAMP']]);

				$output .= str_replace("[DATE]", $dateText, $dateContainerStart);

			}elseif($dateStart && !$dateEnd && !empty($dateText)){

				$tmpDataText = $this->getDateText($data[$modelName][$displayFields['TIMESTAMP']]);
				if($tmpDataText != $dateText && !empty($tmpDataText)){

					$output .= $dateContainerEnd;

					$output .= str_replace("[DATE]", $tmpDataText, $dateContainerStart);

					$dateText = $tmpDataText;
				}

			}

			$recordTime = date("H:i:s", strtotime($data[$modelName][$displayFields['TIMESTAMP']]));

			$levelFlag = '';
			switch($data[$modelName][$displayFields['LEVEL']]){
				case Configure::read('System.log.level.critical'):
					$levelFlag = 'fa-exclamation btn btn-danger';
					break;
				case Configure::read('System.log.level.error'):
					$levelFlag = 'fa-close btn btn-danger';
					break;
				case Configure::read('System.log.level.warning'):
					$levelFlag = 'fa-exclamation-triangle btn btn-warning';
					break;
				case Configure::read('System.log.level.info'):
					$levelFlag = 'fa-info btn btn-light';
					break;
				case Configure::read('System.log.level.debug'):
					$levelFlag = 'fa-wrench btn btn-inverse';
					break;
			}

			$output .= '			<div class="timeline-item clearfix" data-date="' .$dateText .'">';
			$output .= '				<div class="timeline-info">';
			if($displayType == "SIMPLE"){
				$output .= '				<span class="timeline-date">' .$recordTime .'</span>';
			}
			$output .= '					<i class="timeline-indicator ace-icon fa ' .$levelFlag .' no-hover"></i>';
			$output .= '				</div>';
			$output .= '				<div class="widget-box transparent">';

			if($displayType == "SIMPLE"){

				$output .= '				<div class="widget-body">';
				$output .= '					<div class="widget-main no-padding">';
				$output .= '						' .($this->Permissions->isAdmin() ? "[" .$data[$modelName][$displayFields['TITLE']] ."]&nbsp;&nbsp;" : "") .$data[$modelName][$displayFields['CONTENT']];
				$output .= '					</div>';
				$output .= '				</div>';

			}

			if($displayType == "PREMIUM"){
				if($this->Permissions->isAdmin() && !empty($data[$modelName][$displayFields['TITLE']])){
					$output .= '			<div class="widget-header widget-header-small">';
					$output .= '				<h5 class="widget-title smaller">' .$data[$modelName][$displayFields['TITLE']] .'</h5>';
					$output .= '				<span class="widget-toolbar no-border">';
					$output .= '					<i class="ace-icon fa fa-clock-o bigger-110"></i>&nbsp;' .$recordTime;
					$output .= '				</span>';
					$output .= '				<span class="widget-toolbar">';
					$output .= '					<a href="#" data-action="collapse">';
					$output .= '						<i class="ace-icon fa fa-chevron-up"></i>';
					$output .= '					</a>';
					$output .= '				</span>';
					$output .= '			</div>';
				}

				$output .= '				<div class="widget-body">';
				$output .= '					<div class="widget-main">';
				$output .= '						<div>' .$data[$modelName][$displayFields['CONTENT']] .'</div>';

				if($this->Permissions->isClient() || empty($data[$modelName][$displayFields['TITLE']])){
					$output .= '					<div class="pull-right">';
					$output .= '						<i class="ace-icon fa fa-clock-o bigger-110"></i>&nbsp;' .$recordTime;
					$output .= '					</div>';
				}

				$output .= '					</div>';
				$output .= '				</div>';
			}

			$output .= '				</div>';
			$output .= '			</div><!-- timeline-item -->';

		}

		// Make sure the date container has been completed.
		if($dateStart && !$dateEnd){
			$dateStart = false;
			$dateEnd = true;
			$output .= $dateContainerEnd;
		}

		$output .=	$this->Paginator->next(__('Show more ' .$modelNameTxt));

		$output .= '<div class="page-load-status">';
		$output .= '	<p class="infinite-scroll-request">' .__('Loading...') .'</p>';
		$output .= '	<p class="infinite-scroll-last">' .__('End of ' .$modelNameTxtSingular) .'</p>';
		$output .= '	<p class="infinite-scroll-error">' .__('No more ' .$modelNameTxt .' to load') .'</p>';
		$output .= '</div>';

		$output .= $this->Minify->script(array('admin/jquery.infinite-scroll.pkgd'/*, 'admin/jquery.infinitescroll.min'*/, 'admin/date-time/moment', 'admin/date-time/bootstrap-datepicker', 'admin/date-time/bootstrap-timepicker', 'admin/date-time/daterangepicker', 'admin/date-time/bootstrap-datetimepicker'));

		$output .= '<script>';
		$inlineJavaScript = '	$(function(){';
		$inlineJavaScript .= '		var $container = $(\'' .$timelineContainerCssSelector .'\').children(".row").last().find(".timeline-container").first().find(".timeline-items").first();';
		$inlineJavaScript .= '		$container.infiniteScroll({';
		$inlineJavaScript .= '			path  				: \'.next a\',';
		$inlineJavaScript .= '			nextSelector 		: \'.next a\',';
		$inlineJavaScript .= '			append 				: \'.timeline-item\',';
		$inlineJavaScript .= '			hideNav		 		: \'.next\',';
		$inlineJavaScript .= '			status		 		: \'.page-load-status\',';
		$inlineJavaScript .= '			debug		 		: false,';
		$inlineJavaScript .= '			itemDateWrapper		: \'' .$dateContainerStart .$dateContainerEnd .'\',';
		$inlineJavaScript .= '			timelineContainerCssSelector  : \'' .$timelineContainerCssSelector .'\'';
		$inlineJavaScript .= '		});';
		$inlineJavaScript .= '	';
		$inlineJavaScript .= '		var displayType = "' .$displayType .'";';
		$inlineJavaScript .= '		if($("' .$displayTypeCheckerCssSelector .'").length){';
		$inlineJavaScript .= '			$("' .$displayTypeCheckerCssSelector .'").each(function(){';
		$inlineJavaScript .= '				if($(this).hasClass("active")){';
		$inlineJavaScript .= '					displayType = $(this).attr("data-displayType");';
		$inlineJavaScript .= '				}';
		$inlineJavaScript .= '			});';
		$inlineJavaScript .= '			$("' .$displayTypeCheckerCssSelector .'").bind("click", function(){';
		$inlineJavaScript .= '				displayType = $(this).attr("data-displayType");';
		$inlineJavaScript .= '			});';
		$inlineJavaScript .= '		}';
		$inlineJavaScript .= '		var timelineLevelValue = "ALL";';
		$inlineJavaScript .= '		$(".level-selection ul.dropdown-menu li a").click(function(){';
		$inlineJavaScript .= '			var target = $(this);';
		$inlineJavaScript .= '			$(".level-selection button span").text(target.children("span").text());';
		$inlineJavaScript .= '			timelineLevelValue = target.attr("data-value");';
		$inlineJavaScript .= '			if(timelineLevelValue){';
		$inlineJavaScript .= '				var url = window.location.pathname;';
		$inlineJavaScript .= '				var anchor = document.URL.split(url).last();';
		$inlineJavaScript .= '				if(url + anchor + "/" == "' .$pageRedirectBaseUrl .'"){';
		$inlineJavaScript .= '					window.location.href = "' .$pageRedirectBaseUrl .'" + displayType + "/" + timelineLevelValue;';
		$inlineJavaScript .= '					return false;';
		$inlineJavaScript .= '				}else{';
		$inlineJavaScript .= '					var anchorElements = anchor.split("/");';
		$inlineJavaScript .= '					var reloadURL = "' .$pageRedirectBaseUrl .'" + displayType + "/" + timelineLevelValue;';
		$inlineJavaScript .= '					if(anchorElements.length > 7){';
		$inlineJavaScript .= '						reloadURL += (anchorElements[anchorElements.length - 2] ? "/" + anchorElements[anchorElements.length - 2] : "") + (anchorElements[anchorElements.length - 1] ? "/" + anchorElements[anchorElements.length - 1] : "");';
		$inlineJavaScript .= '					}';
		$inlineJavaScript .= '					window.location.href = reloadURL;';
		$inlineJavaScript .= '					return false;';
		$inlineJavaScript .= '				}';
		$inlineJavaScript .= '			}';
		$inlineJavaScript .= '		});';
		$inlineJavaScript .= '	';
		$inlineJavaScript .= '		$(\'#' .$datePickerId .'\').daterangepicker({';
		$inlineJavaScript .= '			format: \'DD/MM/YYYY\',';
		$inlineJavaScript .= '			locale: {';
		$inlineJavaScript .= '				applyLabel: \'' .__('Apply') .'\',';
		$inlineJavaScript .= '				cancelLabel: \'' .__('Cancel') .'\'';
		$inlineJavaScript .= '			},';
		$inlineJavaScript .= '			\'startDate\' : \'' .(empty($startDate) ? date('d/m/Y') : date('d/m/Y', strtotime($startDate))) .'\',';
		$inlineJavaScript .= '			\'endDate\' : \'' .(empty($endDate) ? date('d/m/Y') : date('d/m/Y', strtotime($endDate))) .'\',';
		$inlineJavaScript .= '			\'autoUpdateInput\' : false,';
		$inlineJavaScript .= '			\'applyClass\' : \'btn-sm btn-success\',';
		$inlineJavaScript .= '			\'cancelClass\' : \'btn-sm btn-default\',';
		$inlineJavaScript .= '			\'opens\' : \'left\',';
		$inlineJavaScript .= '			\'maxDate\' : \'' .date('d/m/Y') .'\'';
		$inlineJavaScript .= '		}, function(start, end, label) {';
		if(Configure::read('debug')){
			$inlineJavaScript .= '		console.log(\'New date range selected: \' + start.format(\'YYYY-MM-DD\') + \' to \' + end.format(\'YYYY-MM-DD\') + \' (predefined range: \' + label + \')\');';
		}
		$inlineJavaScript .= '			window.location.href = \'' .$pageRedirectBaseUrl .'\' + displayType + "/" + (timelineLevelValue ? timelineLevelValue : "ALL") + "/" + start.format(\'YYYY-MM-DD\') + \'/\' + end.format(\'YYYY-MM-DD\');';
		$inlineJavaScript .= '		});';
		$inlineJavaScript .= '	});';
		$output .= $this->Minify->minifyInlineJS($inlineJavaScript);
		$output .= '</script>';

		return $output;

	}

	private function getDateText($timestamp){
		$today = date('Y-m-d');
		$yesterday = date('Y-m-d', strtotime("-1 day"));
		$dateText = '';

		$recordDate = date('Y-m-d', strtotime($timestamp));
		if($recordDate == $today){
			$dateText = __('Today');
		}elseif ($yesterday == $recordDate){
			$dateText = __('Yesterday');
		}else{
			$dateText = $this->Time->niceShort(date('Y-m-d', strtotime($recordDate)));
			$dateText = str_replace(", 00:00", "", $dateText); // No time displayed
		}

		return $dateText;
	}

}
?>