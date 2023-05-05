<?php
/*
* 2012 expert-solutions
*
*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2012 Expert Solutions SARL
*  @version  Release: $Revision: 14011 $
*  @license	Proprietary EXPERT SOLUTIONS SARL
*/
if (!defined('_PS_VERSION_'))
	exit;

if (!defined('E_DEPRECATED' )) define( 'E_DEPRECATED', 8192 );


function clariprint_autoloader($class) {
	$f = null;

	switch($class) {
		case 'ClariprintAsset' : 	$f = '/classes/clariprintasset.php'; break;
		case 'ClariprintServer' : 	$f = '/classes/clariprintserver.php'; break;
		case 'Clariprint_Config' : 	$f = '/classes/clariprint_config.php'; break;
		case 'ClariprintPaper' :	$f = '/classes/clariprint_paper.php'; break;
		case 'ClariprintMargin' : 	$f = '/classes/clariprintmargin.php'; break;
		case 'ClariprintConnect' : 	$f = '/classes/clariprintconnect.php'; break;
		case 'ClariprintCustomization' : 	$f = '/classes/clariprintcustomization.php'; break;
		case 'ClariprintProductProcess' : $f = '/classes/clariprintproductprocess.php'; break;
		case 'ClariprintCategory' : $f = '/classes/clariprintcategory.php'; break;
		case 'ClariprintSolverModuleFrontController' : $f = '/controllers/front/solver.php'; break;
	}
	if ($f) require_once(dirname(__FILE__) . $f);
}
spl_autoload_register('clariprint_autoloader');

function in_array_silent($val,$array)
{
	if (!$array) return false;
	if (!$val) return false;
	return in_array($val,$array);
}

class Clariprint extends Module
{
	private $hooks;
	
	var $paper_kinds;
	var $current_config = null;
	
	public function setupDatabase()
	{
		Clariprint_Config::createTable();
		ClariprintPaper::createTable();
		ClariprintMargin::createTable();
//		ClariprintSolverModuleFrontController::createTables();
		ClariprintCategory::install();
		
		if (!$this->checkDatabase())
		{	Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'clariprint_product` (
			id int AUTO_INCREMENT PRIMARY KEY,
			product_id int(11),
			product_kind varchar(30),
			product_json longblob,
			product_xml longblob)');
//			CONSTRAINT FOREIGN KEY (product_id) REFERENCES `'._DB_PREFIX_.'product` (product_id))' );
		}
		
//		Clariprint_Customer::createTable();
	}
	
	public function checkDatabase()
	{
		$res = Db::getInstance()->executeS('SHOW TABLES LIKE \''._DB_PREFIX_.'clariprint_product\'');
		return (count($res) == 1);
	}

	public function __construct()
	{
		$this->name = 'clariprint';
		$this->tab = 'market_place';
		$this->version = 2;
		$this->author = 'EXPERT SOLUTIONS SARL';
		$this->need_instance = 0;
		$this->configs = array('CL_DEFAULT_HOST','CL_DEFAULT_LOGIN','CL_DEFAULT_PASSWORD','CL_DEFAULT_CSS','CL_DB_VERSIONS', 'CL_HARD_PROOF', 'CL_FRONT_UI_MODE','CL_BACK_UI_MODE');
		parent::__construct();
		$this->displayName = $this->l('Clariprint');
		$this->description = $this->l('Clariprint et PrestaShop.');
		
		$this->hooks = array(
			/* front */
			'displayHeader',
			
			'displayProductButtons', //Put new action buttons on product page

			'displayFooterProduct', // Add new blocks under the product description
			
			'displayCustomerAccount',
			
			'filterProductContent', //  appelé avant l'affichage -> permet de declarer le produit commen étant "clariprint"

			'actionProductUpdate',
//			'displayRightColumnProduct',
			'leftColumn',

			'actionOrderStatusPostUpdate',
			
			'displayCustomization',

			'actionProductDelete',
		//	'actionValidateOrder', /// ENVOIE DE MAIL .... TODO
			
//			'displayPDFInvoice',
			'displayPdfInvoiceAppendices',
			
			/* admin */	
//			'displayAdminOrder',
			'displayBackOfficeHeader', // permet d'ajouter les js et css
			'displayAdminCustomers', // admin
			'displayAdminProductsExtra',
			
			'displayAdminProductsMainStepLeftColumnBottom',
			
			'displayProductExtraContent',			
			
			'displayCustomerAccount',
			
			
			'clariprintGetCustomizationInfos'
			
//			'displayAttachmentDetail'
		);
		
	}

	var $direct_messaging = false;
	public function addInfo($value)
	{
		if ($this->direct_messaging) printf('<div class="alert alert-info" role="alert"><p class="alert-text">%s</p></div>',$value);
		$this->_confirmations[] = array('class'=>'info','txt' => $value);
	}
	public function addWarn($value)
	{
		if ($this->direct_messaging) printf('<div class="alert alert-warning" role="alert"><i class="material-icons">info_outline</i><p class="alert-text">%s</p></div>',$value);
		$this->_confirmations[] = array('class'=>'warn','txt' => $value);
	}
	public function addError($value)
	{
		if ($this->direct_messaging) printf('<div class="alert alert-danger" role="alert"><i class="material-icons">error_outline</i><p class="alert-text">%s</p></div>',$value);
		$this->_confirmations[] = array('class'=>'error','txt' => $value);
	}
	var $error_log = null;
	public function log($v)
	{
		if (!$this->error_log) $this->error_log = fopen(_PS_MODULE_DIR_ ."/clariprint/logs/debug.log",'a');
		if ($this->error_log)
		{
			if (is_array($v)) fwrite($this->error_log, print_r($v,true));
			else fwrite($this->error_log, $v);
			fwrite($this->error_log,"\n");
		}
	}

	static public function getConfig($product_id) {
		return (bool)Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'clariprint_product` WHERE `id_product` = '.(int)$product_id);
	}
	
	
	public function hookDisplayLeftColumn($params='')
	{
		return $this->hookLeftColumn($params);
	}



	public function hookLeftColumn($params='')
	{
		/*
		if (Context::getContext()->customer->isLogged() === true)
		{
			if ($cat = ClariprintCategory::getCategory(Context::getContext()->customer->id,false))
			{
				
				
				$this->smarty->assign(array('category_url2' => $this->context->link->getCategoryLink($cat->id_category),
										'isDhtml' => true,
											'category_url' => sprintf(__PS_BASE_URI__. 'index.php?id_category=%d&controller=category',$cat->id_category)));
				$display = $this->display(__FILE__, 'views/hook/blockcategories.tpl');
				return $display;
				
			}
			
		}
		return null;
		*/
	}
	
	
	
	
	
	/*  Mail 
	// * info client
	// * Dossier de fab avec prix 
	// * URL DL fichiers
	// 	*  LISTE DES FICHIERS
	// 	*  par fichiers : boites a cocher 
	//			- OK
	//			-liste de pb 
	//   => mail au client avec liste des pb si necessaire et URL correspondante
	//  		
	// TODO
	*/
	public function hookActionValidateOrder()
	{
		// Getting differents vars
		$context = Context::getContext();
		$id_lang = (int)$context->language->id;
		$id_shop = (int)$context->shop->id;
		$currency = $params['currency'];
		$order = $params['order'];
		$customer = $params['customer'];
		$configuration = Configuration::getMultiple(
			array(
				'PS_SHOP_EMAIL',
				'PS_MAIL_METHOD',
				'PS_MAIL_SERVER',
				'PS_MAIL_USER',
				'PS_MAIL_PASSWD',
				'PS_SHOP_NAME',
				'PS_MAIL_COLOR'), $id_lang, null, $id_shop);

		$delivery = new Address((int)$order->id_address_delivery);
		$invoice = new Address((int)$order->id_address_invoice);
		$order_date_text = Tools::displayDate($order->date_add);
		$carrier = new Carrier((int)$order->id_carrier);
		$message = $this->getAllMessages($order->id);

		if (!$message || empty($message))
			$message = $this->l('No message');

		$items_table = '';

		$products = $params['order']->getProducts();

		foreach ($products as $key => $product)
		{
			$unit_price = $product['product_price_wt'];
			$items_table .=
				'<tr style="background-color:'.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
					<td style="padding:0.6em 0.4em;">'.$product['product_reference'].'</td>
					<td style="padding:0.6em 0.4em;">
					<strong>'
					.$product['product_name'].(isset($product['attributes_small']) ? ' '.$product['attributes_small'] : '').(!empty($customization_text) ? '<br />'.$customization_text : '').
		'</strong>
	</td>';
					
		}
		if ($delivery->id_state)
			$delivery_state = new State((int)$delivery->id_state);
		if ($invoice->id_state)
			$invoice_state = new State((int)$invoice->id_state);

// Filling-in vars for email
		$template_vars = array(
			'{firstname}' => $customer->firstname,
			'{lastname}' => $customer->lastname,
			'{email}' => $customer->email,
			'{delivery_block_txt}' => MailAlert::getFormatedAddress($delivery, "\n"),
			'{invoice_block_txt}' => MailAlert::getFormatedAddress($invoice, "\n"),
			'{delivery_block_html}' => MailAlert::getFormatedAddress(
				$delivery, '<br />', array(
					'firstname' => '<span style="color:'.$configuration['PS_MAIL_COLOR'].'; font-weight:bold;">%s</span>',
					'lastname' => '<span style="color:'.$configuration['PS_MAIL_COLOR'].'; font-weight:bold;">%s</span>'
				)
			),
			'{invoice_block_html}' => MailAlert::getFormatedAddress(
				$invoice, '<br />', array(
					'firstname' => '<span style="color:'.$configuration['PS_MAIL_COLOR'].' font-weight:bold;">%s</span>',
					'lastname' => '<span style="color:'.$configuration['PS_MAIL_COLOR'].'; font-weight:bold;">%s</span>'
				)
			),
			'{delivery_company}' => $delivery->company,
			'{delivery_firstname}' => $delivery->firstname,
			'{delivery_lastname}' => $delivery->lastname,
			'{delivery_address1}' => $delivery->address1,
			'{delivery_address2}' => $delivery->address2,
			'{delivery_city}' => $delivery->city,
			'{delivery_postal_code}' => $delivery->postcode,
			'{delivery_country}' => $delivery->country,
			'{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
			'{delivery_phone}' => $delivery->phone ? $delivery->phone : $delivery->phone_mobile,
			'{delivery_other}' => $delivery->other,
			'{invoice_company}' => $invoice->company,
			'{invoice_firstname}' => $invoice->firstname,
			'{invoice_lastname}' => $invoice->lastname,
			'{invoice_address2}' => $invoice->address2,
			'{invoice_address1}' => $invoice->address1,
			'{invoice_city}' => $invoice->city,
			'{invoice_postal_code}' => $invoice->postcode,
			'{invoice_country}' => $invoice->country,
			'{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
			'{invoice_phone}' => $invoice->phone ? $invoice->phone : $invoice->phone_mobile,
			'{invoice_other}' => $invoice->other,
			'{order_name}' => $order->reference,
			'{shop_name}' => $configuration['PS_SHOP_NAME'],
			'{date}' => $order_date_text,
			'{carrier}' => (($carrier->name == '0') ? $configuration['PS_SHOP_NAME'] : $carrier->name),
			'{payment}' => Tools::substr($order->payment, 0, 32),
			'{items}' => $items_table,
			'{total_paid}' => Tools::displayPrice($order->total_paid, $currency),
			'{total_products}' => Tools::displayPrice($order->getTotalProductsWithTaxes(), $currency),
			'{total_discounts}' => Tools::displayPrice($order->total_discounts, $currency),
			'{total_shipping}' => Tools::displayPrice($order->total_shipping, $currency),
			'{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $currency, false),
			'{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $currency),
			'{currency}' => $currency->sign,
			'{message}' => $message
		);

		// Mail dir ..
		$iso = Language::getIsoById($id_lang);
		$dir_mail = false;
		if (file_exists(dirname(__FILE__).'/mails/'.$iso.'/new_order.txt') &&
			file_exists(dirname(__FILE__).'/mails/'.$iso.'/new_order.html')
		)
				$dir_mail = dirname(__FILE__).'/mails/';
		if (file_exists(_PS_MAIL_DIR_.$iso.'/new_order.txt') &&
			file_exists(_PS_MAIL_DIR_.$iso.'/new_order.html')
		)
				$dir_mail = _PS_MAIL_DIR_;


		// Send 1 email by merchant mail, because Mail::Send doesn't work with an array of recipients
		$merchant_mails = explode(self::__MA_MAIL_DELIMITOR__, $this->_merchant_mails);

		if ($dir_mail)
			foreach ($merchant_mails as $merchant_mail)
			{
				Mail::Send(
					$id_lang,
					'new_order',
					sprintf(Mail::l('New order : #%d - %s', $id_lang), $order->id, $order->reference),
					$template_vars,
					$merchant_mail, // to
					null, // to_name
					$configuration['PS_SHOP_EMAIL'], // from
					$configuration['PS_SHOP_NAME'], // from name
					null, // attachments array(array('name','mime','name'))
					null, // mode smtp
					$dir_mail, // path template
					null, // die ?
					$id_shop,
					null  // bcc 
				);
			}
	}
	
	
	public function checkHooks()
	{
		$this->addInfo("install hooks");
		// Retrieve hooks used by the module
		$registered  = array();	
		$sql = 'SELECT `id_hook` FROM `'._DB_PREFIX_.'hook_module` WHERE `id_module` = '.(int)$this->id;

		$result = Db::getInstance()->executeS($sql);
		foreach	($result as $row)
		{
			$hook = new Hook($row['id_hook']);
			$registered[] = $hook->name;
		}
		// remove unknwon 
		foreach($registered as $r) {
			if (!in_array($r,$this->hooks)) {
				$this->addInfo("remove hooks $r<br>");
				$this->unregisterHook($r);
			}
		}
		// add new hooks
		foreach($this->hooks as $h) {
			if (!in_array($h,$registered)) {
				$this->addInfo("add hooks $h<br>");
				$this->registerHook($h);}
		}
		
	}	
	
	public function hookDisplayAttachmentDetail($param) {
		return 'ok';
	}
	
	
	public function enable($forceAll = false)
	{
		if (!parent::enable()) return false;
		$this->setupDatabase();
		$this->checkHooks();
		$this->installTabs();
		return true;
	}

	public function disable($forceAll = false)
	{
		$this->uninstallTabs();
		return parent::disable();
	}

	public function resetTabs()
	{
		$this->uninstallTabs();
		$this->installTabs();
	}





	private function installTabs()
	{
		$idTabParent = 0;
		$idTabConfigure = (int)Tab::getIdFromClassName('CONFIGURE');
		
		
//		AdminClariprintController
		$idTabClariprint = $this->installModuleTab('AdminClariprint',  'Clariprint',$idTabConfigure, 'library_books');
		if ( $idTabClariprint)
		{
//		$idTabParent = (int)Tab::getIdFromClassName('AdminCatalog');
		$this->installModuleTab('AdminClariprintPapers',  'Papers',$idTabClariprint);
		$this->installModuleTab('AdminClariprintProducts',  'Clariprint Products',$idTabClariprint);
		$this->installModuleTab('AdminClariprintMargins',  'Clariprint Margins',$idTabClariprint);
//		$this->installModuleTab('AdminClariprintLabels', 'Labels', $idTabClariprint);
		$this->installModuleTab('AdminClariprintConfig',  'Config', $idTabClariprint);
	
//		$idTabParent = (int)Tab::getIdFromClassName('AdminAdvancedParameters');
		//if ($idTabParent)
		$this->installModuleTab('AdminClariprintServers', 'Clariprint Servers', $idTabClariprint);
			$this->installModuleTab('AdminClariprintParametrics', 'Parametrics', $idTabClariprint);
		}
	}

	private function uninstallTabs()
	{
		$this->uninstallModuleTab('AdminClariprintPapers');
		$this->uninstallModuleTab('AdminClariprintProducts');
		$this->uninstallModuleTab('AdminClariprintMargins');
		$this->uninstallModuleTab('AdminClariprintServers');
		$this->uninstallModuleTab('AdminClariprintLabels');
		$this->uninstallModuleTab('AdminClariprintConfig');
		$this->uninstallModuleTab('AdminClariprint');
		$this->uninstallModuleTab('AdminClariprint');
	}

	private function installModuleTab($tabClass, $tabName, $idTabParent,$icon = null)
	{
		$tab = new Tab();
		$tab->name = array(1 =>$tabName );
		foreach (Language::getLanguages(false) as $lang)
			$tab->name[(int)$lang['id_lang']] = $tabName;
		$tab->class_name = $tabClass;
		$tab->module = $this->name;
		$tab->id_parent = $idTabParent;
		if ($icon) $tab->icon = $icon;

		if(!$tab->save())
		{
			$this->addWarn("Can't add $tabClass / $tabName / $idTabParent");
			return false;
		}
		$this->addInfo("add tab $tabName<br>");
		
		return $tab->id;
	}
	private function uninstallModuleTab($tabClass)
	{
		$idTab = Tab::getIdFromClassName($tabClass);
		if($idTab != 0)
		{
			$tab = new Tab($idTab);
			$tab->delete();
			return true;
		}
		return false;
	}

	public function setMedia()
	{
		d("setMedia");
	}
	public function hookDisplayLeftColumnProduct($params)
	{
		return null;

	}

