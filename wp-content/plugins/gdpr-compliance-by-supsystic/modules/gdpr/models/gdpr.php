<?php
class gdprModelGrs extends modelGrs {
	private $_agreements = array();
	private $_agreementsLoaded = false;
	public function save($d) {
		$richNames = $this->getModule()->getRichEditorNames();
		foreach($richNames as $name) {
			$d['opt_values'][ $name ] = $d['opt_values_txt_val'. $name];
		}
		frameGrs::_()->getModule('options')->getModel()->saveGroup($d);
		$this->setAgreements(isset($d['agreements']) ? $d['agreements'] : array());
	}
	public function getAgreements() {
		if(!$this->_agreementsLoaded) {
			$this->_agreementsLoaded = true;
			$this->_agreements = get_option('_grs_agreements', array());
		}
		return $this->_agreements;
	}
	public function setAgreements($agreements) {
		update_option('_grs_agreements', $agreements);
	}
	/*public function agreementsExists() {
		$agreements = $this->getAgreements();
		if(!empty($agreements)) {
			foreach($agreements as $a) {
				if(isset($a['enb']) && $a['enb'] && !$a['is_global']) {
					return true;
				}
			}
		}
		return false;
	}*/
	public function getNonGlobalAgreements() {
		$agreements = $this->getAgreements();
		$res = array();
		if(!empty($agreements)) {
			foreach($agreements as $a) {
				if(isset($a['enb']) && $a['enb'] && !$a['is_global']) {
					$res[] = $a;
				}
			}
		}
		return $res;
	}
}
