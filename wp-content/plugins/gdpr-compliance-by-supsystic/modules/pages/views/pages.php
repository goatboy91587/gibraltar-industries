<?php
class pagesViewGrs extends viewGrs {
    public function displayDeactivatePage() {
        $this->assign('GET', reqGrs::get('get'));
        $this->assign('POST', reqGrs::get('post'));
        $this->assign('REQUEST_METHOD', strtoupper(reqGrs::getVar('REQUEST_METHOD', 'server')));
        $this->assign('REQUEST_URI', basename(reqGrs::getVar('REQUEST_URI', 'server')));
        parent::display('deactivatePage');
    }
}

