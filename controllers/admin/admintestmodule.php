<?php

class AdminTestModuleController extends ModuleAdminController
{
    public function __construct() 
    {
        parent::__construct();
    }

    public function init() 
    {
        parent::init();
        $this->bootstrap = true;
    }

    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign(array());

        $this->setTemplate('admintest.tpl');
    }
}