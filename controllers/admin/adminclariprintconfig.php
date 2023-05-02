<?php
	

	
class AdminClariprintConfigController extends ModuleAdminController
{
	
	public function __construct()
	{
		parent::__construct();
		
		$this->bootstrap = true;
		$this->name = 'AdminClariprintConfigController';
		
	}
	public function initPageHeaderToolbar()
	{
		$this->page_header_toolbar_btn['save'] = array(
			'js' => 'return ClariprintConfigSave(this)',
			'href' => $this->context->link->getAdminLink('AdminClariprintConfig',true,null,array('ajax' => 1, 'action' => 'Save')),
//			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminClariprintConfig'),
			'desc' => $this->l('Save')
		);
		$this->page_header_toolbar_btn['update'] = array(	
			'js' => 'return ClariprintConfigAction(this)',
			'href' => $this->context->link->getAdminLink('AdminClariprintConfig',true,null,array('ajax' => 1, 'action' => 'Update')),
			// AdminController::$currentIndex.'&configure='.$this->name.'&svnUpdate'.$this->name.'&token='.Tools::getAdminTokenLite('AdminClariprintConfig'),
			'desc' => $this->l('Update module')
		);

		$this->page_header_toolbar_btn['resettabs'] = array(
			'href' => $this->context->link->getAdminLink('AdminClariprintConfig',true,null,array('ajax' => 1, 'action' => 'ResetTabs')),
			'js' => 'return ClariprintConfigAction(this)',
//			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&resetTabs'.$this->name.'&token='.Tools::getAdminTokenLite('AdminClariprintConfig'),
			'icon' => 'process-icon-configure',
			'desc' => $this->l('Reset Tabs')
		);
		$this->page_header_toolbar_btn['resethooks'] = array(
			'href' => $this->context->link->getAdminLink('AdminClariprintConfig',true,null,array('ajax' => 1, 'action' => 'ResetHooks')),
//			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&resetHooks'.$this->name.'&token='.Tools::getAdminTokenLite('AdminClariprintConfig'),
			'icon' => 'process-icon-configure',
			'js' => 'return ClariprintConfigAction(this)',
			'desc' => $this->l('Reset Hooks')
		);
		$this->page_header_toolbar_btn['clearcache'] = array(
//			'icon' => 'process-icon-refresh-cache',
			'icon' => 'process-icon-delete',
			'js' => 'return ClariprintConfigAction(this)',
			'href' => $this->context->link->getAdminLink('AdminClariprintConfig',true,null,array('ajax' => 1, 'action' => 'ResetCache')),
//			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&clearCache'.$this->name.'&token='.Tools::getAdminTokenLite('AdminClariprintConfig'),
			'desc' => $this->l('Clear Cache')
		);

		parent::initPageHeaderToolbar();
	}
	
	
	public function displayAjaxAddTemplate()
	{
		if (Tools::getValue('add_email_template'))
		{
			$tmpname = preg_replace('/[^a-zA-Z_0-9-]/','_', Tools::getValue('add_email_template'));
			if ($tmpname) {
				foreach(Language::getLanguages(false) as $l) {
					$dir = _PS_MAIL_DIR_.'/' . $l['iso_code'] .'/';
					if (is_dir($dir)) {
						touch($dir.$tmpname.'.html');
						touch($dir.$tmpname.'.txt');
						$this->module->addInfo( $this->l('Create template') . ' : '.  $l['iso_code'] . '/' .$tmpname);
					}
				}
			}
		}
		return true;
	}
	
