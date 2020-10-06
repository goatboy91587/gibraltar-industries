<?php
class mailControllerGrs extends controllerGrs {
	public function testEmail() {
		$res = new responseGrs();
		$email = reqGrs::getVar('test_email', 'post');
		if($this->getModel()->testEmail($email)) {
			$res->addMessage(__('Now check your email inbox / spam folders for test mail.'));
		} else 
			$res->pushError ($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function saveMailTestRes() {
		$res = new responseGrs();
		$result = (int) reqGrs::getVar('result', 'post');
		frameGrs::_()->getModule('options')->getModel()->save('mail_function_work', $result);
		$res->ajaxExec();
	}
	public function saveOptions() {
		$res = new responseGrs();
		$optsModel = frameGrs::_()->getModule('options')->getModel();
		$submitData = reqGrs::get('post');
		if($optsModel->saveGroup($submitData)) {
			$res->addMessage(__('Done', GRS_LANG_CODE));
		} else
			$res->pushError ($optsModel->getErrors());
		$res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			GRS_USERLEVELS => array(
				GRS_ADMIN => array('testEmail', 'saveMailTestRes', 'saveOptions')
			),
		);
	}
}
