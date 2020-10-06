<?php
class optionsControllerGrs extends controllerGrs {
	public function saveGroup() {
		$res = new responseGrs();
		if($this->getModel()->saveGroup(reqGrs::get('post'))) {
			$res->addMessage(__('Done', GRS_LANG_CODE));
		} else
			$res->pushError ($this->getModel('options')->getErrors());
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			GRS_USERLEVELS => array(
				GRS_ADMIN => array('saveGroup')
			),
		);
	}
}