//	displayRightColumnProduct
//	hookproductOutOfStock


	public function hookDisplayProductButtons($param)
	{
		$html = '';
		return;
		if ($config = $this->clariprintConfig($param['product']['id_product']))
		{
			if (Context::getContext()->customer->isLogged())
				$html .= '<fieldset class="attribute_fieldset product_attributes clariprint"><button class="clariprint_save_project">'.$this->l('Save Project').'</button></fieldset>';

			$prod = json_decode($config->product_json);
			if ($prod->options->quantity == 'list') {
				$html .= '<fieldset class="attribute_fieldset product_attributes clariprint"><label class="attribute_label" for="clariprint_quantite">'. $this->l('Quantity:') . '&nbsp;</label>';
				$qts = explode("\n",$prod->options->quantities);
				$html .=  '<select name="clariprint_quantity2" id="clariprint_quantity2" class="" onchange="Clariprint.updateQuantity(this.value);" style=" height: 28px; font-size: 13.63636302947998px;">';				
				foreach($qts as $qt) {
					$sel ='';
					if ($prod->quantity == $qt) $sel = ' selected';
					$html .= sprintf('<option%s>%d</option>',$sel,$qt);
				}
				$html .= '</select></fieldset>';
			} elseif ($prod->options->quantity == 'free') {
					$html .= '<fieldset class="attribute_fieldset product_attributes clariprint"><label class="attribute_label" for="clariprint_quantite">'. $this->l('Quantity:') . '&nbsp;</label><br/>';
					$html .=  sprintf('<input type="number" name="clariprint_quantity2" id="clariprint_quantity2" class="CLInt numeric NoSubmit" onchange="Clariprint.updateQuantity(this.value);" style="  height: 28px; font-size: 13.63636302947998px;" value="%d" step="%d" cl_max="%d" cl_min="%d"/>',
						$prod->quantity,$product->quantity / 10,$product->options->quantity_to,$product->options->quantity_from);
					if ($prod->options->quantities) {
						$html .=  '<select name="clariprint_quantity3" onchange="$(\'#clariprint_quantity2\').val(this.value); Clariprint.updateQuantity(this.value);">';
						$qts = explode("\n",$prod->options->quantities);
						foreach($qts as $qt) {
							$sel ='';
							if ($prod->quantity == $qt) $sel = ' selected';
							$html .= sprintf('<option%s>%d</option>',$sel,$qt);
						}
						$html .= '</select>';
					}
					$html .= '</fieldset>';
					
			} else if ($prod->options->quantity == 'range') {
				$html .= '<fieldset class="attribute_fieldset product_attributes clariprint"><label class="attribute_label" for="clariprint_quantite">'. $this->l('Quantity:') . '&nbsp;</label>';
				$qts = explode("\n",$prod->options->quantities);
				$html .=  '<select name="clariprint_quantity2" id="clariprint_quantity2" class="" onchange="Clariprint.updateQuantity(this.value);" style=" height: 28px; font-size: 13.63636302947998px;">';
				
				$minstep = ($prod->options->quantity_to - $prod->options->quantity_from) / 20;
				$minstep = (floor($minstep / 10) * 10);
				$minstep = max($minstep,1);
				$step = max($minstep,$product->options->quantity_step);
				$i=$prod->options->quantity_from;
				for(; $i <= $prod->options->quantity_to; ) {
					$sel ='';
					if ($prod->quantity == $i) $sel = ' selected';
					$html .= sprintf('<option%s>%d</option>',$sel,$i);
					$i +=  $step;
				}
				$html .= '</select></fieldset>';
			} 
		}	
		return $html;
	}

	public function hookFilterProductContent($params)
	{
		if ($this->clariprintConfig($params['object']['id_product']))
			$params['object']['clariprint'] = true;
		return $params;
	}


	public function hookDisplayBackOfficeHeader($param)
	{
		$this->context->controller->addCSS(($this->_path).'clariprint.css', 'all');
		if (version_compare(_PS_VERSION_, '1.6.0.0') >= 0)
			$this->context->controller->addCSS(($this->_path).'admin.css', 'all');
		$this->context->controller->addJquery();
		$this->context->controller->addJqueryUi(array('ui.widget','ui.button','ui.tabs','ui.accordion','ui.dialog'));
		$this->context->controller->addJS(($this->_path).'js/clariprint.js', 'all');
	}
	
	public function hookDisplayHeader($param)
	{
//		$this->context->controller->addCSS(($this->_path).'clariprint.css', 'all');
		if ($this->context->controller->php_self == 'product')
		{
			$this->context->controller->addJqueryUi(array('ui.widget','ui.autocomplete','ui.dialog','ui.button'));
			$this->context->controller->addjQueryPlugin(array('scrollTo'));
			
			$this->context->controller->registerJavascript('modules-clariprint-base', ($this->_path).'js/clariprint.js',
															['position' => 'bottom', 'priority' => 10000]);
			$this->context->controller->registerJavascript('modules-clariprint-product', ($this->_path).'/js/product.js',
															['position' => 'bottom', 'priority' => 10001]);
			
		}
			// $this->context->controller->addJS(($this->_path).'js/product.js', 'all');
		
	}
	
	public function clariprintConfig($id_product) {
		if (!$this->current_config) 
			$this->current_config = Clariprint_Config::objectForProduct($id_product);
		return $this->current_config;
	}

    public function hookActionProductDelete($params)
	{
		Clariprint_Config::deleteForProductId($params['product']->id);
	}
	
	public function cleanDatabase()
	{
		CLariprint_Config::cleanDatabase();
	}
	
	public function hookDisplayProductTab($param) {
		if ($config = $this->clariprintConfig($param['product']->id))
		{
			return '<li><a id="clariprint_product_tab" href="#clariprint_product_tab_content">'. $this->l("Clariprint") .'</a></li>';
		}
		return null;
	}
	
	public function config($product_id) {
		return false;
	}
	
	var $checkauth = 0;
	
	public function getDeliveriesOptions() {
		return $this->countries();
	}
	
	public function deliveryPost2ZoneSmarty($params, &$smarty)
	{
		return $this->deliveryPost2Zone($params['country'],$params['postal']);
	}
	
	public function deliveryPost2Zone($country_code,$postal_code)
	{
		switch ($country_code) {
			case 'FR':
			case 'FRA':
				$country_code = 'FR';
				if (strlen($postal_code) == 4) $code = '0'. substr($postal_code,0,1);
				else $code = substr($postal_code,0,2);
				if ($code > 95) 
				{
					$code = substr($postal_code,0,3);
					if ($code == 971) return "GUA";
					elseif ($code == 972) return "MTQ";
					elseif ($code == 973) return "GLP";
					elseif ($code == 974) return "REU";
					elseif ($code == 975) return "SPM";
					elseif ($code == 976) return "MYT";
					elseif ($code == 977) return "GUF";
					elseif ($code == 986) return "WLF";
					elseif ($code == 987) return "PYF";
					elseif ($code == 988) return "NCL";
				}
				return sprintf('%s-%s',$country_code,$code);
				
				break;
			case 'BE':
				if ($postal_code < 1300) return 'BE-BRU';
				if ($postal_code < 1500) return 'BE-WBR';

				if ($postal_code < 2000) return 'BE-VBR';
				if ($postal_code < 3000) return 'BE-VAN';
				if ($postal_code < 3500) return 'BE-VBR';
				
				if ($postal_code < 4000) return 'BE-VLI';
				if ($postal_code < 5000) return 'BE-WLG';
				if ($postal_code < 6000) return 'BE-WAL';
				if ($postal_code < 6600) return 'BE-WHT';
				if ($postal_code < 7000) return 'BE-WLX';
				if ($postal_code < 9000) return 'BE-WHT';
				if ($postal_code < 9000) return 'BE-VWV';
				if ($postal_code < 10000) return 'BE-VOV';
			case 'MQ': return 'MTQ';
			case 'RE': return 'REU';
			case 'GF': return 'GUF';
			case 'GP': return 'GUA';
			case 'YT': return 'MYT';
			case 'SP': return 'SPM';
			case 'NC': return 'NCL';
				return $country_code;
			
			default:
				break;
		}
	}
	
	public function uilibs() {
		return array('jqueryui' => 'jQueryUI',
					'bootstrap' => 'Bootstrap 2',
					'bootstrap3' => 'Bootstrap 3',
					'bootstrap3' => 'Bootstrap 4');
		
	}
	
	var $world = null;
	public function world()
	{
		if ($this->world) return $this->world;
		$this->world = json_decode(file_get_contents(dirname(__FILE__).'/geo/world.json'));
		return $this->world;
	}
	
	var $allcountries = null;
	var $allcountriesmap = null;
	public function allcountries()
	{
		if ($this->allcountries) return $this->allcountries;
		
		$this->allcountries = array();
		$this->allcountriesmap = array();
		$this->fillcountries($this->world());
		usort($this->allcountries, array($this,'sortCountries'));
		
		return $this->allcountries;
	}
	
	public function sortCountries($c1,$c2)
	{
		return strcmp($c1->label, $c2->label);
	}

	function findZoneWithCode($iso,$z=null) {
		if ($z == null) $z = $this->world();
		if (isset($z->iso) && ($z->iso == $iso)) return $z;
//		if ($z->iso == $iso) return $z;
		if (isset($z->parts)) {
			foreach($z->parts as $p) 
			{
				if ($r = $this->findZoneWithCode($iso,$p)) return $r;
 			}
		}
		return null;
	}

	
	function findCountryWithCode($iso) {
		foreach($this->allcountries() as $c) {if ($c->iso == $iso || $c->iso2 == $iso) return $c; }
		return null;
	}
	
	public function fillcountries($elem)
	{
		if ($elem->kind == 'country') {
			$this->allcountries[] = & $elem;
			
		} else {
			if (isset($elem->parts))
			{
				foreach($elem->parts as $p) $this->fillcountries($p);
			}
		}
	}

	var $countries = null;
	public function countries()
	{
		if ($this->countries) return $this->countries;
		$path = $this->countriespath();
		if (is_file($path)) return json_decode(file_get_contents($path));
		return array();
	}
	
	public function countriespath()
	{
		return $this->local_path .'/countries.json';
//		return _PS_CACHE_DIR_.'/countries.json';
	}

	public function setCountries($codes)
	{
		$path = $this->countriespath();
		$res = array();
		foreach($this->allcountries() as $co) 
		{
			if (in_array($co->iso2,$codes)) {
				$res[] = $co;
			}
		}
		file_put_contents($this->countriespath(),json_encode($res));
		$this->countries = $res;
	}

	public function updateModule()
	{
			$this->uninstallOverrides();
			$output = array();
			$svn = 'svn --non-interactive --accept theirs-full --username presta  --password P5wvRBazIBqv update ' . _PS_MODULE_DIR_ .'/clariprint 2>&1';
			$res = exec($svn,$output);
			$this->addInfo(implode('<br>',$output));
			$this->runUpdates();
			$this->checkHooks();
			$this->installOverrides();
//			$this->cleanDatabase();
			$this->addInfo($this->l("Clariprint products cleaned"));
	}
	

	public function getContent() {
		if (Tools::isSubmit('resettabs'))
		{	
			$this->resettabs();
		}
		if (Tools::isSubmit('resethooks'))
		{	
			$this->checkHooks();
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
						$this->addInfo( $this->l('Create template') . ' : '.  $l['iso_code'] . '/' .$tmpname);
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
				$this->setDeliveries($del);
			}
			
			/*
			if ($del = Tools::getValue('cl_delivery'))
				Configuration::updateValue('CL_DELIVERY', pSQL(implode(',',$del)));
			else
				Configuration::updateValue('CL_DELIVERY', null);
			*/
			
			if (ClariprintConnect::checkAuth()) {
				$this->addInfo($this->l('Successfully connect to Clariprint server'));
			} else $this->addError($this->l('Unable to connect Clariprint server: check url, login and password'));
		}
		if (Tools::isSubmit('svnUpdate'))
		{
			$this->uninstallOverrides();
			$output = array();
			$svn = 'svn --non-interactive --username presta  --password P5wvRBazIBqv update ' . _PS_MODULE_DIR_ .'/clariprint 2>&1';
			
			$res = exec($svn,$output);
			$this->addInfo(implode('<br>',$output));
			$this->runUpdates();
			$this->checkHooks();
			$this->installOverrides();
//			$this->cleanDatabase();
			$this->addInfo($this->l("Clariprint products cleaned"));
			
		}
		if (Tools::isSubmit('ClariprintCleanCache'))
		{
			Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'clariprint_solver_cache`');
			$this->addInfo( "Clean cache");
		}
		
		$mcountries = array();
		foreach($this->countries() as $c) {
			$mcountries[] = $c->iso;
		}
		$this->smarty->assign(array(
			'displayName' => $this->displayName,
			'email' => Configuration::get('CLP_WYS_EMAIL'),
			
			'cl_server_url' =>  Configuration::get('CL_SERVER_URL'),
			'cl_server_login' => Configuration::get('CL_SERVER_LOGIN'),
			'cl_api_mode' => Configuration::get('CL_API_MODE'),

			'cl_mp_server_url' => Configuration::get('CL_MP_SERVER_URL'),
			'cl_mp_server_key' => Configuration::get('CL_MP_SERVER_KEY'),
			'cl_mp_group' => Configuration::get('CL_MP_GROUP'),

			'cl_mp_server_login' => Configuration::get('CL_MP_SERVER_LOGIN'),
			'cl_mp_server_pass' => Configuration::get('CL_MP_SERVER_PASS'),

			'cl_mp_group' => Configuration::get('CL_MP_GROUP'),
			'cl_mp_groups' => Group::getGroups(Context::getContext()->language->id),
			'cl_category_template' => Configuration::get('CL_CATEGORY_TEMPLATE'),
			'categories'  => Category::getCategories($this->context->language->id, true, false),
			
			'cl_hard_proof' => (float)Configuration::get('CL_HARD_PROOF'),
			'cl_use_calculated_weight' => Configuration::get('CL_USE_CALCULATED_WEIGHT'),
			'mcountries' => $mcountries,
			'countries' => $this->allcountries(),
			'cl_show_discount' => Configuration::get('CL_SHOW_DISCOUNT'),
			
			'cl_deliveries_sub_regions' => Configuration::get('CL_DELIVERIES_SUB'),
			'cl_delivery_default'  => Configuration::get('CL_DELIVERY_DEFAULT'),
			'cl_deliveries'  => $this->countries(),

			'cl_front_ui_modes' => $this->uilibs(),
			'cl_front_ui_mode' => Configuration::get('CL_FRONT_UI_MODE'),
			'cl_front_ui_add' => Configuration::get('CL_FRONT_UI_ADD'),

			'cl_back_ui_modes' => $this->uilibs(),
			'cl_back_ui_mode' => Configuration::get('CL_BACK_UI_MODE'),
			'cl_back_ui_add' => Configuration::get('CL_BACK_UI_ADD'),

			'displayName' => $this->displayName,
			'messages' => $this->_confirmations));
		return $this->display(__FILE__, 'views/admin/config.tpl');
	}
	
	public function finishingMatchInlineFull($f) {
		return ($f['index'] <= 19);
	}
	public function finishinghInlineFull() {
		return array_filter($this->finishingOptions(), array($this,"finishingMatchInlineFull"));
	}

	public function finishingMatchInlineSoft($f) {
		return ($f['index'] <= 12);
	}

	public function finishingInlineSoft() {
		return array_filter($this->finishingOptions(), array($this,"finishingMatchInlineSoft"));
	}

	public function finishingMatchOfflineFull($f) {
		return ($f['index'] >= 20 /* && $f['index'] <= 60 */);
	}
	public function finishingOfflineFull() {
		return array_filter($this->finishingOptions(), array($this,'finishingMatchOfflineFull'));
	}

	public function finishingMatchOfflineSoft($f) {
		switch($f['index']) {
			case 19:
			case 21:
			case 22:
			case 24:
			case 31;
			case 33:
			return true;
		}
		return false;
	}
	public function finishingOfflineSoft($f) {
		return array_filter($this->finishingOptions(), array($this,'finishingOfflineSoft'));
	}
	
	static public function finishingTxt($v)
	{
		return self::finishingKindTxt($v) . self::finishingFinitionTxt($v) . self::finishingSelectifTxt($v);
	}
	
	static function finishingKindTxt($v) {
		return 'vernis';
	}
	
	static function finishingFinitionTxt($v) {
		switch($v['finition']) {
			case 'BRILLANT': return ' brillant';
			case 'MAT': return ' mat';
			case 'SATIN': return ' satin';
		}
		return '';
	}

	public  function infoFinishingSmarty($params, &$smarty)
	{
		if (isset($params['txt'])) return $this->infoFinishing($params['txt']);
		return "?";
	}	
	public function infoFinishing($txt)
	{
		if ($txt == '') return $this->l('None');
		$vs = explode("+",$txt);
		$res = array();
		$l = self::finishingOptions();
		foreach($vs as $v)
		{
			if (isset($l[$v])) {
				$vo = $l[$v];
				$res[] = $vo['info'];
			}
		}
		return implode(' + ',$res);
	}
	
	static public function setupFolds($params, &$smarty)
	{
		$mod = self::getInstanceByName('clariprint');
		$product = $params['product'];
		$res = array();
		$pages = $mod->getFolds();
		foreach($pages as $page => $folds)
		{
			$p = array();
			foreach($folds as $f)
			{
				if (in_array_silent($f['index'],$product->options->folds)) {
					$p[] = $f;
				}
			}
			if (count($p) > 0) $res[$page] = $p;
		}
		$smarty->assign(array('productfolds' => $res));
		return null;
	}
	
	public function getFolderDie() {
		return array(array('index' => 1, 'title' => $this->l('folder with 1 flap without leg of joining')),
					array('index' => 2, 'title' => $this->l('folder with 1 flap without leg of joining')),
					array('index' => 3, 'title' => $this->l('folder with 1 flap and a glue flap')),
					array('index' => 4, 'title' => $this->l('folder with 1 flap and a glue flap')),
					array('index' => 5, 'title' => $this->l('folder with 2 flaps without locking flap')),
					array('index' => 6, 'title' => $this->l('folder with 2 flaps without locking flap')),
					array('index' => 7, 'title' => $this->l('folder with 2 flaps and a locking flap')),
					array('index' => 8, 'title' => $this->l('folder with 2 flaps and a locking flap')),
					array('index' => 9, 'title' => $this->l('folder with 2 flaps + visit cards slotting + locking flap')),
					array('index' => 10, 'title' => $this->l('folder with 2 flaps + visit cards slotting + locking flap')),
					array('index' => 11, 'title' => $this->l('folder with 2 flaps + visit cards slotting + locking flap')),
					array('index' => 12, 'title' => $this->l('folder with 2 flaps + visit cards slotting + locking flap')),
					array('index' => 13, 'title' => $this->l('folder with 2 flaps in window and a locking flap')),
					array('index' => 14, 'title' => $this->l('folder with 2 flaps in window and a locking flap')),
					array('index' => 15, 'title' => $this->l('folder with 3 flaps without locking flap')),
					array('index' => 16, 'title' => $this->l('folder with 3 flaps without locking flap')),
					array('index' => 17, 'title' => $this->l('folder with 3 flaps and a locking flap')),
					array('index' => 18, 'title' => $this->l('folder with 3 flaps and a locking flap')),
					array('index' => 19, 'title' => $this->l('folder with 4 flaps without locking flap')),
					array('index' => 20, 'title' => $this->l('folder with 5 flaps without locking flap')),
					array('index' => 21, 'title' => $this->l('folder with 4 flaps and a locking flap')),
					array('index' => 22, 'title' => $this->l('folder with 5 flaps and a locking flap')));
	}
	
	public function getFolds() {
		return 	array(
	'4' => array(	array('index' => 1,  'title' => $this->l('simple fold'), 'height' => 1, 'width' => 2)),
	'6' => array(	array('index' => 2,  'title' => $this->l('2 fan folds'), 'height' => 1, 'width' => 3),
					array('index' => 3,  'title' => $this->l('2 rolled folds'), 'height' => 1, 'width' => 3),
					array('index' => 39, 'title' => $this->l('window folds'), 'height' => 1, 'width' => 2)),
	'8' => array(	array('index' => 4,  'title' => $this->l('2 parallel folds'), 'height' => 1, 'width' => 4),
					array('index' => 5,  'title' => $this->l('3 rolled folds'), 'height' => 1, 'width' => 4),
					array('index' => 6,  'title' => $this->l('3 fan folds'), 'height' => 1, 'width' => 4),
					array('index' => 10, 'title' => $this->l('2 crossed folds'), 'height' => 2, 'width' => 2),
					array('index' => 40, 'title' => $this->l('3 gate folds'), 'height' => 1, 'width' => 4)),
	'10' => array(	array('index' => 7,  'title' => $this->l('4 fan folds'), 'height' => 1, 'width' => 5)),

	'12' => array(	array('index' => 8,  'title' => $this->l('1 fold + 2 fan folds'), 'height' => 1, 'width' => 6),
					array('index' => 11, 'title' => $this->l('5 fan folds'), 'height' => 1, 'width' => 6),
					array('index' => 12, 'title' => $this->l('2 rolled folds + 1 crossed fold'), 'height' => 2, 'width' => 3),
					array('index' => 13, 'title' => $this->l('2 fan folds + 1 cross fold'), 'height' => 2, 'width' => 3),
					array('index' => 14, 'title' => $this->l('1 fold + roll folds'), 'height' => 1, 'width' => 6),
					array('index' => 15, 'title' => $this->l('1 fold + 2 fan folds'), 'height' => 3, 'width' => 2),
					array('index' => 16, 'title' => $this->l('2 rolled folds + 1 crossed fold'), 'height' => 2, 'width' => 3)),

	'14' => array(	array('index' => 17, 'title' => $this->l('6 fan folds'), 'height' => 1, 'width' => 7)),
	'16' => array(	array('index' => 9,  'title' => $this->l('1 fold + 2 parallel folds'), 'height' => 1, 'width' => 8),
					array('index' => 18, 'title' => $this->l('7 fan folds'), 'height' => 1, 'width' => 8),
					array('index' => 19, 'title' => $this->l('2 parallel folds + 1 crossed fold'), 'height' => 2, 'width' => 4),
					array('index' => 32, 'title' => $this->l('3 crossed folds'), 'height' => 2, 'width' => 4),
					array('index' => 33, 'title' => $this->l('2 parallel folds + 1 crossed fold'), 'height' => 2, 'width' => 4),
					array('index' => 'F16-11', 'title' => $this->l('3 plis accordéon + 1 pli croisé'), 'height' => 2, 'width' => 4, 'jdf' => "F16-11")),

	'20' => array(array('index' => 'F20-2','title' => $this->l('4 plis accordéon + 1 pli croisé'), 
						'height' => 2, 'width' => 5, 'jdf' => "F20-2")),
	'24' => array(	array('index' => 26, 'title' => $this->l('2 parallel folds + 2 rolled folds'), 'height' => 3, 'width' => 4),
					array('index' => 27, 'title' => $this->l('2 parallel folds + 2 fan folds'), 'height' => 3, 'width' => 4),
					array('index' => 28, 'title' => $this->l('2 fan folds + 2 parallel folds'), 'height' => 4, 'width' => 3),
					array('index' => 29, 'title' => $this->l('2 rolled folds + 2 parallel folds'), 'height' => 4, 'width' => 3),
					array('index' => 'F24-6', 'title' => $this->l('5 accordeon folds + 1 cossed fol'), 'height' => 2, 'width' => 6, 'jdf' => "F24-6")),

	'28' => array(	array('index' => 'F28-1', 
						'jdf' => "F28-1",
						'title' => $this->l('6 parallel folds + 1 cross folds'), 'height' => 2, 'width' => 7)),
	'32' => array(	array('index' => 30, 'title' => $this->l('2 parallel folds + 2 parallel folds'), 'height' => 4, 'width' => 4),
					array('index' => 35, 'title' => $this->l('1 fold + 2 parallel folds + 1 cross fold'), 'height' => 4, 'width' => 4)));
	}
	
	public function wrapping() {
		return array(
		'shrink' => $this->l('Shrink-wrapped packing'),
		'cardboard' => $this->l('cardboard wrap'),
		'cardboardbox' => $this->l('cardboard box'),
		'lostpallet' => $this->l('lost pallet packing'),
		'boxpallet' => $this->l('box pallet packing'),
		'eupallet' => $this->l('european pallet packing'),
		'elasticband' => $this->l('elastic band packing'),
		'kraft' => $this->l('under kraft paper'),
		'crosslink' => $this->l('Cross link packing'),
		'roundtube' => $this->l('Round tube'),
		'squaretube' => $this->l('Square tube'),
		'triangletube' => $this->l('Triangle tube'),
		'papertape' => $this->l('Paper tape')
	);
	}
	
	static function getPapers() {
		return array('of'=> array('qualities' => ClariprintPaper::qualities(null,'of'),
								"brands" => ClariprintPaper::brands(null,'of'),
								"colors" => ClariprintPaper::colors(null,'of'),
								"weights" => ClariprintPaper::weights(null,'of')),
					'of'=> array('qualities' => ClariprintPaper::qualities(null,'or'),
								"brands" => ClariprintPaper::brands(null,'or'),
								"colors" => ClariprintPaper::colors(null,'or'),
								"weights" => ClariprintPaper::weights(null,'or')));
	}
	
	public function paperKinds() {
		return array(
					'o' => $this->l('Sheet Offset & heatset'),
					'of' => $this->l('Sheet Offset'),
					'ofuv' => $this->l('Sheet UV Offset'),
					'or' => $this->l('Offset roto'),
					'oc'  => $this->l('Offset roto coldset'), 
					'n' => $this->l('Numérique'),
					'h' => $this->l('hélio'),
					'f' => $this->l('Flexographie'),
					's' => $this->l('Sérigraphie'));
		
	}
	public function processes() {
		return array(
				array(
					'o' => $this->l('Sheet Offset & heatset'),
					'of' => $this->l('Sheet Offset'),
					'ofuv' => $this->l('Sheet UV Offset'),
					'n' => $this->l('Numérique')),
				array(
					'or' => $this->l('Offset roto'),
					'oc'  => $this->l('Offset roto coldset'), 
					'h' => $this->l('hélio')),
				array(
					'f' => $this->l('Flexographie'),
					's' => $this->l('Sérigraphie')));
		
	}
	
	
	public function gildingMaterials()
	{
		return array('gold' => $this->l('Gold'),
					'silver' => $this->l('Silver'),
					'other' => $this->l('Other'),
					'color_resin' => $this->l('Colored resin'),
					'color_wax' => $this->l('Colored wax'));
	}
	static function setupfinishingOptions($params,&$smarty) {
		$mod = self::getInstanceByName('clariprint');
		$product = $params['product'];
		$options = $mod->finishingOptions();
		$front_inline_varnish = array();
		$back_inline_varnish = array();

		$front_all = in_array_silent('all',$product->options->finishing_front);
		$back_all = in_array_silent('all',$product->options->finishing_front);
		foreach($mod->finishinghInlineFull() as $kvel => $vel)
		{
			if ($front_all || in_array_silent($kvel,$product->options->finishing_front)) $front_inline_varnish[$kvel] = $vel;
			if ($back_all || in_array_silent($kvel,$product->options->finishing_back)) $back_inline_varnish[$kvel] = $vel;
		}
		$front_offline_varnish = array();
		$back_offline_varnish = array();

		foreach($mod->finishingOfflineFull() as $kvel => $vel)
		{
			if ($front_all || in_array_silent($kvel,$product->options->finishing_front)) $front_offline_varnish[$kvel] = $vel;
			if ($back_all ||in_array_silent($kvel,$product->options->finishing_back)) $back_offline_varnish[$kvel] = $vel;
		}
		$front_varnish_set = array();
		$back_varnish_set = array();
		
		foreach($mod->list_vernis_combines as $kvel => $vel)
		{
			if ($front_all || in_array_silent($kvel,$product->options->finishing_front)) $front_varnish_set[$kvel] = $vel;
			if ($back_all ||in_array_silent($kvel,$product->options->finishing_back)) $back_varnish_set[$kvel] = $vel;
		}
		$smarty->assign(array('front_inline_varnish' => $front_inline_varnish,
								'back_inline_varnish' => $back_inline_varnish,
								'front_offline_varnish' => $front_offline_varnish,
								'back_offline_varnish' => $back_offline_varnish,
								'front_varnish_set' => $front_varnish_set,
								'back_varnish_set' => $back_varnish_set));
		return null;
		
	}
	
	public function finishingOptions() {
		return array(
			'PROTEC_BRILLANT' => array('kind' => 'Protection','finition' => 'BRILLANT','index' => 40,
										'info' => $this->l('Gloss Protection varnish')),

			'PROTEC_MAT' => array('kind' => 'Protection', 'index' => 41,
									'finition' => 'BRILLANT',
									'info' => $this->l('Mat Protection varnish')),

			'PROTEC_SATIN' => array('kind' => 'Protection', 'index' => 42,
									'finition' => 'SATIN',
									'info' => $this->l('Silk Protection varnish')),

			'PROTEC_BRILLANT_RESERVE' => array('kind' => 'Protection','index' => 43,
									'finition' => 'BRILLANT',
									'reserve' => 30,
									'info' => $this->l('Gloss Protection varnish with space for stamp')),

			'PROTEC_MAT_RESERVE' => array('kind' => 'Protection', 'index' => 44,
									'finition' => 'BRILLANT',
									'reserve' => 30,
									'info' => $this->l('Mat Protection varnish with space for stamp')),

			'PROTEC_SATIN_RESERVE' => array('kind' => 'Protection', 'index' => 45,
									'finition' => 'SATIN',
									'reserve' => 30,
									'info' => $this->l('Silk Protection varnish with space for stamp')),


			'OFFSET_BRILLANT' => array('kind' => 'Offset','finition' => 'BRILLANT','index' => 0, 'info' => $this->l('Gloss offset varnish')),
			'OFFSET_BRILLANT_RESERVE' => array('kind' => 'Offset','finition' => 'BRILLANT','reserve' => 50,'index' => 1, 'info' => $this->l('Spot gloss offset varnish')),

			'OFFSET_SATIN' => array('kind' => 'Offset','finition' => 'SATIN','index' => 2, 'info' => $this->l('vernis offset satin')),
			'OFFSET_SATIN_RESERVE' => array('kind' => 'Offset','finition' => 'SATIN','reserve' => 50,'index' => 3, 'info' => $this->l('Silk Mat offset varnish')),

			'OFFSET_MAT' => array('kind' => 'Offset','finition' => 'MAT','index' => 4, 'info' => $this->l('Mat offset varnish')),
			'OFFSET_MAT_RESERVE' => array('kind' => 'Offset','finition' => 'MAT','reserve' => 50,'index' => 5, 'info' => $this->l('Spot Mat offset varnish')),

			'ACRILY_BRILLANT' => array('kind' => 'Acrilyque','finition' => 'BRILLANT','index' => 6, 'info' => $this->l('Gloss dispersive varnish')),
			'ACRILY_BRILLANT_RESERVE' => array('kind' => 'Acrilyque','finition' => 'BRILLANT','reserve' => 30,'index' => 7, 'info' => $this->l('Gloss dispersive with space for stamp')),

			'ACRILY_SATIN' => array('kind' => 'Acrilyque','finition' => 'SATIN','roto?' => false,'index' => 8, 'info' => $this->l('Silk dispersive varnish ')),
			'ACRILY_SATIN_RESERVE' => array('kind' => 'Acrilyque','finition' => 'SATIN','reserve' => 30,'roto?' => false,'index' => 9, 'info' => $this->l('Silk dispersive varnish with space for stamp (sheetfed only)')),

			'ACRILY_MAT' => array('kind' => 'Acrilyque','finition' => 'MAT','roto?' => false,'index' => 10, 'info' => $this->l('Mat dispersive varnish (sheetfed only)')),
			'ACRILY_MAT_RESERVE' => array('kind' => 'Acrilyque','finition' => 'MAT','reserve' => 30,'roto?' => false,'index' => 11, 'info' => $this->l('Mat dispersive varnish with space for stamp (sheetfed only)')),

			'DRIPOFF' => array('kind' => 'DripOff', 'finition' => 'BRILLANT','index' => 12, 'info' => $this->l('drip off varnish')),

			'UVO_BRILLANT' => array('kind' => 'UvOffset','finition' => 'BRILLANT','index' => 13, 'info' => $this->l('Gloss offset UV varnish')),
			'UVO_BRILLANT_RESERVE' => array('kind' => 'UvOffset','finition' => 'BRILLANT','reserve' => 30,'index' => 14, 'info' => $this->l('Gloss offset UV varnish with space for stamp')),
			'UVO_SATIN' => array('kind' => 'UvOffset','finition' => 'SATIN','index' => 15, 'info' => $this->l('Silk UV offset varnish')),
			'UVO_SATIN_RESERVE' => array('kind' => 'UvOffset','finition' => 'SATIN','reserve' => 30,'index' => 16, 'info' => $this->l('Silk UV offset varnish with space for stamp')),
			'UVO_MAT' => array('kind' => 'UvOffset','finition' => 'MAT','index' => 17, 'info' => $this->l('Matt UV offset varnish')),
			'UVO_MAT_RESERVE' => array('kind' => 'UvOffset','finition' => 'MAT','reserve' => 30,'index' => 18, 'info' => $this->l('Matt UV offset varnish with space for stamp')),


			'UVN_BRILLANT_SELECTIF10' => array('kind' => 'UvNumerique','finition' => 'BRILLANT','reserve' => 10,'index' => 30, 'info' => $this->l('Spot glossy UV varnish < 10% - digital')),
			'UVN_BRILLANT_SELECTIF30' => array('kind' => 'UvNumerique','finition' => 'BRILLANT','reserve' => 30,'index' => 31, 'info' => $this->l('Spot glossy UV varnish < 30% - digital')),
			'UVN_BRILLANT_SELECTIF50' => array('kind' => 'UvNumerique','finition' => 'BRILLANT','reserve' => 50,'index' => 32, 'info' => $this->l('Spot glossy UV varnish > 30% - digital')),


			'UVS_BRILLANT' => array('kind' => 'UvSerigraphique','finition' => 'BRILLANT','index' => 40, 'info' => $this->l('Gloss screen varnish')),
			'UVS_SATIN' => array('kind' => 'UvSerigraphique','finition' => 'SATIN','index' => 41, 'info' => $this->l('Silk screen varnish')),
			'UVS_MAT' => array('kind' => 'UvSerigraphique','finition' => 'MAT','index' => 42, 'info' => $this->l('Mat screen varnish')),

			'UVS_3D_BRILLANT_SELECTIF30' => array('kind' => 'UvSerigraphique','finition' => 'BRILLANT','index' => 64, 'reserve' => 30, 'info' => $this->l('Gloss 3D screen varnish (30%)')),																																			

			'UVS_BRILLANT_RESERVE' => array('kind' => 'UvSerigraphique','finition' => 'BRILLANT','reserve' => 70,'index' => 43, 'info' => $this->l('Spot gloss screen varnish (30%)')),
			'UVS_SATIN_RESERVE' => array('kind' => 'UvSerigraphique','finition' => 'SATIN','reserve' => 70,'index' => 44, 'info' => $this->l('Spot silk screen varnish (30%)')),
			'UVS_MAT_RESERVE' => array('kind' => 'UvSerigraphique','finition' => 'MAT','reserve' => 70,'index' => 45, 'info' => $this->l('Spot mat screen varnish (30%)')),


			'PELLIC_INDIF_BRILLANT' => array('kind' => 'Pelliculage', 'finition' => 'BRILLANT','index' => 52,
							'info' => $this->l('Best gloss lamination')),
			'PELLIC_INDIF_SATIN' => array('kind' => 'PelliculageIndifferent','finition' => 'SATIN','index' => 53,
							'info' => $this->l('Best matt lamination')),
			'PELLIC_INDIF_MAT' => array('kind' => 'PelliculageIndifferent','finition' => 'MAT','index' => 54,
							'info' => $this->l('Best silk lamination')),


			'PELLIC_ACETATE_BRILLANT' => array('kind' => 'PelliculageAcetate','finition' => 'BRILLANT','index' => 33, 'info' => $this->l('Gloss acetate lamination')),
			'PELLIC_ACETATE_SATIN' => array('kind' => 'PelliculageAcetate','finition' => 'SATIN','index' => 34, 'info' => $this->l('Silk acetate lamination')),
			'PELLIC_ACETATE_MAT' => array('kind' => 'PelliculageAcetate','finition' => 'MAT','index' => 35, 'info' => $this->l('Mat acetate lamination ')),

			'PELLIC_POLYPRO_BRILLANT' => array('kind' => 'PelliculagePolypro','finition' => 'BRILLANT','index' => 36, 'info' => $this->l('Gloss polypro lamination')),
			'PELLIC_POLYPRO_SATIN' => array('kind' => 'PelliculagePolypro','finition' => 'SATIN','index' => 37, 'info' => $this->l('Silk polypro lamination')),
			'PELLIC_POLYPRO_MAT' => array('kind' => 'PelliculagePolypro','finition' => 'MAT','index' => 38, 'info' => $this->l('Mat polypro lamination')),
			'PELLIC_SOFT_TOUCH' => array('knid' => 'PelliculageSoftTouch','finition' => 'MAT','index' => 39, 'info' => $this->l('soft touch laminating')),
			'PELLIC_POLYPRO_BRILLANT' => array('kind' => 'PelliculagePolypro','finition' => 'BRILLANT','index' => 36, 'info' => $this->l('Gloss polypro lamination')),
			
			'PELLIC_VELLEDA' => array('kind' => 'PelliculageVelleda','finition' => 'BRILLANT','index' => 56, 'info' => $this->l('Whiteboard Lamination')),

			'PELLIC_ANTIUV_BRILLANT' => array('kind' => 'PelliculageAntiUv','finition' => 'BRILLANT','index' => 58, 'info' => $this->l('Brillant Anti-UV Lamination')),
			'PELLIC_ANTIUV_MAT' => array('kind' => 'PelliculageAntiUv','finition' => 'MAT','index' => 59, 'info' => $this->l('Mat Anti-UV Lamination')),

			'PELLIC_ANTIRAYURE_MAT' => array('kind' => 'PelliculageAntiRayure','finition' => 'MAT','index' => 61, 'info' => $this->l('Anti scratch Lamination')),
			'PELLIC_SOL' => array('kind' => 'PelliculageSol','finition' => 'MAT','index' => 60, 'info' => $this->l('Floor Lamination')));
			
			
			
	}
	
	var $list_vernis_full = array(
		'PROTEC_BRILLANT','PROTEC_MAT','PROTEC_SATIN',
		'PROTEC_BRILLANT_RESERVE','PROTEC_MAT_RESERVE','PROTEC_SATIN_RESERVE',


		'OFFSET_BRILLANT','OFFSET_SATIN','OFFSET_MAT','OFFSET_BRILLANT_RESERVE','OFFSET_SATIN_RESERVE','OFFSET_MAT_RESERVE',
		'ACRILY_BRILLANT','ACRILY_SATIN','ACRILY_MAT','ACRILY_BRILLANT_RESERVE','ACRILY_SATIN_RESERVE','ACRILY_MAT_RESERVE',
		'DRIPOFF',
		'UVO_BRILLANT','UVO_SATIN','UVO_MAT','UVO_BRILLANT_RESERVE','UVO_SATIN_RESERVE','UVO_MAT_RESERVE',
		'UVS_BRILLANT','UVS_SATIN','UVS_MAT','UVS_BRILLANT_RESERVE','UVS_SATIN_RESERVE','UVS_MAT_RESERVE',
		'UVN_BRILLANT_SELECTIF10','UVN_BRILLANT_SELECTIF30','UVN_BRILLANT_SELECTIF50',
		'UVS_BRILLANT', 'UVS_SATIN', 'UVS_MAT','UVS_3D_BRILLANT_SELECTIF30',
		
		'PELLIC_INDIF_BRILLANT', 'PELLIC_INDIF_SATIN', 'PELLIC_INDIF_MAT',

		'PELLIC_ACETATE_BRILLANT','PELLIC_ACETATE_SATIN','PELLIC_ACETATE_MAT', 
		'PELLIC_POLYPRO_BRILLANT','PELLIC_POLYPRO_SATIN','PELLIC_POLYPRO_MAT',
		'PELLIC_SOFT_TOUCH','PELLIC_VELLEDA','PELLIC_ANTIUV_BRILLANT','PELLIC_ANTIUV_MAT','PELLIC_SOL' );
		

	var $list_vernis_combines = array(
		'PELLIC_ACETATE_BRILLANT+UVS_MAT_RESERVE' =>	array('PELLIC_ACETATE_BRILLANT','UVS_MAT_RESERVE'),
		'PELLIC_ACETATE_SATIN+UVS_BRILLANT_RESERVE' =>	array('PELLIC_ACETATE_SATIN','UVS_BRILLANT_RESERVE'),
		'PELLIC_ACETATE_MAT+UVS_BRILLANT_RESERVE' =>	array('PELLIC_ACETATE_MAT','UVS_BRILLANT_RESERVE'),

		'PELLIC_POLYPRO_BRILLANT+UVS_MAT_RESERVE' =>	array('PELLIC_POLYPRO_BRILLANT','UVS_MAT_RESERVE'),
		'PELLIC_POLYPRO_SATIN+UVS_BRILLANT_RESERVE' =>	array('PELLIC_POLYPRO_SATIN','UVS_BRILLANT_RESERVE'),
		'PELLIC_POLYPRO_MAT+UVS_BRILLANT_RESERVE' =>	array('PELLIC_POLYPRO_MAT','UVS_BRILLANT_RESERVE'),

		'OFFSET_BRILLANT+UVS_MAT_RESERVE' =>			array('OFFSET_BRILLANT','UVS_MAT_RESERVE'),
		'OFFSET_SATIN+UVS_BRILLANT_RESERVE' =>			array('OFFSET_SATIN','UVS_BRILLANT_RESERVE'),
		'OFFSET_MAT+UVS_BRILLANT_RESERVE' =>			array('OFFSET_MAT','UVS_BRILLANT_RESERVE'),

		'ACRILY_BRILLANT+UVS_MAT_RESERVE' =>			array('ACRILY_BRILLANT','UVS_MAT_RESERVE'),
		'ACRILY_SATIN+UVS_BRILLANT_RESERVE' =>			array('ACRILY_SATIN','UVS_BRILLANT_RESERVE'),
		'ACRILY_MAT+UVS_BRILLANT_RESERVE' =>			array('ACRILY_MAT','UVS_BRILLANT_RESERVE'),

		'ACRILY_BRILLANT+UVO_MAT_RESERVE' =>			array('ACRILY_BRILLANT','UVO_MAT_RESERVE'),
		'ACRILY_SATIN+UVO_BRILLANT_RESERVE' =>			array('ACRILY_SATIN','UVO_BRILLANT_RESERVE'),
		'ACRILY_MAT+UVO_BRILLANT_RESERVE' =>			array('ACRILY_MAT','UVO_BRILLANT_RESERVE'),

		'ACRILY_SATIN+UVN_BRILLANT_SELECTIF10' =>		array('ACRILY_SATIN','UVN_BRILLANT_SELECTIF10'),
		'ACRILY_SATIN+UVN_BRILLANT_SELECTIF30' =>		array('ACRILY_SATIN','UVN_BRILLANT_SELECTIF30'),
		'ACRILY_SATIN+UVN_BRILLANT_SELECTIF50' =>		array('ACRILY_SATIN','UVN_BRILLANT_SELECTIF50'),

		'PELLIC_SOFT_TOUCH+UVS_MAT_RESERVE' =>			array('PELLIC_SOFT_TOUCH','UVS_MAT_RESERVE'),
		'PELLIC_SOFT_TOUCH+UVS_BRILLANT_RESERVE' =>			array('PELLIC_SOFT_TOUCH','UVS_BRILLANT_RESERVE'),
		'PELLIC_SOFT_TOUCH+UVS_3D_BRILLANT_SELECTIF30' =>			array('PELLIC_SOFT_TOUCH','UVS_3D_BRILLANT_SELECTIF30'),

		'PELLIC_POLYPRO_BRILLANT+ACRILY_MAT_RESERVE' =>	array('PELLIC_POLYPRO_BRILLANT','ACRILY_MAT_RESERVE'),
		'PELLIC_POLYPRO_SATIN+ACRILY_BRILLANT_RESERVE' =>	array('PELLIC_POLYPRO_SATIN','ACRILY_BRILLANT_RESERVE'),
		'PELLIC_POLYPRO_MAT+ACRILY_BRILLANT_RESERVE' =>	array('PELLIC_POLYPRO_MAT','ACRILY_BRILLANT_RESERVE'),
		'PELLIC_POLYPRO_MAT+UVS_3D_BRILLANT_SELECTIF30' =>			array('PELLIC_POLYPRO_MAT','UVS_3D_BRILLANT_SELECTIF30'),

		'PELLIC_POLYPRO_MAT+UVN_BRILLANT_SELECTIF10' =>	array('PELLIC_POLYPRO_MAT','UVN_BRILLANT_SELECTIF10'),
		'PELLIC_POLYPRO_MAT+UVN_BRILLANT_SELECTIF30' =>	array('PELLIC_POLYPRO_MAT','UVN_BRILLANT_SELECTIF30'),
		'PELLIC_POLYPRO_MAT+UVN_BRILLANT_SELECTIF50' => array('PELLIC_POLYPRO_MAT','UVN_BRILLANT_SELECTIF50'));
	
	
	var $smartyok = false;
	public function registerSmartyPlugins()
	{
		if ($this->smartyok) return;
		smartyRegisterFunction(Context::getContext()->smarty,'modifier','infoFinishing',array($this,"infoFinishing"));
		smartyRegisterFunction(Context::getContext()->smarty,'function','ClariprintSetupFolds',array($this,"setupFolds"));
		smartyRegisterFunction(Context::getContext()->smarty,'function','ClariprintSetupFinishingOptions',array($this,"setupfinishingOptions"));
		smartyRegisterFunction(Context::getContext()->smarty,'function','displayCMS',array($this,"displayCMS"));

		smartyRegisterFunction(Context::getContext()->smarty,'function','setupParametricConfig',array($this,"setupParametricConfig"));

		smartyRegisterFunction(Context::getContext()->smarty,'function','getParametricTemplate',array($this,"getParametricTemplate"));


		
		smartyRegisterFunction(Context::getContext()->smarty,'function','deliveryPost2Zone',array($this,'deliveryPost2ZoneSmarty'));
		
		$this->smartyok = true;
	}
	
	
	
	
	public function primaries() {
		return array('cyan' => $this->l('cyan'),
					'magenta' => $this->l('magenta'),
					'yellow' => $this->l('yellow'),
					'black' => $this->l('black'),
					'4-color' => $this->l('4-color'));
		
	}
	
	public function parseOptions($k) {
	}
	
	
	public function setupCustomerAdresses() {
	}
	

	
	public function assignCustomerName() {
		$logged = $this->context->customer->isLogged();

		if ($logged) {
			$customerName = $this->getTranslator()->trans(
				'%firstname% %lastname%',
					array(
						'%firstname%' => $this->context->customer->firstname,
						'%lastname%' => $this->context->customer->lastname,
					),
					'Modules.Customersignin.Admin'
				);
		} else {
			$customerName = '';
		}
		$this->smarty->assign(array('customerName' => $customerName));
	}
	
	
	public function displayFrontProduct($param)
	{
		$id_product = $param['product']['id_product'];		
		
		$config = $this->clariprintConfig($id_product);
		if ($config) {
			$id_customer = Context::getContext()->customer->id;
			if ($id_customer) $user_groups = Customer::getGroupsStatic($id_customer);
			else $user_groups = array();

			$customer_addresses = array();
			if (isset(Context::getContext()->customer) && Context::getContext()->customer->id)
			{
				$customer = Context::getContext()->customer;
				$customer_addresses = $customer->getAddresses($this->context->language->id);
			}
			
			$use_marketplace = false;
			if ($mp_group =  Configuration::get('CL_MP_GROUP'))
			{
				$use_marketplace = in_array($mp_group,$user_groups);
			}
			
			$product_kind = $this->current_config->product_kind;
			$display_mode = 'accordion';
			if (isset($this->current_config->options->display))
			{
				$display_mode = $this->current_config->options->display;
			}
			
			$this->assignCustomerName();
			$this->smarty->assign(array(
				'ui_mode' => Configuration::get('CL_FRONT_UI_MODE'),
				'api_mode' => Configuration::get('CL_API_MODE'),
				'product_name' => $param['product']['name'],

				'product_template' => "./" . $product_kind .'.tpl',
//				'product_uid' => 'clpid_'.uniqueid(),
				'product_key' => 'clariprint_product',
				'product' => json_decode($this->current_config->product_json),
				'clariprint_product_id' => $id_product,
				'customer_addresses' => $customer_addresses,
				'directs' => ClariprintServer::directs($id_customer),
				'marketplaces' => ClariprintServer::marketplaces($id_customer),
				
				'display_mode' => $display_mode,
//				'vernis_en_lignes' => $this->finishinghInlineFull(),
//				'vernis_en_reprise' => $this->finishingOfflineFull(),
//				'vernis_combines' => $this->list_vernis_combines,

				'harproof_cost' => Tools::displayPrice(Configuration::get('CL_HARD_PROOF'), null) . ' HT',

				'primary_colors' => array('cyan','magenta','yellow','black'),
				'sepcial_colors' => array('pms1','pms2','pms3','pms4'),
				'ajax_papers_selector_url' => '/index.php', //$this->context->link->getModuleLink('clariprint','paper'),
				'paper_kinds' => $this->paperKinds(),
				'processes' => $this->processes(),
				'user_groups' => $user_groups,
				'papers' => $this->getPapers(),
				'customer_addresses' => $this->getUserAddresses(),
				'deliveries' => $this->getDeliveriesOptions(),
				'delivery_default'  => Configuration::get('CL_DELIVERY_DEFAULT'),
				'no_sub_regions' => Configuration::get('CL_DELIVERIES_SUB'),
				
				'folds' => $this->getFolds(),
				'folderdie' => $this->getFolderDie(),
				'gilding_materials' => $this->gildingMaterials(),
				'wrapping' => $this->wrapping()));
			
			$this->registerSmartyPlugins();

			error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED);		
			//error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);		
//			echo $display_mode;
			
			$this->context->controller->registerJavascript('modules-clariprint', 'modules/'.$this->name.'/js/product.js',
															['position' => 'bottom', 'priority' => 0]);
			

//			$this->context->controller->addJS(($this->_path).'js/product.js', 'all');
			if ($product_kind <> "") {
				$this->smarty->error_reporting = E_ALL & ~E_NOTICE;
				$html .= $this->display(__FILE__, 'views/front/product.tpl');
			}
			return $html;
		} 
		return null;
	}
		
	public function hookDisplayFooterProduct($param)
	{

		return $this->displayFrontProduct($param);
	}

	public function hookDisplayProductTabContent($param) {
		return '';
		
		return $this->displayFrontProduct(Tools::getValue('id_product'));
	}
	public function hookDisplayRightColumnProduct($params)
	{
		return "";
		return $this->displayFrontProduct($param['id_product']);
	}

	static public function filterSimpleSizes($value='')
	{
		$res = array();
		$l = explode('\n',$value);
		foreach($l as $v) {
			$x = explode(":",$v);
			$res[$x] = end($x);
		}
		return $res;
	}

	public function productTypes()
	{
		return array('leaflet' => $this->l('Leaflet'),
					'folded' => $this->l('Folded leaflet'),
					'folder' => $this->l('Folder'),
					'envelope' => $this->l('Envelope'),
					'book' => $this->l('Book'),
					'bookform' => $this->l('Book Form'),
					'snapsetform' => $this->l('Snap-set Form'),
					'continuousform' => $this->l('Continuous Form'),
					'parametric' => $this->l('Parametric Design'));
	}
	
	/**
	 * Return customer addresses
	 *
	 * @param integer $id_lang Language ID
	 * @return array Addresses
	 */
	public function getCustomerAddresses($id_customer,$id_lang)
	{
		$share_order = (bool)Context::getContext()->shop->getGroup()->share_order;
		$cache_id = 'Customer::getAddresses'.(int)$this->id.'-'.(int)$id_lang.'-'.$share_order;
		if (!Cache::isStored($cache_id))
		{ 
			$sql = 'SELECT DISTINCT a.*, cl.`name` AS country, s.name AS state, s.iso_code AS state_iso
					FROM `'._DB_PREFIX_.'address` a
					LEFT JOIN `'._DB_PREFIX_.'country` c ON (a.`id_country` = c.`id_country`)
					LEFT JOIN `'._DB_PREFIX_.'country_lang` cl ON (c.`id_country` = cl.`id_country`)
					LEFT JOIN `'._DB_PREFIX_.'state` s ON (s.`id_state` = a.`id_state`)
					'.($share_order ? '' : Shop::addSqlAssociation('country', 'c')).' 
					WHERE `id_lang` = '.(int)$id_lang.' AND `id_customer` = '.(int)$this->id.' AND a.`deleted` = 0';

			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
			Cache::store($cache_id, $result);
		}
		return Cache::retrieve($cache_id);
	}
	
	public function setDeliveries($value='')
	{
		$lines = explode("\n",$value);
		$res = array();
		$this->allcountries();
		foreach($lines as $line)
		{
			$v = explode(':',$line);
			$iso = trim($v[0]);
			if ($iso == '') continue;
			$c = null;
			if ($c = $this->findCountryWithCode($iso)) {
				$label = $c->label;
			} else if ($c = $this->findZoneWithCode($iso)) {
				$c->parts = null;
			} else {
				 $c = new stdClass();
				 $c->iso = $iso;
				 $c->label = '?';
			}
			if (count($v) > 1 && $v[1] != '') $c->label = trim($v[1]);
			if (count($v) > 2)
			{
				$mode = trim($v[2]);
				if ($mode == '-') {
					$c->parts = null;
				}
			}
			$res[] = $c;
		}
		file_put_contents($this->countriespath(),json_encode($res));
	}
	
	public function hookDisplayCustomization($params)
	{
		$custom = $params['customization'];
//		echo "ici" . (int)$custom['id_customization'] . '<br>';
		if ($cc = ClariprintCustomization::objectForCustomization($custom['id_customization']))
		{
//			echo "la";
			$prj = json_decode($cc['project']);
			if (isset($prj->request->proofing))
			{
				if ($prj->request->proofing == 'hard')
					$prj->html .= '<br><b>BAT PAPIER</b>';

			}


			return $cc['name'] . $prj->html ;	
		}
		return null;
	}	
	
	
	public function hookDisplayAdminProductsExtra($cookie,$car=null,$altern=null)
	{
		return;
		$id_product = Tools::getValue('id_product');
		if (version_compare(_PS_VERSION_,'1.7.0.0') >= 0)
		{
			$id_product = $cookie['id_product'];
			return 'Clariprint';
		}
		
		if (!$id_product) {
			Context::getContext()->controller->warnings[] = $this->l('You must save this product before adding specific pricing');
			return $this->l('You must save this product before adding specific pricing');
		}
		
		$this->context->controller->addCSS(($this->_path).'clariprint.css', 'all');
		
		$id_product = Tools::getValue('id_product');
		
		$cfg  = Clariprint_Config::loadByIdProduct($id_product);
		$product_kind = "";
		if ($cfg) $product_kind = $cfg->product_kind;
		$this->smarty->assign(array(
						'ui_mode' => Configuration::get('CL_BACK_UI_MODE'),
						'presta_product' => new Product($id_product),
						'productkinds' => $this->productTypes(),
						'product_key' => "clariprint_product"));

		if ($prod = $cfg->product())
			$this->smarty->assign(array('product' => $prod));

		if ($product_kind)
			$product_template =  "./" . $product_kind .'.tpl';
		else $product_template = false;
		
		$customer_addresses = array();
		if (isset(Context::getContext()->customer) && Context::getContext()->customer->id)
		{
/*			foreach($customer->getAddresses($this->context->language->id) as $ad )
			{
				
					
			} */
			$customer_addresses = $customer->getAddresses($this->context->language->id);
		}
		
		
		$this->smarty->assign(array('vernis_en_lignes' => $this->finishinghInlineFull(),

									'vernis_en_reprise' => $this->finishingOfflineFull(),
									'vernis_combines' => $this->list_vernis_combines,
									'paper_kinds' => $this->paperKinds(),
									'processes' => $this->processes(),
									'papers' => $this->getPapers(),
									'product_template' => $product_template,
									'harproof_cost' => Tools::displayPrice(Configuration::get('CL_HARD_PROOF'), null) . ' HT',
//									'customer_addresses' => $this->getUserAddresses(),
									'deliveries' => $this->getDeliveriesOptions(),
									'gilding_materials' => $this->gildingMaterials(),
									'wrapping' => $this->wrapping(),
									'primaries' => $this->primaries(),
//									'direct_servers' => ClariprintServer::directs(),
									'groups' => Group::getGroups(Context::getContext()->language->id),
									'ajax_papers_selector_url' => '/index.php', // $this->context->link->getModuleLink('clariprint','paper')
									'ajax_papers_selector_url2' => $this->context->link->getModuleLink('clariprint','paper') ,
									'direct_servers' => ClariprintServer::directs(),
									
									'folds' => $this->getFolds(),
									'folderdie' => $this->getFolderDie(),
									'no_sub_regions' => Configuration::get('CL_DELIVERIES_SUB'),
									
									'marketplaces' => ClariprintServer::marketplaces()
								 								));

		$this->registerSmartyPlugins();

			error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);		

			$this->smarty->error_reporting = E_ALL & ~E_NOTICE;
			$html .= $this->display(__FILE__, 'views/admin/product.tpl');
			return $html;
	}
	
	
	public function in_array($x,$arr) {
		if ($arr) {
			in_array($x,$arr);
		}
		return false;
	}
	
	public function hookActionProductUpdate($params) {
		if (Tools::getValue('clariprint_update_config') == '1') {
			$clariprint_product_kind = Tools::getValue('clariprint_product_kind');
			$id_product = Tools::getValue('id_product');
			if ($clariprint_product_kind == '') {
				Clariprint_Config::deleteForProductId($id_product);
			} else {
				$cfg = Clariprint_Config::loadByIdProduct($id_product);
				$cfg->product_kind = $clariprint_product_kind;
				$cfg->product_id = $id_product;
			
				$product = Tools::getValue("clariprint_product");
				$product['kind'] = $clariprint_product_kind;
				$cfg->product_json = json_encode($product);
				
				if (isset($cfg->id))
				{	if (!$cfg->update()) throw new PrestaShopException('Cannot update clariprint setup'); }
				elseif(!$cfg->add()) { throw new PrestaShopException('Cannot create clariprint setup'); };
			}
		} else if (Tools::getValue('clariprint_update_config') == '-1') {
			Clariprint_Config::deleteForProductId(Tools::getValue('id_product'));
		}
	}

	public function hookDisplayAdminCustomers($params) {
		return null;
		
		$id_customer = (int)$params['id_customer'];
		$group = ClariprintCategory::getGroup($id_customer);
		
		$custo = new Customer($id_customer);
		$category = null;
		$group_id = 0;
		if ($group) ClariprintCategory::getGroupCategory($group->id);
		if ($group) $group_id = $group->id;
		
		$x = '<div style="width:100%"><h2>Clariprint setup</h2>';
		$x .= '<label for="clariprint_url">'.$this->l('Group:').'</label>';
		$groups = $custo->getGroups();
		
		
		$x .= '<select name="clariprint_group">';
		$x .= '<option value="0">-</option>';
				
		foreach($groups as $gid)
		{
			$g = new Group($gid); 
			$sel = ($group_id == $g->id ? ' selected' : '');
			$x .= sprintf('<option value="%d"%s>%s</option>',$g->id,$sel,$g->name[$this->context->language->id]);
		}
		$x .= '</select>';
		$x .= '<input type="submit" name="clariprint_create_group" value="'. $this->l("Create") .'" />';
		$x .= '<input type="submit" name="clariprint_update" value="'. $this->l("Update") .'" />';
		$x .= '<hr>';
		$x .= '</form>';  
		$x .= '</div>';

		return $x;
	}
	
	
	public function ajaxProcessUpdateClariprintClientConfig()
	{
		
/*		if ($this->tabAccess['edit'] === '1')
		{
			$note = Tools::htmlentitiesDecodeUTF8(Tools::getValue('note'));
			$customer = new Customer((int)Tools::getValue('id_customer'));
			if (!Validate::isLoadedObject($customer))
				die ('error:update');
			if (!empty($note) && !Validate::isCleanHtml($note))
				die ('error:validation');
			$customer->note = $note;
			if (!$customer->update())
				die ('error:update');
			die('ok');
		} */
	}
	
	public function hookActionOrderStatusPostUpdate($param) {
		$cart = $param['cart'];
		$status = $param['newOrderStatus'];
		$order = new Order($param['id_order']);
		$mailVars = array(
			'{order_id}' => 12,
				'{order_id}' => 12
			
		);
		if ($status->id == 2) {
			Mail::Send((int)$cart->id_lang, 'clariprint_workflow_fab','test',
				$mailVars,
				'x@pechoultres.com',
				$customer->firstname.' '.$customer->lastname,
				null, null, null, null, _PS_MAIL_DIR_, true,
				null);
			}
	}
	
	static function addTableColumn($table, $column, $column_attr = "VARCHAR( 255 ) NULL" ){
		$result = Db::getInstance()->executeS(sprintf('show columns from %s%s',_DB_PREFIX_,$table));
		foreach($result as $col) {
			if ($col['Field'] == $column) {
				return;
			}
		}
		Db::getInstance()->execute(sprintf('ALTER TABLE %s%s ADD COLUMN %s %s',_DB_PREFIX_,$table,$column, $column_attr));
	}
	
	public function runUpdates()
	{
		$up_path = _PS_MODULE_DIR_.$this->name.'/updates/';

		$files = glob($up_path . '[0-9]*.php');
		foreach($files as $f) {
			$fname = basename($f);
			$val = (int)$fname;
			try {
				include($f);
				if ($errm = Db::getInstance()->getMsgError()) {
					$this->addWarn("Error: $errm");
				}
				
				$this->addInfo("runnning $fname : ok<br>");
				Configuration::updateValue('CL_DB_VERSIONS', $val);
				
			} catch (Exception $e) {
				$this->addError('Error on '. $fname . ' : '. $e->getMessage());
				return;
			}
		}
		$this->addInfo('Current clariprint db version : '.$val) ;
	}
	
	
	
	
	
	
	public function getUserAddresses()
	{
		if (!Context::getContext()->customer->isLogged()) return array();

		$id_customer = Context::getContext()->customer->id;
		$sql = 'SELECT DISTINCT a.*, s.name AS state, c.iso_code AS country_iso, s.iso_code AS state_iso
					FROM `'._DB_PREFIX_.'address` a
					LEFT JOIN `'._DB_PREFIX_.'country` c ON (a.`id_country` = c.`id_country`)
					LEFT JOIN `'._DB_PREFIX_.'state` s ON (s.`id_state` = a.`id_state`)
					WHERE a.active = 1  AND `id_customer` = '.(int)$id_customer.' AND a.`deleted` = 0';


		$res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		
		
		return $res;
	}


	public function hookDisplayAdminOrder($params='')
	{
		$prods  = array();
		$order = new Order($params['id_order']);
		if ($order->id) {
			$products = $order->getProducts();
			foreach($products as $p)
			{
				if ($pid = ClariprintProductProcess::idForProduct($p['product_id'])) {
					$prods[$pid] = $p;
				}
			}
		}
		if (count($prods) > 0) {
			$this->smarty->assign(array(
//				'products' => $products,
 				'products' => $prods,
				'download_url' => $this->context->link->getAdminLink('AdminClariprintProducts', true).'&action=file&ajax=1',
				'download_url2' => $this->context->link->getModuleLink($this->name, 'pdfprocess'),
				'download_url3' => $this->context->link->getAdminLink('AdminClariprintDownload')));
			return $this->display(__FILE__, 'views/hook/displayadminorder.tpl');
		}
		return null;
	}
	
	public function getPdfTemplate($name) {
		$dirs = array(_PS_THEME_DIR_,_PS_THEME_DIR_,__DIR__);
		foreach($dirs as $d)
		{
			if (is_file($d.'/pdf/'.$name.'.tpl')) return $d.'/pdf/'.$name.'.tpl';
		}	
		return null;
		
	}
	
	public function hookDisplayPdfInvoiceAppendices($params)
	{
/*
		$shop_name = Configuration::get('PS_SHOP_NAME', null, null, (int)$this->order->id_shop);
		$path_logo = $this->getLogo();

		$width = 0;
		$height = 0;
		if (!empty($path_logo))
			list($width, $height) = getimagesize($path_logo); */

//		$this->smarty->assign($params);
		return "ici";
		return $this->getPdfTemplate('invoice-appendices');
		return $this->smarty->fetch($this->getPdfTemplate('invoice-appendices'));
	}

	public function hookDisplayPDFInvoice($params)
	{
	}
	
	
	/* Account Manager */
	
	public function accountManagerFor($id_customer) {
		return new Employee(Db::getInstance()->getValue(sprintf('SELECT account_manager FROM %scustomer WHERE id_customer=%d',
		_DB_PREFIX_,
		$id_customer)));
	}
	
	public function hookDisplayCustomerAccount($params) {
		if (Context::getContext()->customer->isLogged() === true)
		{
			// $params['id_customer']
			$this->smarty->assign(array(
				'account_manager' => self::accountManagerFor(Context::getContext()->customer->id),
				'id_customer' => Context::getContext()->customer->id,
				'employees' => Employee::getEmployees()));
				return $this->display(__FILE__, 'views/hook/displaycustomeraccount.tpl');
		} 
	}
	
	
	
	
	public static function deleteTagsForProduct($id_product)
	{
		return Db::getInstance()->execute(sprintf("DELETE FROM `%sproduct_tag` WHERE `id_product` = %d AND
				 					addby = 'clariprint'",_DB_PREFIX_,$id_product));
	}

	var $parametriclayouts = null;
	public function parametricLayouts()
	{
		if (!$this->parametriclayouts)
			$this->parametriclayouts = ClariprintConnect::getLayouts();
		return $this->parametriclayouts;

		
		$query = new DbQuery();
		$query->select('uuid,name');
		$query->from('clariprint_asset');
		$query->where("kind IN ('LAYOUT', 'SCENE')");
		$query->orderby('name');
		return Db::getInstance()->executeS($query);
		
	}
	
	
	/* PRESTASHOP 1.7 */
	public function hookDisplayAdminProductsMainStepLeftColumnBottom($params)
	{
		$this->context->controller->addJS('https://lrdp.clariprint.com/js/gl-matrix-min.js',false);
		$this->context->controller->addJS('https://lrdp.clariprint.com/js/webgl-debug.js',false);
		$this->context->controller->addJS('https://lrdp.clariprint.com/js/xl_3D_sampler.js',false);


		$id_product = $params['id_product'];
		
		if (!$id_product) {
			Context::getContext()->controller->warnings[] = $this->l('You must save this product before adding specific pricing');
			return $this->display(__FILE__, 'views/admin/notsaved.tpl');
		}
		
		$this->context->controller->addCSS(($this->_path).'clariprint.css', 'all');
				
		$cfg  = Clariprint_Config::loadByIdProduct($id_product);
		$product_kind = "";
		if ($cfg) $product_kind = $cfg->product_kind;
		$this->smarty->assign(array(
						'ui_mode' => Configuration::get('CL_BACK_UI_MODE'),
						'presta_product' => new Product($id_product),
						'productkinds' => $this->productTypes(),
						'product_key' => "clariprint_product"));

		if ($prod = $cfg->product())
			$this->smarty->assign(array('product' => $prod));

		if ($product_kind)
			$product_template =  "./" . $product_kind .'.tpl';
		else $product_template = false;
		
		$customer_addresses = array();
		if (isset(Context::getContext()->customer) && Context::getContext()->customer->id)
		{
/*			foreach($customer->getAddresses($this->context->language->id) as $ad )
			{
				
					
			} */
			$customer_addresses = $customer->getAddresses($this->context->language->id);
		}
		
		
		$this->smarty->assign(array('vernis_en_lignes' => $this->finishinghInlineFull(),
									'vernis_en_reprise' => $this->finishingOfflineFull(),
									'vernis_combines' => $this->list_vernis_combines,
									'customerName' => 'admin',
									'paper_kinds' => $this->paperKinds(),
									'processes' => $this->processes(),
									'papers' => $this->getPapers(),
									'product_template' => $product_template,
									'harproof_cost' => Tools::displayPrice(Configuration::get('CL_HARD_PROOF'), null) . ' HT',
//									'customer_addresses' => $this->getUserAddresses(),
									'deliveries' => $this->getDeliveriesOptions(),
									'gilding_materials' => $this->gildingMaterials(),
									'wrapping' => $this->wrapping(),
									'primaries' => $this->primaries(),
//									'direct_servers' => ClariprintServer::directs(),
									'groups' => Group::getGroups(Context::getContext()->language->id),
									'ajax_papers_selector_url' => '/index.php', // $this->context->link->getModuleLink('clariprint','paper')
									'ajax_papers_selector_url2' => $this->context->link->getModuleLink('clariprint','paper') ,
									'direct_servers' => ClariprintServer::directs(),
									'layouts' => $this->parametricLayouts(),
									
									'folds' => $this->getFolds(),
									'folderdie' => $this->getFolderDie(),
									'no_sub_regions' => Configuration::get('CL_DELIVERIES_SUB'),
									
									'marketplaces' => ClariprintServer::marketplaces()
								 								));

			$this->registerSmartyPlugins();

			error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);		

			$this->smarty->error_reporting = E_ALL & ~E_NOTICE;
			$html .= $this->display(__FILE__, 'views/admin/product.tpl');
			return $html;
	}
	
	public function displayCMS($params,&$smarty)
	{
		$cms_id = $params['cms'];
		$sql = new DbQuery();
		$lang = Context::getContext()->language->id;
		$cache_id =  'CMS::clariprintGetCMS2'.$cms_id.'l'.$lang;
		if (true /* !Cache::isStored($cache_id)*/ ) {
			$query = new DbQuery();
			$query->select('pc.content');
			$query->from('cms_lang', 'pc');
			$query->where('pc.link_rewrite = \''. pSQL($cms_id).'\'');
			$query->where('pc.id_lang = '.(int) $lang);
			$content = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
			
			if ($content) {
				$this->smarty->assign(array('content' => $content));
				$html = $this->display(__FILE__, 'views/front/help.tpl');
				Cache::store($cache_id, $html);
				return $html;
			}
			Cache::store($cache_id, $content);
			return null;
		} 
		return Cache::retrieve($cache_id);
	}

	public function getParametricTemplate($params,&$smarty)
	{
		$kind = $params['kind'];
		$kmap = [
				"ClariprintLeaflet" => "leaflet.tpl",
				"ClariprintFolder" => "folder.tpl",
				"ClariprintFolded" => "folded.tpl",
				"ClariprintEnveloppe" => "enveloppe.tpl",
				"ClariprintBook" => "book.tpl",
				"ClariprintFormDelivery" => "delivery.tpl",
				"ClariprintFormWrapping" => "wrapping.tpl",
				"ClariprintFormQuantity" => "quantities.tpl",
				"ClariprintFormQuantity" => "quantities.tpl",
				"ClariprintFormModels" => "models.tpl",
				"ClariprintFormVariables" => "variables.tpl",
				"ClariprintFormMaterial" => "papers.tpl",
				"ClariprintFormFinish" => "finishing.tpl",
				"ClariprintFormPrint" => "colors.tpl",
				"ClariprintFormCutAndHole" => "makeready.tpl",
				"ClariprintFormDimension" => "dimensions.tpl"
				];
		$smarty->assign("parametric_tpl", (isset($kmap[$kind]) ? $kmap[$kind] : null));
	}
	public function setupParametricConfig($params,&$smarty)
	{
		$product = &$params['product'];
		if (!isset($product->config) && isset($product->layout))
		{
			$uuid = $product->layout;
			$query = new DbQuery();
			$query->select('json');
			$query->from('clariprint_asset');
			$query->where("uuid = '$uuid'");
			$params['product']->config = json_decode(Db::getInstance()->getValue($query),true);
//			$params['product']->config = Db::getInstance()->getValue($query);
		} else {

		}
		return null;
	}
	
	function hookClariprintGetCustomizationInfos($params)
	{
		$res = array();
		if ($cc = ClariprintCustomization::objectForCustomization($params['id_customization']))
		{
			$prj = json_decode($cc['project']);
			$txt = $prj->html;
			if (isset($prj->request->proofing))
			{
				if ($prj->request->proofing == 'hard')
					$txt .= 'BAT PAPIER';

			}

			$res[] = array('type' => 'txt',
							'content'  => $txt);
			$res[] = array(	'type' => 'file',
							'content' => base64_decode($prj->quote_process), 
			 								'name' => $cc['name'] . '_'.(int)$params['id_customization'].'.pdf',
											'mime' => 'application/pdf');
			
		}
		return $res;
	}
}

