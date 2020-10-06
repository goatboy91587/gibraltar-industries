<?php
class modInstallerGrs {
    static private $_current = array();
    /**
     * Install new moduleGrs into plugin
     * @param string $module new moduleGrs data (@see classes/tables/modules.php)
     * @param string $path path to the main plugin file from what module is installed
     * @return bool true - if install success, else - false
     */
    static public function install($module, $path) {
        $exPlugDest = explode('plugins', $path);
        if(!empty($exPlugDest[1])) {
            $module['ex_plug_dir'] = str_replace(DS, '', $exPlugDest[1]);
        }
        $path = $path. DS. $module['code'];
        if(!empty($module) && !empty($path) && is_dir($path)) {
            if(self::isModule($path)) {
                $filesMoved = false;
                if(empty($module['ex_plug_dir']))
                    $filesMoved = self::moveFiles($module['code'], $path);
                else
                    $filesMoved = true;     //Those modules doesn't need to move their files
                if($filesMoved) {
                    if(frameGrs::_()->getTable('modules')->exists($module['code'], 'code')) {
                        frameGrs::_()->getTable('modules')->delete(array('code' => $module['code']));
                    }
					if($module['code'] != 'license')
						$module['active'] = 0;
                    frameGrs::_()->getTable('modules')->insert($module);
                    self::_runModuleInstall($module);
                    self::_installTables($module);
                    return true;
                } else {
                    errorsGrs::push(sprintf(__('Move files for %s failed'), $module['code']), errorsGrs::MOD_INSTALL);
                }
            } else
                errorsGrs::push(sprintf(__('%s is not plugin module'), $module['code']), errorsGrs::MOD_INSTALL);
        }
        return false;
    }
    static protected function _runModuleInstall($module, $action = 'install') {
        $moduleLocationDir = GRS_MODULES_DIR;
        if(!empty($module['ex_plug_dir']))
            $moduleLocationDir = utilsGrs::getPluginDir( $module['ex_plug_dir'] );
        if(is_dir($moduleLocationDir. $module['code'])) {
			if(!class_exists($module['code']. strFirstUpGrs(GRS_CODE))) {
				importClassGrs($module['code']. strFirstUpGrs(GRS_CODE), $moduleLocationDir. $module['code']. DS. 'mod.php');
			}
            $moduleClass = toeGetClassNameGrs($module['code']);
            $moduleObj = new $moduleClass($module);
            if($moduleObj) {
                $moduleObj->$action();
            }
        }
    }
    /**
     * Check whether is or no module in given path
     * @param string $path path to the module
     * @return bool true if it is module, else - false
     */
    static public function isModule($path) {
        return true;
    }
    /**
     * Move files to plugin modules directory
     * @param string $code code for module
     * @param string $path path from what module will be moved
     * @return bool is success - true, else - false
     */
    static public function moveFiles($code, $path) {
        if(!is_dir(GRS_MODULES_DIR. $code)) {
            if(mkdir(GRS_MODULES_DIR. $code)) {
                utilsGrs::copyDirectories($path, GRS_MODULES_DIR. $code);
                return true;
            } else 
                errorsGrs::push(__('Cannot create module directory. Try to set permission to '. GRS_MODULES_DIR. ' directory 755 or 777', GRS_LANG_CODE), errorsGrs::MOD_INSTALL);
        } else
            return true;
        return false;
    }
    static private function _getPluginLocations() {
        $locations = array();
        $plug = reqGrs::getVar('plugin');
        if(empty($plug)) {
            $plug = reqGrs::getVar('checked');
            $plug = $plug[0];
        }
        $locations['plugPath'] = plugin_basename( trim( $plug ) );
        $locations['plugDir'] = dirname(WP_PLUGIN_DIR. DS. $locations['plugPath']);
		$locations['plugMainFile'] = WP_PLUGIN_DIR. DS. $locations['plugPath'];
        $locations['xmlPath'] = $locations['plugDir']. DS. 'install.xml';
		$locations['extendModPath'] = $locations['plugDir']. DS. 'install.php';
        return $locations;
    }
    static private function _getModulesFromXml($xmlPath) {
        if($xml = utilsGrs::getXml($xmlPath)) {
            if(isset($xml->modules) && isset($xml->modules->mod)) {
                $modules = array();
                $xmlMods = $xml->modules->children();
                foreach($xmlMods->mod as $mod) {
                    $modules[] = $mod;
                }
                if(empty($modules))
                    errorsGrs::push(__('No modules were found in XML file', GRS_LANG_CODE), errorsGrs::MOD_INSTALL);
                else
                    return $modules;
            } else
                errorsGrs::push(__('Invalid XML file', GRS_LANG_CODE), errorsGrs::MOD_INSTALL);
        } else
            errorsGrs::push(__('No XML file were found', GRS_LANG_CODE), errorsGrs::MOD_INSTALL);
        return false;
    }
	static private function _getExtendModules($locations) {
		$modules = array();
		$isExtendModPath = file_exists($locations['extendModPath']);
		$modulesList = $isExtendModPath ? include $locations['extendModPath'] : self::_getModulesFromXml($locations['xmlPath']);
		if(!empty($modulesList)) {
			foreach($modulesList as $mod) {
				$modData = $isExtendModPath ? $mod : utilsGrs::xmlNodeAttrsToArr($mod);
				array_push($modules, $modData);
			}
			if(empty($modules))
				errorsGrs::push(__('No modules were found in installation file', GRS_LANG_CODE), errorsGrs::MOD_INSTALL);
			else
				return $modules;
		} else
			errorsGrs::push(__('No installation file were found', GRS_LANG_CODE), errorsGrs::MOD_INSTALL);
		return false;
	}
    /**
     * Check whether modules is installed or not, if not and must be activated - install it
     * @param array $codes array with modules data to store in database
     * @param string $path path to plugin file where modules is stored (__FILE__ for example)
     * @return bool true if check ok, else - false
     */
    static public function check($extPlugName = '') {
		if(GRS_TEST_MODE) {
			add_action('activated_plugin', array(frameGrs::_(), 'savePluginActivationErrors'));
		}
        $locations = self::_getPluginLocations();
		if($modules = self::_getExtendModules($locations)) {
			foreach($modules as $m) {
				if(!empty($m)) {
					//If module Exists - just activate it, we can't check this using frameGrs::moduleExists because this will not work for multy-site WP
					if(frameGrs::_()->getTable('modules')->exists($m['code'], 'code') /*frameGrs::_()->moduleExists($m['code'])*/) {
						self::activate($m);
					} else {                                           //  if not - install it
						if(!self::install($m, $locations['plugDir'])) {
							errorsGrs::push(sprintf(__('Install %s failed'), $m['code']), errorsGrs::MOD_INSTALL);
						}
					}
				}
			}
		} else
            errorsGrs::push(__('Error Activate module', GRS_LANG_CODE), errorsGrs::MOD_INSTALL);
        if(errorsGrs::haveErrors(errorsGrs::MOD_INSTALL)) {
            self::displayErrors();
            return false;
        }
		update_option(GRS_CODE. '_full_installed', 1);
        return true;
    }
    /**
	 * Public alias for _getCheckRegPlugs()
	 */
	/**
	 * We will run this each time plugin start to check modules activation messages
	 */
	static public function checkActivationMessages() {

	}
    /**
     * Deactivate module after deactivating external plugin
     */
    static public function deactivate() {
        $locations = self::_getPluginLocations();
		if($modules = self::_getExtendModules($locations)) {
			foreach($modules as $m) {
				if(frameGrs::_()->moduleActive($m['code'])) { //If module is active - then deacivate it
					if(frameGrs::_()->getModule('options')->getModel('modules')->put(array(
						'id' => frameGrs::_()->getModule($m['code'])->getID(),
						'active' => 0,
					))->error) {
						errorsGrs::push(__('Error Deactivation module', GRS_LANG_CODE), errorsGrs::MOD_INSTALL);
					}
				}
			}
		}
        if(errorsGrs::haveErrors(errorsGrs::MOD_INSTALL)) {
            self::displayErrors(false);
            return false;
        }
        return true;
    }
    static public function activate($modDataArr) {
		if(!empty($modDataArr['code']) && !frameGrs::_()->moduleActive($modDataArr['code'])) { //If module is not active - then acivate it
			if(frameGrs::_()->getModule('options')->getModel('modules')->put(array(
				'code' => $modDataArr['code'],
				'active' => 1,
			))->error) {
				errorsGrs::push(__('Error Activating module', GRS_LANG_CODE), errorsGrs::MOD_INSTALL);
			} else {
				$dbModData = frameGrs::_()->getModule('options')->getModel('modules')->get(array('code' => $modDataArr['code']));
				if(!empty($dbModData) && !empty($dbModData[0])) {
					$m['ex_plug_dir'] = $dbModData[0]['ex_plug_dir'];
				}
				self::_runModuleInstall($modDataArr, 'activate');
			}
		}
    } 
    /**
     * Display all errors for module installer, must be used ONLY if You realy need it
     */
    static public function displayErrors($exit = true) {
        $errors = errorsGrs::get(errorsGrs::MOD_INSTALL);
        foreach($errors as $e) {
            echo '<b style="color: red;">'. $e. '</b><br />';
        }
        if($exit) exit();
    }
    static public function uninstall() {
        $locations = self::_getPluginLocations();
		if($modules = self::_getExtendModules($locations)) {
			foreach($modules as $m) {
				self::_uninstallTables($m);
				frameGrs::_()->getModule('options')->getModel('modules')->delete(array('code' => $m['code']));
				utilsGrs::deleteDir(GRS_MODULES_DIR. $m['code']);
			}
		}
    }
    static protected  function _uninstallTables($module) {
        if(is_dir(GRS_MODULES_DIR. $module['code']. DS. 'tables')) {
            $tableFiles = utilsGrs::getFilesList(GRS_MODULES_DIR. $module['code']. DS. 'tables');
            if(!empty($tableNames)) {
                foreach($tableFiles as $file) {
                    $tableName = str_replace('.php', '', $file);
                    if(frameGrs::_()->getTable($tableName))
                        frameGrs::_()->getTable($tableName)->uninstall();
                }
            }
        }
    }
    static public function _installTables($module, $action = 'install') {
		$modDir = empty($module['ex_plug_dir']) ? 
            GRS_MODULES_DIR. $module['code']. DS : 
            utilsGrs::getPluginDir($module['ex_plug_dir']). $module['code']. DS; 
        if(is_dir($modDir. 'tables')) {
            $tableFiles = utilsGrs::getFilesList($modDir. 'tables');
            if(!empty($tableFiles)) {
                frameGrs::_()->extractTables($modDir. 'tables'. DS);
                foreach($tableFiles as $file) {
                    $tableName = str_replace('.php', '', $file);
                    if(frameGrs::_()->getTable($tableName))
                        frameGrs::_()->getTable($tableName)->$action();
                }
            }
        }
    }
}