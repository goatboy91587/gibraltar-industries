<?php
class gdprControllerGrs extends controllerGrs {
	public function save() {
		$res = new responseGrs();
		if($this->getModel()->save(reqGrs::get('post'))) {
			$res->addMessage(__('Done', GRS_LANG_CODE));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			GRS_USERLEVELS => array(
				GRS_ADMIN => array('save')
			),
		);
	}
}

