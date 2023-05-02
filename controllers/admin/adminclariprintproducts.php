<?php
	
define('MAX_LINE_SIZE', 0);
require_once(dirname(__FILE__) .'/../../classes/clariprint_config.php');
	
class AdminClariprintProductsController extends ModuleAdminController
{

	public function __construct()
	{
		$this->table = 'clariprint_product';
		$this->identifier = 'product_id';
		$this->className = 'Clariprint_Config';
		$this->lang = false;
//		$this->addRowAction('edit'); 
//		$this->addRowAction('view');
//		$this->addRowAction('delete');
		$this->addRowAction('select');

		$this->allow_export = true;
		$this->deleted = false;
		$this->context = Context::getContext();

		$this->_join = '';
		$this->_orderBy = 'product_id';
		$this->_orderWay = 'DESC';
		$this->_where = '';
//		$state_array = array(0 => $this->l('-'), 1 => 'Calculated', -1 => 'Error');
		/* $this->fields_list = array(
			'reference' => array(
				'title' => $this->l('Reference'),
				'align' => 'left',
				'width' => 65,
				'filter_key' => 'tp.reference'),
			'kind' => array(
//  			  	'active' => 'status', 
  				'title' => $this->l('Kind'),
				'align' => 'left',
				'filter_key' => 'kind',
				'width' => 65),
			'price' => array(
				'title' => $this->l('Price'),
				'type' => 'price',
//				'currency' => true,
				'align' => 'right',
				'filter_key' => 'price',
				'width' => 65),
			'state' => array(
				'title' => $this->l('State'),
//  			  	'active' => 'status', 
								'align' => 'center',
				'type' => 'select',
				'list' => $state_array,
				'icon' => array(                              // If set, an icon will be displayed with icon key matching the field value.
				      -1 => 'error.png',                         // Used in combination with type == bool (optional).
				      0 => 'enabled.gif',
					  1 => 'cog.gif',
					  2 => 'ajax-loader.gif'
				    ),
				'filter_key' => 'state',
				'width' => 65),
			'dynamic' => array(
				'title' => $this->l('Dynamic'),
				'align' => 'center',
				'filter_key' => 'dynamic',
				'width' => 65)
			); */
//			die('hoh');
		parent::__construct();
	}
	
	public function printColor($value='')
	{
		return $value;
	}
	
	public function initProcess()
	{
		parent::initProcess();
	}

	public function initToolbar()
	{
		$res = parent::initToolbar();
		if (isset($this->toolbar_btn['new'])) unset($this->toolbar_btn['new']);
		if (isset($this->toolbar_btn['export'])) unset($this->toolbar_btn['export']);

		$this->toolbar_btn['refresh-cache'] = array(
			'href' => $this->context->link->getAdminLink('AdminClariprintProducts', true).'&updateprices=start',
			'desc' => $this->l('Update prices')
		);
		$this->toolbar_btn['cancel'] = array(
			'href' => $this->context->link->getAdminLink('AdminClariprintProducts', true).'&updateprices=stop',
			'desc' => $this->l('Cancel updates')
		);

		return $res;	
	}

	// Returns true if $string is valid UTF-8 and false otherwise.
	function is_utf8($string) {
    
	    // From http://w3.org/International/questions/qa-forms-utf-8.html
	    return preg_match('%^(?:
	          [\x09\x0A\x0D\x20-\x7E]            # ASCII
	        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
	    )*$%xs', $string);
    }
	
	public function ensureUtf8($x) {
		if ($this->is_utf8($x)) return $x;
		return iconv('ISO-8859-1','UTF-8',$x);
	}
	
	
	public function jsonUpdateAction() {
		
	}
	
	public function setMedia()
	{
		parent::setMedia();
		$this->addJquery();
		$this->addJqueryUI('ui.progressbar');
		
	}

	public function renderView()
	{
		$this->content .= "coucou";
		
		return parent::renderView();
	}
	
	static public function countToCalculate() {
		return Db::getInstance()->getValue(sprintf('SELECT count(id) FROM %sclariprint_product WHERE state > 1',_DB_PREFIX_));
	}

	public function ajaxProcessFile()
	{
		require_once dirname(__FILE__) .'/../../classes/clariprintproductprocess.php';
		header('Content-Description: File Transfer');
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename='.$this->l('Dossier_de_frabiication_').'.pdf');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		$content = ClariprintProductProcess::pdfForProduct((int)Tools::getValue('processfile'));
		header('Content-Length: ' . strlen($content));
		ob_clean();
		flush();
		
		echo $content;
		
	}
	