	public function displayAjaxUpdate()
	{
		$this->module->direct_messaging = true;
		$this->module->updateModule();
	}
	public function displayAjaxResetTabs()
	{
		$this->module->direct_messaging = true;
		$this->module->resetTabs();
	}
	public function displayAjaxResetHooks()
	{
		$this->module->direct_messaging = true;
		$this->module->checkHooks();
	}
	public function displayAjaxResetCache()
	{
		Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'clariprint_solver_cache`');
		$this->addInfo( "Clear cache");
	}
	
	
	
	public function displayAjaxSave()
	{
		$this->module->direct_messaging = true;
		Configuration::updateValue('CL_SERVER_URL', pSQL(Tools::getValue('cl_server_url')));
		Configuration::updateValue('CL_SERVER_LOGIN', pSQL(Tools::getValue('cl_server_login')));

		Configuration::updateValue('CL_MP_SERVER_URL', pSQL(Tools::getValue('cl_mp_server_url')));
		if (Tools::getValue('cl_mp_server_pass'))
			Configuration::updateValue('CL_MP_SERVER_PASS', pSQL(Tools::getValue('cl_mp_server_pass')));
		Configuration::updateValue('CL_MP_SERVER_LOGIN', pSQL(Tools::getValue('cl_mp_server_login')));
		Configuration::updateValue('CL_MP_SERVER_KEY', pSQL(Tools::getValue('cl_mp_server_key')));
		Configuration::updateValue('CL_MP_GROUP', pSQL(Tools::getValue('cl_mp_group')));
		if (Tools::getValue('cl_server_password'))
			Configuration::updateValue('CL_SERVER_PASSWORD', pSQL(Tools::getValue('cl_server_password')));

		Configuration::updateValue('CL_PA_SERVER_URL', pSQL(Tools::getValue('cl_pa_server_url')));
		Configuration::updateValue('CL_PA_SERVER_LOGIN', pSQL(Tools::getValue('cl_pa_server_login')));
		if (Tools::getValue('cl_pa_server_password'))
			Configuration::updateValue('CL_PA_SERVER_PASSWORD', pSQL(Tools::getValue('cl_pa_server_password')));

		Configuration::updateValue('CL_CATEGORY_TEMPLATE', pSQL(Tools::getValue('cl_category_template')));

		Configuration::updateValue('CL_HARD_PROOF', pSQL(Tools::getValue('cl_hard_proof')));

		Configuration::updateValue('CL_USE_CALCULATED_WEIGHT', pSQL(Tools::getValue('cl_use_calculated_weight')));

		Configuration::updateValue('CL_SHOW_DISCOUNT', pSQL(Tools::getValue('cl_show_discount')));
		Configuration::updateValue('CL_API_MODE', pSQL(Tools::getValue('cl_api_mode')));


		Configuration::updateValue('CL_FRONT_UI_MODE', pSQL(Tools::getValue('cl_front_ui_mode')));
		Configuration::updateValue('CL_FRONT_UI_ADD', pSQL(Tools::getValue('cl_front_ui_add')));
		Configuration::updateValue('CL_BACK_UI_MODE', pSQL(Tools::getValue('cl_back_ui_mode')));
		Configuration::updateValue('CL_BACK_UI_ADD', pSQL(Tools::getValue('cl_back_ui_add')));

		Configuration::updateValue('CL_DELIVERIES_SUB', pSQL(Tools::getValue('cl_deliveries_sub_regions')));
		Configuration::updateValue('CL_DELIVERY_DEFAULT', pSQL(Tools::getValue('cl_delivery_default')));

		if ($del = Tools::getValue('mycountries')) {
		}

		$this->module->addInfo($this->l('Update deliveries'));
		if ($del = Tools::getValue('cl_deliveries'))
		{
			$this->module->setDeliveries($del);
		}

		/*
		if ($del = Tools::getValue('cl_delivery'))
			Configuration::updateValue('CL_DELIVERY', pSQL(implode(',',$del)));
		else
			Configuration::updateValue('CL_DELIVERY', null);
		*/

		if (ClariprintConnect::checkAuth()) {
			$this->module->addInfo($this->l('Successfully connect to Clariprint server'));
		} else $this->module->addError($this->l('Unable to connect Clariprint server: check url, login and password'));		
	}
	
	public function display()
	{
		if (Tools::isSubmit('resettabs'))
		{	
			$this->module->resettabs();
		}
		if (Tools::isSubmit('resethooks'))
		{	
			$this->module->checkHooks();
		}
		
		if (Tools::isSubmit('add_email_template'))
		{
			$tmpname = preg_replace('/[^a-zA-Z_0-9-]/','_', Tools::getValue('new_email_template'));
			if ($tmpname) {
				foreach(Language::getLanguages(false) as $l) {
					$dir = _PS_MAIL_DIR_.'/' . $l['iso_code'] .'/';
					if (is_dir($dir)) {
						touch($dir.$tmpname.'.html');
						touch($dir.$tmpname.'.txt');
						$this->module->addInfo( $this->l('Create template') . ' : '.  $l['iso_code'] . '/' .$tmpname);
					}
				}
			}
		}
		if (Tools::isSubmit('submitUpdate'))
		{
			Configuration::updateValue('CL_SERVER_URL', pSQL(Tools::getValue('cl_server_url')));
			Configuration::updateValue('CL_SERVER_LOGIN', pSQL(Tools::getValue('cl_server_login')));

			Configuration::updateValue('CL_MP_SERVER_URL', pSQL(Tools::getValue('cl_mp_server_url')));
			if (Tools::getValue('cl_mp_server_pass'))
				Configuration::updateValue('CL_MP_SERVER_PASS', pSQL(Tools::getValue('cl_mp_server_pass')));
			Configuration::updateValue('CL_MP_SERVER_LOGIN', pSQL(Tools::getValue('cl_mp_server_login')));
			Configuration::updateValue('CL_MP_SERVER_KEY', pSQL(Tools::getValue('cl_mp_server_key')));
			Configuration::updateValue('CL_MP_GROUP', pSQL(Tools::getValue('cl_mp_group')));
// GrPFAB
			if (Tools::getValue('cl_server_password'))
				Configuration::updateValue('CL_SERVER_PASSWORD', pSQL(Tools::getValue('cl_server_password')));


			Configuration::updateValue('CL_PA_SERVER_URL', pSQL(Tools::getValue('cl_pa_server_url')));
			Configuration::updateValue('CL_PA_SERVER_LOGIN', pSQL(Tools::getValue('cl_pa_server_login')));
			if (Tools::getValue('cl_pa_server_password'))
				Configuration::updateValue('CL_PA_SERVER_PASSWORD', pSQL(Tools::getValue('cl_pa_server_password')));

			Configuration::updateValue('CL_CATEGORY_TEMPLATE', pSQL(Tools::getValue('cl_category_template')));

			Configuration::updateValue('CL_HARD_PROOF', pSQL(Tools::getValue('cl_hard_proof')));

			Configuration::updateValue('CL_USE_CALCULATED_WEIGHT', pSQL(Tools::getValue('cl_use_calculated_weight')));
			
			Configuration::updateValue('CL_SHOW_DISCOUNT', pSQL(Tools::getValue('cl_show_discount')));
			Configuration::updateValue('CL_API_MODE', pSQL(Tools::getValue('cl_api_mode')));


			Configuration::updateValue('CL_FRONT_UI_MODE', pSQL(Tools::getValue('cl_front_ui_mode')));
			Configuration::updateValue('CL_FRONT_UI_ADD', pSQL(Tools::getValue('cl_front_ui_add')));
			Configuration::updateValue('CL_BACK_UI_MODE', pSQL(Tools::getValue('cl_back_ui_mode')));
			Configuration::updateValue('CL_BACK_UI_ADD', pSQL(Tools::getValue('cl_back_ui_add')));

			Configuration::updateValue('CL_DELIVERIES_SUB', pSQL(Tools::getValue('cl_deliveries_sub_regions')));
			Configuration::updateValue('CL_DELIVERY_DEFAULT', pSQL(Tools::getValue('cl_delivery_default')));

			if ($del = Tools::getValue('mycountries')) {
//				$this->setCountries($del);
			} 
			
			if ($del = Tools::getValue('cl_deliveries'))
			{
				$this->module->setDeliveries($del);
			}
			
			/*
			if ($del = Tools::getValue('cl_delivery'))
				Configuration::updateValue('CL_DELIVERY', pSQL(implode(',',$del)));
			else
				Configuration::updateValue('CL_DELIVERY', null);
			*/
			
			if (ClariprintConnect::checkAuth()) {
				$this->module->addInfo($this->l('Successfully connect to Clariprint server'));
			} else $this->module->addError($this->l('Unable to connect Clariprint server: check url, login and password'));
		}
		if (Tools::isSubmit('svnUpdate'))
		{
			$this->module->uninstallOverrides();
			$output = array();
			$svn = 'svn --non-interactive --username presta  --password P5wvRBazIBqv update ' . _PS_MODULE_DIR_ .'/clariprint 2>&1';
			
			$res = exec($svn,$output);
			$this->module->addInfo(implode('<br>',$output));
			$this->module->runUpdates();
			$this->module->checkHooks();
			$this->module->installOverrides();
//			$this->cleanDatabase();
			$this->module->addInfo($this->l("Clariprint products cleaned"));
			
		}
		if (Tools::isSubmit('ClariprintCleanCache'))
		{
			Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'clariprint_solver_cache`');
			$this->module->addInfo( "Clean cache");
		}
		$this->_confirmations = array("poo");
		
		
		
		$mcountries = array();
		foreach($this->module->countries() as $c) {
			$mcountries[] = $c->iso;
		}
		
		$this->context->smarty->assign(array(
//			'displayName' => $this->displayName,
			'email' => Configuration::get('CLP_WYS_EMAIL'),
			
			'cl_server_url' =>  Configuration::get('CL_SERVER_URL'),
			'cl_server_login' => Configuration::get('CL_SERVER_LOGIN'),
			'cl_api_mode' => Configuration::get('CL_API_MODE'),

			'cl_mp_server_url' => Configuration::get('CL_MP_SERVER_URL'),
			'cl_mp_server_key' => Configuration::get('CL_MP_SERVER_KEY'),
			'cl_mp_group' => Configuration::get('CL_MP_GROUP'),
			
			'cl_pa_server_url' => Configuration::get('CL_PA_SERVER_URL'),
			'cl_pa_server_login' => Configuration::get('CL_PA_SERVER_LOGIN'),


			'cl_mp_server_login' => Configuration::get('CL_MP_SERVER_LOGIN'),
			'cl_mp_server_pass' => Configuration::get('CL_MP_SERVER_PASS'),

			'cl_mp_group' => Configuration::get('CL_MP_GROUP'),
			'cl_mp_groups' => Group::getGroups(Context::getContext()->language->id),
			'cl_category_template' => Configuration::get('CL_CATEGORY_TEMPLATE'),
			'categories'  => Category::getCategories($this->context->language->id, true, false),
			
			'cl_hard_proof' => (float)Configuration::get('CL_HARD_PROOF'),
			'cl_use_calculated_weight' => Configuration::get('CL_USE_CALCULATED_WEIGHT'),
			'mcountries' => $mcountries,
			'countries' => $this->module->allcountries(),
			'cl_show_discount' => Configuration::get('CL_SHOW_DISCOUNT'),
			
			'cl_deliveries_sub_regions' => Configuration::get('CL_DELIVERIES_SUB'),
			'cl_delivery_default'  => Configuration::get('CL_DELIVERY_DEFAULT'),
			'cl_deliveries'  => $this->module->countries(),

			'cl_front_ui_modes' => $this->module->uilibs(),
			'cl_front_ui_mode' => Configuration::get('CL_FRONT_UI_MODE'),
			'cl_front_ui_add' => Configuration::get('CL_FRONT_UI_ADD'),

			'cl_back_ui_modes' => $this->module->uilibs(),
			'cl_back_ui_mode' => Configuration::get('CL_BACK_UI_MODE'),
			'cl_back_ui_add' => Configuration::get('CL_BACK_UI_ADD'),

			'displayName' => $this->module->displayName));		
		parent::display();
	}
}