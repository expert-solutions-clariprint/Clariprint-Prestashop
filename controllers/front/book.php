<?php

class ClariprintBookModuleFrontController extends ModuleFrontController
{
	
	static function interior($value='')
	{
		$x = new stdClass();
	}
	
	public function displayAjaxAddProduct()
	{
		$model_key = Tools::getValue('model_key');
		$product_id = Tools::getValue('product_id');
		$config = Clariprint_Config::objectForProduct($product_id);
		$product = $config->product();
		$model = $product->parts->$model_key;
		
		$product_kind = $model->kind;
		$smart = $this->context->smarty;
		$product_template = "./" . $product_kind .'.tpl';
		$product = new stdClass();
		$product->kind = $product_kind;
		$np = Tools::getValue('product_index');

		$model->name .=  ' '.$np;

		$smart->assign(array(
							'product' => $model,
							'ui_mode' => Configuration::get('CL_FRONT_UI_MODE'),
							'link' => $this->context->link,
							'product_key' => 'clariprint_product[parts]['. uniqid('p'). ']',
							'vernis_en_lignes' => $this->module->finishinghInlineFull(),
							'vernis_en_reprise' => $this->module->finishingOfflineFull(),
							'vernis_combines' => $this->module->list_vernis_combines,
							'paper_kinds' => $this->module->paperKinds(),
							'processes' => $this->module->processes(),
							'papers' => $this->module->getPapers(),
							'product_template' => $product_template,
							'gilding_materials' => $this->module->gildingMaterials(),
							'wrapping' => $this->module->wrapping(),
							'primaries' => $this->module->primaries(),
							'groups' => Group::getGroups(Context::getContext()->language->id),
							'ajax_papers_selector_url' => '/index.php', // $this->context->link->getModuleLink('clariprint','paper')
							'ajax_papers_selector_url2' => $this->context->link->getModuleLink('clariprint','paper') ,
							'direct_servers' => ClariprintServer::directs(),
							'marketplaces' => ClariprintServer::marketplaces(),
							'folds' => $this->module->getFolds(),
							'folderdie' => $this->module->getFolderDie(),
							'remove_product' => true
						 	));
		
		$this->module->registerSmartyPlugins();
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);		
		$tpl = _PS_MODULE_DIR_.'/clariprint/views/front/'.$product_kind.'.tpl';
		echo $smart->display($tpl);
		die();
		
	}
	public function displayAjaxBookComponentModel()
	{
		$model_key = Tools::getValue('model');
		$book_key = Tools::getValue('book');
		$product_id = Tools::getValue('id_product');

		$config = Clariprint_Config::objectForProduct($product_id);
		$product = $config->product();
//		echo '<pre>'; print_r($product); echo '</pre>';
		$model = $product->parts->$book_key->components->$model_key;
		$product_kind = $model->kind;
		$smart = $this->context->smarty;
		$product_template = "./" . $product_kind .'.tpl';

		$product = new stdClass();
		$product->kind = $product_kind;
		$smart->assign(array(
							'unique_component' => false,
							'product' => $model,
							'ui_mode' => Configuration::get('CL_FRONT_UI_MODE'),
							'link' => $this->context->link,
							'product_key' => 'clariprint_product[parts]['.$book_key.'][components]['. uniqid('p'). ']',
							'vernis_en_lignes' => $this->module->finishinghInlineFull(),
							'vernis_en_reprise' => $this->module->finishingOfflineFull(),
							'vernis_combines' => $this->module->list_vernis_combines,
							'paper_kinds' => $this->module->paperKinds(),
							'processes' => $this->module->processes(),
							'papers' => $this->module->getPapers(),
							'product_template' => $product_template,
							'gilding_materials' => $this->module->gildingMaterials(),
							'wrapping' => $this->module->wrapping(),
							'primaries' => $this->module->primaries(),
							'groups' => Group::getGroups(Context::getContext()->language->id),
							'ajax_papers_selector_url' => '/index.php', // $this->context->link->getModuleLink('clariprint','paper')
							'ajax_papers_selector_url2' => $this->context->link->getModuleLink('clariprint','paper') ,
							'direct_servers' => ClariprintServer::directs(),
							'marketplaces' => ClariprintServer::marketplaces(),
							'folds' => $this->module->getFolds(),
							'folderdie' => $this->module->getFolderDie(),
							'remove_product' => true
						 	));
		
		$this->module->registerSmartyPlugins();
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);		
		$tpl = _PS_MODULE_DIR_.'/clariprint/views/front/'.$product_kind.'.tpl';
		echo $smart->display($tpl);
		die();		
		
	}
	
	
	public function displayAjaxBookComponent()
	{
		$kind  = Tools::getValue('kind');
		$index  = Tools::getValue('index');
		$path  = Tools::getValue('product_path');
		$admin = Tools::getValue('admin');
		if (!$index) $index = 'component_'.uniqid();
		$smarty = $this->context->smarty;
		$smarty->assign(array(
			'product' => new stdClass(),
			'ui_mode' => Configuration::get('CL_FRONT_UI_MODE'),
			'product_key' => Tools::getValue('productkey').'[components]['.$index.']', // 'clariprint_product[components]['.$index.']',
//					'product' => json_decode($this->current_config->product_json),
			'vernis_en_lignes' => $this->module->finishinghInlineFull(),
			'vernis_en_reprise' => $this->module->finishingOfflineFull(),
			'vernis_combines' => $this->module->list_vernis_combines,
			'primary_colors' => array('cyan','magenta','yellow','black'),
			'sepcial_colors' => array('pms1','pms2','pms3','pms4'),
			'paper_kinds' => $this->module->paperKinds(),
			'ajax_papers_selector_url' => '/index.php', // $this->context->link->getModuleLink('clariprint','paper'),
			'papers' => $this->module->getPapers()));
		switch ($kind) {
			case "folded":
				$smarty->assign(array('folds' => $this->getFolds()));
				break;
			case "folder":
				$smarty->assign(array('folderdie' => $this->getFolderDie()));
				break;
		}
		smartyRegisterFunction(Context::getContext()->smarty,'modifier','infoFinishing',array($this->module,'infoFinishing'));
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);		
		$smarty->error_reporting = E_ALL & ~E_NOTICE;
		if ($admin) $path = '/views/admin/'. $kind .'.tpl';
		else $path = '/views/front/'. $kind .'.tpl';
		echo $this->module->display('clariprint', $path);
		die();
	}
	public function displayAjaxBookCover()
	{
		$index  = Tools::getValue('index');
		$path  = Tools::getValue('product_path');
		$admin = Tools::getValue('admin');
		$prod = new stdClass();
		$prod->kind = 'cover';
		if (!$index) $index = 'cover_'.uniqid();;
		$smarty = $this->context->smarty;
		$smarty->assign(array(
			'product' => $prod,
			'ui_mode' => Configuration::get('CL_FRONT_UI_MODE'),
			'product_key' => Tools::getValue('productkey').'[cover]', // 'clariprint_product[cover]',
			'vernis_en_lignes' => $this->module->finishinghInlineFull(),
			'vernis_en_reprise' => $this->module->finishingOfflineFull(),
			'vernis_combines' => $this->module->list_vernis_combines,
			'primary_colors' => array('cyan','magenta','yellow','black'),
			'sepcial_colors' => array('pms1','pms2','pms3','pms4'),
			'processes' => $this->module->processes(),
			'paper_kinds' => $this->module->paperKinds(),
			'ajax_papers_selector_url' => '/index.php', // $this->context->link->getModuleLink('clariprint','paper'),
			'papers' => $this->module->getPapers(),
			'folds' => $this->module->getFolds(),
			'folderdie' => $this->module->getFolderDie()));

		smartyRegisterFunction(Context::getContext()->smarty,'modifier','infoFinishing',array($this->module,'infoFinishing'));
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);
		$smarty->error_reporting = E_ALL & ~E_NOTICE;
		if ($admin) $path = '/views/admin/cover.tpl';
		else $path = '/views/front/cover.tpl';
		echo $this->module->display('clariprint', $path);
		die();
	}
}



