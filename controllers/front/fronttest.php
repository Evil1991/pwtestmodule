<?php
/**
 * <ModuleName> => pwtestmodule
 * <FileName> => testfront.php
 * Format expected: <ModuleName><FileName>ModuleFrontController
 */
class PwTestModuleFrontTestModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {

        parent::initContent();

        $this->context->smarty->assign(
            array(
              'text' => 'Hello to my shop!', 
            )
        );

        $this->setTemplate('module:pwtestmodule/views/templates/front/fronttest.tpl');
    }
}