	public function ajaxProcessProductUpdate()
	{
		$res = array();

		$id_product = Tools::getValue('id_product');
		if (!$id_product &&  is_array(Tools::getValue('form')))
		{
			$form = Tools::getValue('form');
			$id_product = $form['id_product'];
		}
		

		if ($id_product) {
			$clariprint_product_kind = Tools::getValue('clariprint_product_kind');
			$update  = Tools::getValue('clariprint_update_config');
			if ($update == '-1') {
				Clariprint_Config::deleteForProductId($id_product);
				
				$res['info'] = 'Deleted';
				$res['status'] = 'OK';
				
			} else {


				$cfg = Clariprint_Config::loadByIdProduct($id_product);

				$cfg->product_kind = $clariprint_product_kind;
				$cfg->product_id = $id_product;
				$product = Tools::getValue('clariprint_product');
				
				$product['kind'] = $clariprint_product_kind;
				$cfg->product_json = json_encode($product);
				
//				print_r($cfg);
				
				if (isset($cfg->id))
				{	if (!$cfg->update()) throw new PrestaShopException('Cannot update clariprint setup'); }
				elseif(!$cfg->add()) throw new PrestaShopException('Cannot create clariprint setup');
				$res['info'] = 'Updated';
				$res['status'] = 'OK';
				$res['id_product'] = $id_product;
				$res['jsons'] = json_decode($cfg->product_json);
			}
		} else {
			$res['info'] = 'unknwon product id';
			$res['status'] = 'ERROR';
		}
		die(Tools::jsonEncode($res));
	}
	
	public function displayAjaxCopyConfig()
	{
		echo 'displayAjaxCopyProduct';
		$from = Tools::getValue('from');
		$to = Tools::getValue('to');
		echo "$from to $to";
		Clariprint_Config::deleteForProductId($to);
		$cfg = Clariprint_Config::loadByIdProduct($from);
		$cfg->product_id = $to;
		$cfg->id = null;
		if ($cfg->add())
		{	
			echo 'OK';
			die('OK');
		} else {
			echo 'ERROR';
			die();
		}
	}

	public function assignParametric()
	{
		$this->context->smarty->assign('layouts', ClariprintConnect::getLayouts());

//		$this->context->smarty->assign('lcayouts', $this->module->parametricLayouts());

	}
	
