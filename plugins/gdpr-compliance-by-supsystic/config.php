<?php
    global $wpdb;
    if (!defined('WPLANG') || WPLANG == '') {
        define('GRS_WPLANG', 'en_GB');
    } else {
        define('GRS_WPLANG', WPLANG);
    }
    if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

    define('GRS_PLUG_NAME', basename(dirname(__FILE__)));
    define('GRS_DIR', WP_PLUGIN_DIR. DS. GRS_PLUG_NAME. DS);
    define('GRS_TPL_DIR', GRS_DIR. 'tpl'. DS);
    define('GRS_CLASSES_DIR', GRS_DIR. 'classes'. DS);
    define('GRS_TABLES_DIR', GRS_CLASSES_DIR. 'tables'. DS);
	define('GRS_HELPERS_DIR', GRS_CLASSES_DIR. 'helpers'. DS);
    define('GRS_LANG_DIR', GRS_DIR. 'lang'. DS);
    define('GRS_IMG_DIR', GRS_DIR. 'img'. DS);
    define('GRS_TEMPLATES_DIR', GRS_DIR. 'templates'. DS);
    define('GRS_MODULES_DIR', GRS_DIR. 'modules'. DS);
    define('GRS_FILES_DIR', GRS_DIR. 'files'. DS);
    define('GRS_ADMIN_DIR', ABSPATH. 'wp-admin'. DS);

	define('GRS_PLUGINS_URL', plugins_url());
    define('GRS_SITE_URL', get_bloginfo('wpurl'). '/');
    define('GRS_JS_PATH', GRS_PLUGINS_URL. '/'. GRS_PLUG_NAME. '/js/');
    define('GRS_CSS_PATH', GRS_PLUGINS_URL. '/'. GRS_PLUG_NAME. '/css/');
    define('GRS_IMG_PATH', GRS_PLUGINS_URL. '/'. GRS_PLUG_NAME. '/img/');
    define('GRS_MODULES_PATH', GRS_PLUGINS_URL. '/'. GRS_PLUG_NAME. '/modules/');
    define('GRS_TEMPLATES_PATH', GRS_PLUGINS_URL. '/'. GRS_PLUG_NAME. '/templates/');
    define('GRS_JS_DIR', GRS_DIR. 'js/');

    define('GRS_URL', GRS_SITE_URL);

    define('GRS_LOADER_IMG', GRS_IMG_PATH. 'loading.gif');
	define('GRS_TIME_FORMAT', 'H:i:s');
    define('GRS_DATE_DL', '/');
    define('GRS_DATE_FORMAT', 'm/d/Y');
    define('GRS_DATE_FORMAT_HIS', 'm/d/Y ('. GRS_TIME_FORMAT. ')');
    define('GRS_DATE_FORMAT_JS', 'mm/dd/yy');
    define('GRS_DATE_FORMAT_CONVERT', '%m/%d/%Y');
    define('GRS_WPDB_PREF', $wpdb->prefix);
    define('GRS_DB_PREF', 'grs_');
    define('GRS_MAIN_FILE', 'grs.php');

    define('GRS_DEFAULT', 'default');
    define('GRS_CURRENT', 'current');

	define('GRS_EOL', "\n");

    define('GRS_PLUGIN_INSTALLED', true);
    define('GRS_VERSION', '1.1.15');
    define('GRS_USER', 'user');

    define('GRS_CLASS_PREFIX', 'grsc');
    define('GRS_FREE_VERSION', false);
	define('GRS_TEST_MODE', true);

    define('GRS_SUCCESS', 'Success');
    define('GRS_FAILED', 'Failed');
	define('GRS_ERRORS', 'grsErrors');

	define('GRS_ADMIN',	'admin');
	define('GRS_LOGGED','logged');
	define('GRS_GUEST',	'guest');

	define('GRS_ALL',		'all');

	define('GRS_METHODS',		'methods');
	define('GRS_USERLEVELS',	'userlevels');
	/**
	 * Framework instance code
	 */
	define('GRS_CODE', 'grs');

	define('GRS_LANG_CODE', 'grs_lng');
	/**
	 * Plugin name
	 */
	define('GRS_WP_PLUGIN_NAME', 'GDPR Compliance by Supsystic');
	/**
	 * Plugin admin area slug
	 */
	define('GRS_ADMIN_AREA_SLUG', 'gdpr-compliance-by-supsystic');
	/**
	 * Dash icon for WP admin area menu
	 */
	define('GRS_ADMIN_MENU_ICON', 'dashicons-admin-appearance');
	/**
	 * Allow minification
	 */
	define('GRS_MINIFY_ASSETS', false);
	/**
	 * Load plugin core only in admin area - leave frontend lighter
	 */
	define('GRS_ADMIN_USAGE_ONLY', false);
	/**
	 * Open this tab by default in admin area
	 */
	define('GRS_DEF_ADMIN_TAB', 'gdpr-settings');
	/**
	 * Custom defined for plugin
	 */
	define('GRS_HOME_PAGE_ID', 0);
