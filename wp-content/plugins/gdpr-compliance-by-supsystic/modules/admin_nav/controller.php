<?php
class admin_navControllerGrs extends controllerGrs {
	public function getPermissions() {
		return array(
			GRS_USERLEVELS => array(
				GRS_ADMIN => array()
			),
		);
	}
}