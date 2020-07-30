<?php 
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class PwTestModule extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'pwtestmodule';
        $this->tab = 'other';
        $this->version = '0.1.0';
        $this->author = 'PrestaWeb.ru';
        $this->need_instance = 1;


        parent::__construct();

        $this->displayName = $this->l('Test module');
        $this->description = $this->l('Display some text at the home1');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }


    public function install()
    {
        if (!parent::install()
            || !Configuration::updateValue('PW_TEXT', 'some text')
            || !$this->registerHook('home')
            || !$this->registerHook('header')
            || !$this->createTabLink()
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()
            || !Configuration::deleteByName('PW_TEXT')
        ) {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        $output = null;
    
        if (Tools::isSubmit('submit'.$this->name))
        {
            $some_text = strval(Tools::getValue('PW_TEXT'));
            if (!$some_text
                || empty($some_text)
                || !Validate::isGenericName($some_text)
            ) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            }
            else {
                Configuration::updateValue('PW_TEXT', $some_text);
                $output .= $this->displayConfirmation($this->l('Template updated'));
            }
        }
        return $output.$this->displayForm();
    }
    public function displayForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Update text'),
            ),
            'input' => array(
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Some text value'),
                    'name' => 'PW_TEXT',
                    'cols' => 50,
                    'required' => false,
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            )
        );
        
        $helper = new HelperForm();
        
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list'),
            )
        );
        
        $helper->fields_value['PW_TEXT'] = Configuration::get('PW_TEXT');
        
        return $helper->generateForm($fields_form);
    }

    public function hookDisplayHome()
    {
      $this->context->smarty->assign([
        'sometext' => Configuration::get('PW_TEXT'),
        'frontcontrollerlink' => $this->context->link->getModuleLink($this->name, 'fronttest'),
      ]);
      return $this->display(__FILE__, 'sometext.tpl');
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'css/pwtestmodule.css', 'all');
    }

    public function createTabLink() 
    {
        $tab = new Tab();

        foreach(Language::getLanguages() as $lang)
        {
            $tab->name[$lang['id_lang']] = $this->l('Test');
        }

        $tab->class_name = 'AdminTestModule';
        $tab->module = $this->name;
        $tab->active = 1;
        $tab->position = 3;
        $tab->id_parent = 103;
        
        return $tab->save();
    }
} 