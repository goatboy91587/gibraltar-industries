<?php
/**
 * Plugin Name: GDPR Compliance by Supsystic
 * Description: Be prepared for GDPR!
 * Version: 1.1.15
 * Author: supsystic.com
 * Author URI: https://supsystic.com
 **/
	/**
	 * Base config constants and functions
	 */
    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'config.php');
	if(defined('GRS_ADMIN_USAGE_ONLY') && GRS_ADMIN_USAGE_ONLY && !is_admin()) return;

    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'functions.php');
	/**
	 * Connect all required core classes
	 */
    importClassGrs('dbGrs');
	importClassGrs('outGrs');
    importClassGrs('installerGrs');
    importClassGrs('baseObjectGrs');
    importClassGrs('moduleGrs');
    importClassGrs('modelGrs');
    importClassGrs('viewGrs');
    importClassGrs('controllerGrs');
    importClassGrs('helperGrs');
    importClassGrs('dispatcherGrs');
    importClassGrs('fieldGrs');
    importClassGrs('tableGrs');
    importClassGrs('frameGrs');
	/**
	 * @deprecated since version 1.0.1
	 */
    importClassGrs('langGrs');
    importClassGrs('reqGrs');
    importClassGrs('uriGrs');
    importClassGrs('htmlGrs');
    importClassGrs('responseGrs');
    importClassGrs('fieldAdapterGrs');
    importClassGrs('validatorGrs');
    importClassGrs('errorsGrs');
    importClassGrs('utilsGrs');
    importClassGrs('modInstallerGrs');
	importClassGrs('installerDbUpdaterGrs');
	importClassGrs('dateGrs');
	/**
	 * Check plugin version - maybe we need to update database, and check global errors in request
	 */
    installerGrs::update();
    errorsGrs::init();
    /**
	 * Start application
	 */
    frameGrs::_()->parseRoute();
    frameGrs::_()->init();
    frameGrs::_()->exec();

	//var_dump(frameGrs::_()->getActivationErrors()); exit();
