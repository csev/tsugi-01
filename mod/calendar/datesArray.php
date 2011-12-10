<?php

#Input: number of days to display
#Output: array of size(input) of date strings
function make_date($dates_request){

	$date_array = array();
	$cur_month = date('n');		
	$cur_day = date('j');
	$cur_year = date('Y');
	$leap_true  = date('L');
	$num_days = 0;
	
	//Loop that determines next $num_days upcoming dates
	while ($num_days < $dates_request) {	

		$format_date = $cur_month."/".$cur_day."/".$cur_year;			
		$date_array[] = $format_date;		

		//months with 30 days
		if ($cur_month == 9 || $cur_month == 4 || $cur_month == 6) {
			if ($cur_day == 30) {
				$cur_day = 1;
				$cur_month = $cur_month+1;
			}
			else {
				$cur_day = $cur_day+1;
			}
		}
		//december
		else if ($cur_month == 12) {
			if ($cur_day == 31) {
				$cur_day = 1;
				$cur_month = 1;
				$cur_year = $cur_year+1;
			}
			else {
				$cur_day = $cur_day+1;
			}
		}
		//february		
		else if ($cur_month == 2) {
			if ($leap_true == true) {
				if ($cur_day == 29) {
					$cur_day=1;
					$cur_month=$cur_month+1;
				}
				else {
					$cur_day = $cur_day+1;
				}
			}
			else {
				if ($cur_day == 28) {
					$cur_day = 1;
					$cur_month=$cur_month+1;
				}
				else {
					$cur_day = $cur_day+1;
				}
			}
		}
		//other months		
		else {
			if ($cur_day == 31) {
				$cur_day = 1;
				$cur_month=$cur_month+1;
			}
			else {
				$cur_day = $cur_day+1;
			}
		}
	$num_days = $num_days+1;
	}
	return $date_array;
}

?>