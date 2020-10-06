<?php
class mailViewGrs extends viewGrs {
	public function getTabContent() {
		frameGrs::_()->getModule('templates')->loadJqueryUi();
		frameGrs::_()->addScript('admin.'. $this->getCode(), $this->getModule()->getModPath(). 'js/admin.'. $this->getCode(). '.js');
		
		$this->assign('options', frameGrs::_()->getModule('options')->getCatOpts( $this->getCode() ));
		$this->assign('testEmail', frameGrs::_()->getModule('options')->get('notify_email'));
		return parent::getContent('mailAdmin');
	}
}
