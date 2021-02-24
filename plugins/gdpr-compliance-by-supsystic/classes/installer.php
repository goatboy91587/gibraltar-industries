<?php
class installerGrs {
	static public $update_to_version_method = '';
	static private $_firstTimeActivated = false;
	static public function init( $isUpdate = false ) {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$current_version = get_option($wpPrefix. GRS_DB_PREF. 'db_version', 0);
		if(!$current_version)
			self::$_firstTimeActivated = true;
		/**
		 * modules 
		 */
		/*if (!dbGrs::exist("@__modules")) {
			dbDelta(dbGrs::prepareQuery("CREATE TABLE IF NOT EXISTS `@__modules` (
			  `id` smallint(3) NOT NULL AUTO_INCREMENT,
			  `code` varchar(32) NOT NULL,
			  `active` tinyint(1) NOT NULL DEFAULT '0',
			  `type_id` tinyint(1) NOT NULL DEFAULT '0',
			  `label` varchar(64) DEFAULT NULL,
			  `ex_plug_dir` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `code` (`code`)
			) DEFAULT CHARSET=utf8;"));
			dbGrs::query("INSERT INTO `@__modules` (id, code, active, type_id, label) VALUES
				(NULL, 'adminmenu',1,1,'Admin Menu'),
				(NULL, 'options',1,1,'Options'),
				(NULL, 'user',1,1,'Users'),
				(NULL, 'pages',1,1,'Pages'),
				(NULL, 'templates',1,1,'templates'),
				(NULL, 'supsystic_promo',1,1,'supsystic_promo'),
				(NULL, 'admin_nav',1,1,'admin_nav'),
				
				(NULL, 'popup',1,1,'popup'),
				(NULL, 'subscribe',1,1,'subscribe'),
				(NULL, 'sm',1,1,'sm'),
				(NULL, 'statistics',1,1,'statistics'),
				
				(NULL, 'mail',1,1,'mail');");
		}
		if(!dbGrs::exist("@__modules", "code", "tgm_promo")) {
			dbGrs::query("INSERT INTO `@__modules` (id, code, active, type_id, label) VALUES (NULL, 'tgm_promo',1,1,'tgm_promo')");
		}*/
		/**
		 *  modules_type 
		 */
		/*if(!dbGrs::exist("@__modules_type")) {
			dbDelta(dbGrs::prepareQuery("CREATE TABLE IF NOT EXISTS `@__modules_type` (
			  `id` smallint(3) NOT NULL AUTO_INCREMENT,
			  `label` varchar(32) NOT NULL,
			  PRIMARY KEY (`id`)
			) AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;"));
			dbGrs::query("INSERT INTO `@__modules_type` VALUES
				(1,'system'),
				(6,'addons');");
		}*/
		installerDbUpdaterGrs::runUpdate();
		if($current_version && !self::$_firstTimeActivated) {
			self::setUsed();
			self::_setOldUser();
			// For users that just updated our plugin - don't need tp show step-by-step tutorial
			update_user_meta(get_current_user_id(), GRS_CODE . '-tour-hst', array('closed' => 1));
		}
		update_option($wpPrefix. GRS_DB_PREF. 'db_version', GRS_VERSION);
		add_option($wpPrefix. GRS_DB_PREF. 'db_installed', 1);
	}
	
	static private function _setOldUser() {
		update_option(GRS_DB_PREF. 'is_old_user', 1);
	}
	
	static public function isNewUser() {
		$isOld = (int)get_option(GRS_DB_PREF. 'is_old_user');
		return !$isOld;
	}
	
	static public function setUsed() {
		update_option(GRS_DB_PREF. 'plug_was_used', 1);
	}
	static public function isUsed() {
		//return true;	// No welcome page for now
		//return 0;
		return (int) get_option(GRS_DB_PREF. 'plug_was_used');
	}
	static public function delete() {
		//self::_checkSendStat('delete');
		global $wpdb;
		$wpPrefix = $wpdb->prefix;
		//$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GRS_DB_PREF."modules`");
		//$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GRS_DB_PREF."modules_type`");
		//$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GRS_DB_PREF."usage_stat`");
		//$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GRS_DB_PREF."popup`");
		//$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GRS_DB_PREF."popup_show_pages`");
		//$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GRS_DB_PREF."statistics`");
		//$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GRS_DB_PREF."statistics_sm`");
		//$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GRS_DB_PREF."subscribers`");
		delete_option($wpPrefix. GRS_DB_PREF. 'db_version');
		delete_option($wpPrefix. GRS_DB_PREF. 'db_installed');
	}
	static public function deactivate() {
		//self::_checkSendStat('deactivate');
	}
	static private function _checkSendStat($statCode) {
		if(class_exists('frameGrs') 
			&& frameGrs::_()->getModule('supsystic_promo')
			&& frameGrs::_()->getModule('options')
		) {
			frameGrs::_()->getModule('supsystic_promo')->getModel()->saveUsageStat( $statCode );
			frameGrs::_()->getModule('supsystic_promo')->getModel()->checkAndSend( true );
		}
	}
	static public function update() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		$currentVersion = get_option($wpPrefix. GRS_DB_PREF. 'db_version', 0);
		if(!$currentVersion || version_compare(GRS_VERSION, $currentVersion, '>')) {
			self::init( true );
			update_option($wpPrefix. GRS_DB_PREF. 'db_version', GRS_VERSION);
		}
	}
}