	public function displayAjaxAddProduct()
	{
		$product_kind = Tools::getValue('product_kind');
		$smart = $this->context->smarty;

		$product_template = "./" . $product_kind .'.tpl';
		
		$product = new stdClass();
		$product->kind = $product_kind;



		$smart->assign(array(
							'product' => $product,
							'ui_mode' => Configuration::get('CL_BACK_UI_MODE'),
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
							'marketplaces' => ClariprintServer::marketplaces()
						 	));
		$localhdl = 'assign'.ucfirst($product->kind);
		if (method_exists($this, $localhdl)) call_user_func([$this,$localhdl]);
		else echo "$localhdl not exists";

		
		switch ($product_kind) {
			case "folded":
				$smart->assign(array('folds' => $this->module->getFolds()));
				break;
			case "folder":
				$smart->assign(array('folderdie' => $this->module->getFolderDie()));
				break;
					
		}
		$this->module->registerSmartyPlugins();
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);
		if ($product_kind)
		{
			$tpl = _PS_MODULE_DIR_.'/clariprint/views/admin/'.$product_kind.'.tpl';
			echo $smart->display($tpl);
		}	
		die();
	}


	public function ajaxProcessUpdatePrice()
	{
//		$reste = Db::getInstance()->getValue(sprintf('SELECT count(id) FROM %sclariprint_product WHERE state > 1',_DB_PREFIX_));
//		$res = array('left' => $reste);
		$current = Db::getInstance()->getValue(sprintf('SELECT count(id) FROM %sclariprint_product WHERE state = 3',_DB_PREFIX_));
		$error = true;
		
		if (!$current) {
			$id = Db::getInstance()->getValue(sprintf('SELECT id FROM %sclariprint_product WHERE state = 2',_DB_PREFIX_));
			$cfg = new Clariprint_Config($id);
			$v = json_encode(array('clariprint_product' => $cfg->product()));
			$res = ClariprintConnect::quoteRequest($v);

			if (isset($res->costs))
			{
				if ($costs = $res->costs)
				{
					Db::getInstance()->execute(sprintf('UPDATE  %sclariprint_product SET date_cal= NOW(), state = 3 WHERE id = %d',_DB_PREFIX_,$id));
					$res->costs = ClariprintMargin::applyForGroup(1,$costs->paper,$costs->print,$costs->makeready,$costs->packaging,$costs->delivery);
					$res->response = $res->costs['total'];
					$total = (float)$res->costs['total'];
					if ($res->costs['discount'] != 0)
					{
						$res->responseTxt = Tools::displayPrice($v, null) . sprintf(' HT (%.2f%%)',$res->costs['discount']); 
					} else $res->responseTxt = Tools::displayPrice($v, null) . ' HT'; 
					$prod = new Product($id);
					$prod->price = $total;
					$prod->update();
					$error = false;
				}
			}
			if ($error)
				Db::getInstance()->execute(sprintf('UPDATE  %sclariprint_product SET state = -1 WHERE id = %d',_DB_PREFIX_,$id));
			else
				Db::getInstance()->execute(sprintf('UPDATE  %sclariprint_product SET state = 1 WHERE id = %d',_DB_PREFIX_,$id));
		}
		$reste = (int)self::countToCalculate();
		die(Tools::jsonEncode($reste));
	}

	public function renderList()
	{
		if (Tools::isSubmit('updateprices')) {
			if (Tools::getValue('updateprices') == 'start') {
				Db::getInstance()->execute(sprintf('UPDATE %sclariprint_product SET state = 2 WHERE state IN (-1,0,1)',_DB_PREFIX_));
			} else 	if (Tools::getValue('updateprices') == 'stop') {
				Db::getInstance()->execute(sprintf('UPDATE %sclariprint_product SET state = 1 WHERE state > 2',_DB_PREFIX_));
			}
		}	
		$res = self::countToCalculate();
		if ($res > 0)
		{
			$html = '
				<script type="text/javascript">
		
				ClariprintUpdateAdminPrice = function() {
				jQuery.ajax("index.php",{
						"type": "POST",
						"data" : {
							"controller" : "AdminClariprintProducts",
							"ajax" : 1,
							"action" : "updatePrice",
							token: "'. Tools::getValue('token') .'"
						},
						"dataType" : "json",
						"error" : function( jqXHR,  textStatus,  errorThrown ) {
							$("#progress-label").html("error: " + textStatus);
						},
						"success": function(data,status,jqXHR) {
							var max = $( "#progressbar" ).progressbar( "option", "max");
							var rest = data;
							$( "#progressbar" ).progressbar( "option", "value", max - data );
							if (data > 0) ClariprintUpdateAdminPrice();
						}
					});
				
			};
			
			jQuery(function(){ $( "#progressbar" ).progressbar({
					max: '. $res .',
					value: 0});
					ClariprintUpdateAdminPrice();
					});
				</script>
				<div id="progressbar" style="position: relative;"><div id="progress-label" style="background: transparent; position: absolute; left: 50%; top: 4px; font-weight: bold; text-shadow: 1px 1px 0 #fff;"></div></div>';
			$this->content .= $html;
		}
		return parent::renderList();
	}
	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = false, $id_lang_shop = false)
	{
		$query = sprintf('SELECT tp.id_product as `product_id`, tp.reference as `reference`, tcp.product_kind as `kind`, tp.price as `price`, tcp.state as `state`, tcp.dynamic as `dynamic` 
					FROM %sproduct as tp
					RIGHT JOIN %sclariprint_product as tcp ON tcp.product_id = tp.id_product',
					_DB_PREFIX_,_DB_PREFIX_);
		if ($orderby = Tools::getValue('clariprint_productOrderby'))
			$query .= ' ORDER BY ' .$this->fields_list[$orderby]['filter_key'];
		elseif ($orderby = $this->_orderBy) $query .= ' ORDER BY ' . $this->_orderBy;
		if ($orderby & $orderway = Tools::getValue('clariprint_productOrderway')) $query .= ' '. ($orderway == 'desc' ? 'DESC' : 'ASC');
		if ($limit)
			$query .= sprintf(' LIMIT %s, %s ', $start, $limit);
		$v = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
		$this->_list = Db::getInstance()->executeS($query);
		$this->_listTotal = Db::getInstance()->getValue('SELECT FOUND_ROWS() AS `'._DB_PREFIX_.'clariprint_product`');
		return $v; 
	}

	public function renderForm()
	{
		Tools::redirectAdmin($this->context->link->getAdminLink('AdminProducts').'&id_product='.(int)Tools::getValue('product_id').'&updateproduct&key_tab=ModuleClariprint&conf=4');
		return parent::renderForm();
	}
	
	
	
	public function getDeliveriesOptions() {
		$countries = Configuration::get('CL_DELIVERY');
		if (!$countries) $countries  = 'fra';
		$mycountries = explode(',', $countries );
		$res = array();
		$geodir = _PS_MODULE_DIR_.'clariprint/geo/';
		
		foreach($mycountries as $c) {
			$fname= $geodir .  $c.  '.json';
			if (is_file($fname))
			{
				$fx = file_get_contents($fname);
				$x = json_decode($fx);
				$res[] = $x;
			}	
		}
		return $res;
	}
	
	public function displayAjaxDeliveryItem()
	{
//		echo Configuration::get('CL_DELIVERIES_SUB');
		$this->context->smarty->assign(
			array(	'deliveries' => $this->module->getDeliveriesOptions(),
					'delivery_default'  => Configuration::get('CL_DELIVERY_DEFAULT'),
					'ui_mode' => Configuration::get('CL_BACK_UI_MODE'),
					'delivery_nodelete' => false,
					'delivery_mode' => 'multiple',
					'no_sub_regions' => Configuration::get('CL_DELIVERIES_SUB'),
					'k' => uniqid('d'),
					'product_key' => Tools::getValue('product_key')));
		return $this->context->smarty->display(_PS_MODULE_DIR_.'/clariprint/views/front/delivery_item.tpl');
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
			'product_key' => Tools::getValue('productkey').'[components]['.$index.']', // 'clariprint_product[components]['.$index.']',
//					'product' => json_decode($this->current_config->product_json),
			'vernis_en_lignes' => $this->module->finishinghInlineFull(),
			'vernis_en_reprise' => $this->module->finishingOfflineFull(),
			'vernis_combines' => $this->module->list_vernis_combines,
			'primary_colors' => array('cyan','magenta','yellow','black'),
			'sepcial_colors' => array('pms1','pms2','pms3','pms4'),
			'paper_kinds' => $this->module->paperKinds(),
			'processes' => $this->module->processes(),
			'groups' => Group::getGroups(Context::getContext()->language->id),
			'ajax_papers_selector_url' => '/index.php', // $this->context->link->getModuleLink('clariprint','paper'),
			'folds' => $this->module->getFolds(),
			'folderdie' => $this->module->getFolderDie(),
			'papers' => $this->module->getPapers()));
		
		smartyRegisterFunction(Context::getContext()->smarty,'modifier','infoFinishing',array($this->module,'infoFinishing'));
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);		
		$smarty->error_reporting = E_ALL & ~E_NOTICE;
		$path = '/views/admin/'. $kind .'.tpl';
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
			'groups' => Group::getGroups(Context::getContext()->language->id),
			'folds' => $this->module->getFolds(),
			'folderdie' => $this->module->getFolderDie()));

		smartyRegisterFunction(Context::getContext()->smarty,'modifier','infoFinishing',array($this->module,'infoFinishing'));
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);
		$smarty->error_reporting = E_ALL & ~E_NOTICE;
		$path = '/views/admin/cover.tpl';
		echo $this->module->display('clariprint', $path);
		die();
	}

	public function displayAjaxParametricTemplate()
	{
		$layout = Tools::getValue('layout');
		$params = ClariprintConnect::sugarGetJsonParameters($layout);
		$path = '/views/admin/parametric_content.tpl';
		$smart = $this->context->smarty;
		$product_template = "./parametric.tpl";
		$product = new stdClass();
		$product->kind = 'parametric';
		$product->layout = $layout;
		$product->parameters = $params->parameters;

		$smart->assign(array(
			'product' => $product,
			'parameters' => $product->parameters,
			'ui_mode' => Configuration::get('CL_BACK_UI_MODE'),
			'link' => $this->context->link,
			'product_key' => Tools::getValue('product_key'),
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
			'marketplaces' => ClariprintServer::marketplaces()
		 	));
		$localhdl = 'assign'.ucfirst($product->kind);
		if (method_exists($this, $localhdl)) call_user_func([$this,$localhdl]);
		// else echo "$localhdl not exists";
		
		$this->module->registerSmartyPlugins();
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);		
		$tpl = _PS_MODULE_DIR_.'/clariprint/views/admin/parametric_content.tpl';
		echo $smart->display($tpl);
		die();
	}




}
