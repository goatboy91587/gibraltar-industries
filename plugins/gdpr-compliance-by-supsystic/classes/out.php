<?php
class outGrs {
	static public function _($data) {
		if(is_array($data)) {
			foreach($data as $i => $d) {
				$data[ $i ] = self::_($d);
			}
			return $data;
		} else
			return esc_html(stripslashes($data));
	}
	static public function _js($data) {
		if(is_array($data)) {
			foreach($data as $i => $d) {
				$data[ $i ] = self::_js($d);
			}
			return $data;
		} else
			return stripslashes($data);
	}
}