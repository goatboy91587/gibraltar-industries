<?php
class gdprViewGrs extends viewGrs {
	
	public function getSettingsTabContent() {
		dispatcherGrs::addAction('afterAdminBreadcrumbs', array($this, 'showSettingsFormControls'));
		
		frameGrs::_()->addScript('admin.gdpr.settings', $this->getModule()->getModPath(). 'js/admin.gdpr.settings.js');
		frameGrs::_()->addJSVar('admin.gdpr.settings', 'grsRichEditNames', $this->getModule()->getRichEditorNames());
		frameGrs::_()->addStyle('admin.gdpr.settings', $this->getModule()->getModPath(). 'css/admin.gdpr.settings.css');
		frameGrs::_()->getModule('templates')->loadJqueryUi();
		frameGrs::_()->getModule('templates')->loadBootstrapSimple();
		frameGrs::_()->addScript('wp.tabs', GRS_JS_PATH. 'wp.tabs.js');
		
		$this->assign('tabs', array(
			'main' => array(
				'fa_icon' => 'fa-gear', 'title' => __('Main', GRS_LANG_CODE), 'content' => $this->_getOptsListContent('main'),
			),
			'design' => array(
				'fa_icon' => 'fa-pencil', 'title' => __('Design', GRS_LANG_CODE), 'content' => $this->_getOptsListContent('design'),
			),
			'buttons' => array(
				'fa_icon' => 'fa-check-circle', 'title' => __('Buttons', GRS_LANG_CODE), 'content' => $this->_getOptsListContent('btns'),
			),
			'agreements-and-scripts' => array(
				'fa_icon' => 'fa-thumbs-up', 'title' => __('Agreements and Scripts', GRS_LANG_CODE), 'content' => $this->_getAgreementsAndScriptsTab(),
			),
		));
		return parent::getContent('gdprSettingsTabContent');
	}
	public function showSettingsFormControls() {
		parent::display('gdprSettingsFormControls');
	}
	private function _getOptsListContent($category) {
		$this->assign('options', frameGrs::_()->getModule('options')->getCatOpts($category));
		return parent::getContent('gdprOptsList');
	}
	public function generateHtml($notify) {
		$this->assign('opts', $notify['opts']);
		$this->assign('agree', $notify['agree']);
		return parent::getContent('gdprHtml');
	}
	public function _getAgreementsAndScriptsTab() {
		$def = array(
			array(
				'label' => __('Global', GRS_LANG_CODE),
				'desc' => __('This will be main scripts to load if user agree to your Privacy Settings.', GRS_LANG_CODE),
				'scripts_header' => '',
				'scripts_footer' => '',
				'is_global' => 1,
				'enb' => true,
			),
			array(
				'label' => __('Analytics', GRS_LANG_CODE),
				'desc' => __('We\'d like to improve your experience using browsing history on our site and your general location (region). We\'ll also store data about your device and browser to recognize returning visitors.', GRS_LANG_CODE),
				'scripts_header' => '',
				'scripts_footer' => '',
				'enb' => false,
			),
			array(
				'label' => __('Remarketing', GRS_LANG_CODE),
				'desc' => __('We\'d like to show you our advertisements – and only ours – on other websites. We will use your browsing history on our site to make these advertisements relevant to your interests.', GRS_LANG_CODE),
				'scripts_header' => '',
				'scripts_footer' => '',
				'enb' => false,
			),
		);
		$agreements = $this->getModel()->getAgreements();
		if(empty($agreements))
			$agreements = $def;
		$agreements = $this->_prepareAgreementsOut($agreements);
		frameGrs::_()->addScript('admin.gdpr.agreements', $this->getModule()->getModPath(). 'js/admin.gdpr.agreements.js');
		frameGrs::_()->addJSVar('admin.gdpr.agreements', 'grsAgreements', $agreements);
		return parent::getContent('gdprAgreementAndScripts');
	}
	private function _prepareAgreementsOut($agreements) {
		foreach($agreements as $i => $a) {
			foreach($a as $k => $v) {
				if(is_string($v)) {
					$agreements[ $i ][ $k ] = outGrs::_js($v);
				}
			}
		}
		return $agreements;
	}
}
