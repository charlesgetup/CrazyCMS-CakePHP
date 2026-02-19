<?php
class DateTimeHandler {

/**
 * Validate give date string
 * @param unknown $date
 * @param string $format
 * @return boolean
 */
	public function validateDate($date, $format = 'Y-m-d H:i:s'){
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}

/**
 * Calculate the same day in the next month. If the date is not exist, make it the last day of the month
 */
	public function oneMonthLater($dateStr) {
		if(empty($dateStr) && !$this->validateDate($dateStr)){
			return null;
		}

		$dateArr 	= explode("-", date("Y-m-d", strtotime($dateStr)));
		$dateArr[1] = intval($dateArr[1]);
		$dateArr[2] = intval($dateArr[2]);
		$dateArr[1] = str_pad(strval(($dateArr[1] > 11) ? 1 : ($dateArr[1] + 1)), 2, "0", STR_PAD_LEFT);
		if(!checkdate($dateArr[1], $dateArr[2], $dateArr[0])){
			$dateArr[2] = date('t', strtotime($dateArr[0] ."-" .$dateArr[1] ."-01"));
			$dateArr[2] = str_pad(strval($dateArr[2]), 2, "0", STR_PAD_LEFT);
		}

		return implode("-", $dateArr);
	}

	public function secToHumanReadable($seconds){
		if(empty($seconds) || !is_numeric($seconds) || $seconds < 0){
			return false;
		}

		$hours 	 = floor($seconds / 3600);
		$minutes = floor(($seconds / 60) % 60);
		$seconds = $seconds % 60;

		return [$hours, $minutes, $seconds];
	}

	public function getSameDayInFollowingMonth($dateStr, $nextMonthAmount = 1){

		$curTimestamp = strtotime($dateStr);
		$currentYear  = intval(date('Y', $curTimestamp));
		$currentMonth = intval(date('m', $curTimestamp));
		$currentDay   = intval(date('d', $curTimestamp));

		$year  = 0;
		$month = 0;
		if($nextMonthAmount > 12){
			$year = intval($nextMonthAmount / 12);
			$month = $nextMonthAmount % 12;
		}else{
			$month = $nextMonthAmount;
		}

		$currentYear += $year;

		$targetMonth = $currentMonth + $month;
		if($targetMonth > 12){

			$currentYear++;
			$targetMonth -= 12;

		}

		$targetMonth = str_pad($targetMonth, 2, "0", STR_PAD_LEFT);
		$daysInTargetMonth = intval(date('t', strtotime("{$currentYear}-{$targetMonth}-01")));
		if($currentDay > $daysInTargetMonth){
			$currentDay = $daysInTargetMonth;
		}

		return "{$currentYear}-{$targetMonth}-{$currentDay}";
	}
}
?>