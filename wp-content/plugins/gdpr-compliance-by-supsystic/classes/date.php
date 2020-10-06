<?php
class dateGrs {
	static public function _($time = NULL) {
		if(is_null($time)) {
			$time = time();
		}
		return date(GRS_DATE_FORMAT_HIS, $time);
	}
}