<?php
class admin_navViewGrs extends viewGrs {
	public function getBreadcrumbs() {
		$this->assign('breadcrumbsList', dispatcherGrs::applyFilters('mainBreadcrumbs', $this->getModule()->getBreadcrumbsList()));
		return parent::getContent('adminNavBreadcrumbs');
	}
}
