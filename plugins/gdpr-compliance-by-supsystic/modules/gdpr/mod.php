<?php
class gdprGrs extends moduleGrs {
	private $_buttons = array();
	private $_gdprOptsCats = array();
	// Options that is using TimyMCE editor in admin area
	private $_richEditorNames = array('cookie_txt');
	private $_deside = array();
	
	public function __construct($d) {
		parent::__construct($d);
		$this->_initDeside();
		dispatcherGrs::addFilter('optionsDefine', array($this, 'addOptions'));
	}
	public function init() {
		dispatcherGrs::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		$mainCheckActionName = (defined('WP_USE_THEMES') && WP_USE_THEMES) ? 'template_redirect' : 'get_header';
		add_action($mainCheckActionName, array($this, 'checkGdprShow'));
		add_action('wp_head', array($this, 'printHeaderScripts'));
		add_action('wp_footer', array($this, 'printFooterScripts'));
	}
	public function addAdminTab($tabs) {
		$tabs['gdpr-settings'] = array(
			'label' => __('Settings', GRS_LANG_CODE), 'callback' => array($this, 'getSettingsTabContent'), 'fa_icon' => 'fa-gear', 'sort_order' => 30,
		);
		return $tabs;
	}
	public function getSettingsTabContent() {
		return $this->getView()->getSettingsTabContent();
	}
	public function addOptions($opts) {
		
		$opts['main'] = array(
			'label' => __('Main', GRS_LANG_CODE),
			'opts' => array(
				'enb_cookie_bar' => array('label' => __('Enable Cookie Notification', GRS_LANG_CODE), 'desc' => __('Show notification about GDPR and Cookie usage for Your site users. This actually allow you enable/disable main plugin functionalities.', GRS_LANG_CODE), 'def' => '1', 'html' => 'checkboxHiddenVal', 'type' => 'bool'),

				'enb_block_content' => array('label' => __('Enable Block Content', GRS_LANG_CODE), 'desc' => __('Use this option ONLY if you understand how it work! This will block 3rd part resources and remove all unapproved cookies if user rejected your GDPR Policy. Read more about this option <a href="https://supsystic.com/blog/gdpr-to-be-continued-or-how-to-fight-against-cookies/" target="_blank">here.</a>', GRS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal', 'type' => 'bool'),
				'enb_block_resources' => array('label' => __('Enable Block 3rd part Resources', GRS_LANG_CODE), 'desc' => __('Remove all links to external resources from your site if user did not agree wth your GDPR Policies (such as Google Analytics, Youtube, etc.)', GRS_LANG_CODE), 'def' => '1', 'html' => 'checkboxHiddenVal', 'type' => 'bool', 'connect' => 'enb_block_content:1'),
				'enb_block_cookie' => array('label' => __('Enable Block Cookies', GRS_LANG_CODE), 'desc' => __('Clear all Cookies for your visitors on your site domain if user did not agree wth your GDPR Policies', GRS_LANG_CODE), 'def' => '1', 'html' => 'checkboxHiddenVal', 'type' => 'bool', 'connect' => 'enb_block_content:1'),
				'enb_block_without_agree' => array('label' => __('Block also without Agree', GRS_LANG_CODE), 'desc' => __('By default this options will block GDPR disallowed content only if user Rejected your Policies. But - you can enable this one and all required content will be blocked until user will not Agree with your Policies.', GRS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal', 'type' => 'bool', 'connect' => 'enb_block_content:1'),
				
				'bar_delay_hide' => array('label' => __('Auto-Hide on Delay', GRS_LANG_CODE), 'desc' => __('Hide Cookie bar after time passed.', GRS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal', 'type' => 'bool'),
				'bar_delay_hide_time' => array('label' => __('Time before Hide (ms)', GRS_LANG_CODE), 'desc' => __('Time passed before hide in miliseconds.', GRS_LANG_CODE), 'def' => '1000', 'html' => 'text', 'connect' => 'bar_delay_hide:1', 'type' => 'int'),
				
				'enb_show_again_tab' => array('label' => __('Enable Show Again Tab', GRS_LANG_CODE), 'desc' => __('It will appear if user will close main Cookie Bar and allow to open it again.', GRS_LANG_CODE), 'def' => '1', 'html' => 'checkboxHiddenVal', 'type' => 'bool'),
				'show_again_tab_pos' => array('label' => __('Show Again Tab Position', GRS_LANG_CODE), 'desc' => __('Position of Show Again tab.', GRS_LANG_CODE), 'def' => 'right', 'html' => 'selectbox', 'connect' => 'enb_show_again_tab:1', 'options' => array(
					'left' => __('Left', GRS_LANG_CODE), 'right' => __('Right', GRS_LANG_CODE),
				)),
				'show_again_tab_txt' => array('label' => __('Show Again Text', GRS_LANG_CODE), 'desc' => __('Text that will be show on Show Again tab.', GRS_LANG_CODE), 'def' => __('Privacy Policy', GRS_LANG_CODE), 'html' => 'text', 'connect' => 'enb_show_again_tab:1'),
				
				'cookie_txt' => array('label' => __('Message to show to Users', GRS_LANG_CODE), 'desc' => __('This will appear in your Cookie Tab.', GRS_LANG_CODE), 'def' => 'This website uses cookies to improve your experience. We\'ll only use your data for purposes you consent to.', 'html' => 'wp_editor'),
			),
		);
		$opts['design'] = array(
			'label' => __('Design', GRS_LANG_CODE),
			'opts' => array(
				'show_as' => array('label' => __('Show As'), 'desc' => __('Possibility to show as notify Bar or PopUp Window on your site.', GRS_LANG_CODE), 'html' => 'radiobuttons', 'def' => 'bar', 'options' => array(
					'bar' => __('Bar', GRS_LANG_CODE), 'popup' => __('PopUp', GRS_LANG_CODE),
				)),
				
				'bar_pos' => array('label' => __('Cookie Notification Position', GRS_LANG_CODE), 'desc' => __('Position of your Cookie bar on Frontend.', GRS_LANG_CODE), 'def' => 'bottom', 'html' => 'selectbox', 'connect' => 'show_as:bar', 'options' => array(
					'top' => __('Top', GRS_LANG_CODE), 'bottom' => __('Bottom', GRS_LANG_CODE),
				)),
				
				'main_color' => array('label' => __('Background Color', GRS_LANG_CODE), 'desc' => __('Main color for Cookie Notification Background.', GRS_LANG_CODE), 'def' => '#fff', 'html' => 'colorpicker'),
				'text_color' => array('label' => __('Text Color', GRS_LANG_CODE), 'desc' => __('Text color.', GRS_LANG_CODE), 'def' => '#000', 'html' => 'colorpicker'),
				'enb_border' => array('label' => __('Enable Border', GRS_LANG_CODE), 'desc' => __('Borders around Cookie Bar.', GRS_LANG_CODE), 'def' => '1', 'html' => 'checkboxHiddenVal', 'type' => 'bool'),
				'border_color' => array('label' => __('Border Color', GRS_LANG_CODE), 'desc' => __('Borders color.', GRS_LANG_CODE), 'def' => '#444', 'html' => 'colorpicker', 'connect' => 'enb_border:1'),
				
				'animation' => array('label' => __('Appearance Animation', GRS_LANG_CODE), 'desc' => __('Setup animation for your Notification Bar or PopUp - it will look more pretty. But sure - you can leave here "None" to disable animation at all.', GRS_LANG_CODE), 'def' => 'slide', 'html' => 'selectbox','options' => array(
					'none' => __('None', GRS_LANG_CODE), 'slide' => __('Slide', GRS_LANG_CODE), 'fade' => __('Fade (In/Out)', GRS_LANG_CODE),
				)),
				'animation_duration' => array('label' => __('Border Color', GRS_LANG_CODE), 'desc' => __('Borders color.', GRS_LANG_CODE), 'def' => '#444', 'html' => 'colorpicker', 'connect' => 'enb_border:1'),
			),
		);
		$opts['btns'] = array(
			'label' => __('Buttons', GRS_LANG_CODE),
			'opts' => array(
				
			),
		);
		
		$this->getButtons();
		foreach($this->_buttons as $k => $b) {
			$connect = $k. '_enb:1';
			$opts['btns']['opts'][$k. '_enb'] = array('label' => $b['label'], 'desc' => $b['desc'], 'def' => $b['enb'] ? '1' : '0', 'html' => 'checkboxHiddenVal', 'type' => 'bool');
			$opts['btns']['opts'][$k. '_lbl'] = array('label' => sprintf(__('"%s" button label', GRS_LANG_CODE), $b['label']), 'desc' => __('You can change button name here.', GRS_LANG_CODE), 'def' => $b['label'], 'html' => 'text', 'connect' => $connect);
			$opts['btns']['opts'][$k. '_color_bg'] = array('label' => sprintf(__('"%s" button Color', GRS_LANG_CODE), $b['label']), 'desc' => __('Main (background) color for button.', GRS_LANG_CODE), 'def' => $b['bg_color'], 'html' => 'colorpicker', 'connect' => $connect);
			$opts['btns']['opts'][$k. '_color_txt'] = array('label' => sprintf(__('"%s" button Text Color', GRS_LANG_CODE), $b['label']), 'desc' => __('Text color.', GRS_LANG_CODE), 'def' => $b['txt_color'], 'html' => 'colorpicker', 'connect' => $connect);
			$opts['btns']['opts'][$k. '_lnk_style'] = array('label' => sprintf(__('Link Style for "%s"', GRS_LANG_CODE), $b['label']), 'desc' => __('If enabled - will be shown as link (not as button) on frontend.', GRS_LANG_CODE), 'def' => $b['lnk_style'] ? '1' : '0', 'html' => 'checkboxHiddenVal', 'connect' => $connect, 'type' => 'bool');
			$opts['btns']['opts'][$k. '_new_line'] = array('label' => __('New Line', GRS_LANG_CODE), 'desc' => __('Show button on it\'s own (separate) line.', GRS_LANG_CODE), 'def' => $b['new_line'] ? '1' : '0', 'html' => 'checkboxHiddenVal', 'connect' => $connect, 'type' => 'bool');
			if($k == 'terms') {
				$opts['btns']['opts'][$k. '_url'] = array('label' => __('Privacy Page URL', GRS_LANG_CODE), 'desc' => __('Create (if it was not done before) page with your Privacy Settings and paste URL - here.', GRS_LANG_CODE), 'def' => GRS_SITE_URL, 'html' => 'text', 'connect' => $connect);
				$opts['btns']['opts'][$k. '_blank'] = array('label' => __('Open in new Page (Tab)', GRS_LANG_CODE), 'desc' => __('If checked - Privacy URL will be opened on new page.', GRS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal', 'connect' => $connect, 'type' => 'bool');
			}
		}
		
		$this->_gdprOptsCats = array('main', 'design', 'btns');
		return $opts;
	}
	public function getButtons() {
		if(empty($this->_buttons)) {
			$this->_buttons = array(
				'accept_all' => array('label' => __('Accept', GRS_LANG_CODE), 'desc' => __('This will accept all Policies and allow all Cookies.', GRS_LANG_CODE), 'bg_color' => '#0085ba', 'txt_color' => '#fff', 'enb' => true, 'lnk_style' => false, 'new_line' => false),
				'save_decision' => array('label' => __('Save Decision', GRS_LANG_CODE), 'desc' => __('This will save selected cookies (if there was such) and close tab.', GRS_LANG_CODE), 'bg_color' => '#f7f7f7', 'txt_color' => '#555', 'enb' => true, 'lnk_style' => false, 'new_line' => false),
				'reject' => array('label' => __('Reject', GRS_LANG_CODE), 'desc' => __('This will reject all cookies.', GRS_LANG_CODE), 'bg_color' => '#f7f7f7', 'txt_color' => '#555', 'enb' => false, 'lnk_style' => false, 'new_line' => false),
				'terms' => array('label' => __('Learn more on Privacy Policy page.', GRS_LANG_CODE), 'desc' => __('This will open Privacy Policy page.', GRS_LANG_CODE), 'bg_color' => '#f7f7f7', 'txt_color' => '#555', 'enb' => true, 'lnk_style' => true, 'new_line' => true),
			);
		}
		return $this->_buttons;
	}
	public function checkGdprShow() {
		if(frameGrs::_()->getModule('options')->get('enb_cookie_bar')
			&& !$this->acceptedAll()
		) {
			$notify = array('opts' => array());
			foreach($this->_gdprOptsCats as $optCat) {
				$notify['opts'][ $optCat ] = frameGrs::_()->getModule('options')->getCatOpts($optCat, true);
			}
			$notify['agree'] = $this->getModel()->getNonGlobalAgreements();
			$notify['opts'] = outGrs::_($notify['opts']);
			$notify['rendered_html'] = $this->getView()->generateHtml( $notify );
			$this->getButtons();
			$notify['btns'] = array();
			foreach($this->_buttons as $bK => $b) {
				$notify['btns'][] = $bK;
			}
			frameGrs::_()->setMinify(GRS_MINIFY_ASSETS)->addScript('frontend.gdpr', $this->getModPath(). 'js/frontend.gdpr.js', array('jquery'));
			frameGrs::_()->addJSVar('frontend.gdpr', 'grsNotifyData', $notify);
			frameGrs::_()->setMinify(GRS_MINIFY_ASSETS)->addStyle('frontend.gdpr', $this->getModPath(). 'css/frontend.gdpr.css');
		}
	}
	public function getRichEditorNames() {
		return $this->_richEditorNames;
	}
	private function _initDeside() {
		$saved = reqGrs::getVar('grs_gdpr', 'cookie');
		if(!empty($saved)) {
			$this->_deside = $saved;
		}
	}
	public function acceptedAll() {
		if($this->_deside && $this->_deside['aa']) {
			return true;
		}
		return false;
	}
	public function printHeaderScripts() {
		$this->_printScripts('header');
	}
	public function printFooterScripts() {
		$this->_printScripts('footer');
	}
	private function _printScripts($from) {
		if($this->_deside 
			&& ($this->_deside['aa'] || !empty($this->_deside['a']))
		) {
			$agreements = $this->getModel()->getAgreements();
			if(!empty($agreements)) {
				$print = array();
				$assetKey = 'scripts_'. $from;
				foreach($agreements as $a) {
					if(isset($a['enb']) && $a['enb'] && isset($a[$assetKey]) && !empty($a[$assetKey])) {
						$a[$assetKey] = trim($a[$assetKey]);
						if(!empty($a[$assetKey])) {
							if($this->_deside['aa'] || (!$a['is_global'] && in_array(md5($a['label']), $this->_deside['a']))) {
								$print[] = $a[$assetKey];
							}
						}
					}
				}
				if(!empty($print)) {
					foreach($print as $p) {
						$p = outGrs::_js($p);
						if(strpos($p, '<script') === false) {
							$p = '<script>'. $p. '</script>';
						}
						echo $p;
					}
				}
			}
		}
	}
}

