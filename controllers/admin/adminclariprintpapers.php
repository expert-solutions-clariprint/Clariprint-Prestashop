<?php
	
define('MAX_LINE_SIZE', 0);


class AdminClariprintPapersController extends ModuleAdminController
{

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'clariprint_paper';
		$this->identifier = 'id_paper';
		$this->className = 'ClariprintPaper';
		$this->lang = false;
//		$this->addRowAction('edit'); 
//		$this->addRowAction('view');
//		$this->addRowAction('delete');
//		$this->addRowAction('select');

		$this->allow_export = true;
		$this->deleted = false;
		$this->context = Context::getContext();

		$this->_join = '';
		$this->_orderBy = 'id_paper';
		$this->_orderWay = 'DESC';
		$this->_where = '';
		parent::__construct();

		$this->fields_list = array(
			'quality' => array(
				'title' => $this->l('Quality'),
				'align' => 'left',
				'width' => 65
			),
			'brand' => array(
				'title' => $this->l('Brand'),
				'align' => 'left'
			),
			'color' => array(
				'title' => $this->l('Color'),
				'align' => 'left',
				'width' => 65,
					'callback' => 'printColor'
			),
			'weight' => array(
				'title' => $this->l('Weight'),
				'align' => 'right',
				'width' => 65
			),
			'thickness' => array(
				'title' => $this->l('Thickness'),
				'align' => 'right',
				'width' => 65
			),
			'pefc' => array(
				'type' => 'bool',
				'title' => $this->l('PEFC'),
				'align' => 'center',
				'icon' => array(0 => 'bullet_red.png', 1 => 'bullet_green.png')
				),
			'recycled' => array(
				'type' => 'bool',
				'title' => $this->l('Recyclé'),
				'align' => 'center',
				'icon' => array(0 => 'bullet_red.png', 1 => 'bullet_green.png')
				),
			'iso' => array(
				'title' => $this->l('ISO'),
				'align' => 'center'
			),
/*			'process_n' => array(
				'title' => $this->l('Digital'),
				'align' => 'center',
				'icon' => array(0 => 'bullet_red.png', 1 => 'bullet_green.png')
			),
			'process_of' => array(
				'title' => $this->l('Offset'),
				'align' => 'center',	
				'icon' => array(0 => 'bullet_red.png', 1 => 'bullet_green.png')
			),
			*/
			'process_ofuv' => array(
				'title' => $this->l('Offset UV'),
				'align' => 'center',
				'icon' => array(0 => 'bullet_red.png', 1 => 'bullet_green.png')
			)
			
		); 
	}
	
	public function printColor($value='')
	{
		return $value;
	}
	
/*	public function init() {
		if (Tools::isSubmit('updateattribute'))
			$this->display = 'editAttributes';
		else if (Tools::isSubmit('submitAddattribute'))
			$this->display = 'editAttributes';
		else if (Tools::isSubmit('submitAddattribute_group'))
			$this->display = 'add';
	} */

	public function processImport()
	{
		
		
	}
	
	
	public function initProcess()
	{
		parent::initProcess();
	}
	

	public function initToolbar()
	{

		$res = parent::initToolbar();
		if (isset($this->toolbar_btn['new']))
			unset($this->toolbar_btn['new']);

		$this->toolbar_btn['import'] = array(
			'href' => $this->context->link->getAdminLink('AdminClariprintPapers', true).'&add'.$this->table.'=1',
			'desc' => $this->l('Import from file')
		);
		$this->toolbar_btn['sync-papers'] = array(
			'href' => $this->context->link->getAdminLink('AdminClariprintPapers', true).'&sync'.$this->table.'=1',
			'desc' => $this->l('Sync'),
			'class' => 'icon-server' //'icon-code-fork'
		);
		$this->toolbar_btn['localize'] = array(
			'short' => 'Localize',
			'href' => $this->context->link->getAdminLink('AdminClariprintPapers', true).'&localize'.$this->table.'=1',
			'desc' => $this->l('Generate localization files'),
			'class' => 'icon-language'
		);

		$this->toolbar_btn['validate'] = array(
			'short' => 'Validate',
			'href' => self::$currentIndex.'&amp;validate&amp;token='.$this->token,
			'desc' => $this->l('Validate selected'),
			'class' => 'process-icon-save'
		);
		return $res;
		
	}
	/**
	* Get a detailed order list of an id_order
	* @param int $id_order
	* @return array
	*/
/*	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
//		d("getList");
//	d(Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'clariprint_paper`'));
		return Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'clariprint_paper`');
	}*/

	public function setMedia()
	{
		parent::setMedia();
		$this->addJqueryUI('ui.datepicker');
		/*
		if ($this->tabAccess['edit'] == 1 && $this->display == 'view')
		{
			$this->addJS(_PS_JS_DIR_.'admin_order.js');
			$this->addJS(_PS_JS_DIR_.'tools.js'); 
			$this->addJqueryPlugin('autocomplete');
		} */
		$this->addJS($this->module->getPathUri() . 'js/clariprintorder-admin.js');
	}

	// Returns true if $string is valid UTF-8 and false otherwise.
	function is_utf8($string) {
	
		// From http://w3.org/International/questions/qa-forms-utf-8.html
		return preg_match('%^(?:
				[\x09\x0A\x0D\x20-\x7E]				# ASCII
			| [\xC2-\xDF][\x80-\xBF]				# non-overlong 2-byte
			|  \xE0[\xA0-\xBF][\x80-\xBF]		# excluding overlongs
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			|  \xED[\x80-\x9F][\x80-\xBF]		# excluding surrogates
			|  \xF0[\x90-\xBF][\x80-\xBF]{2}	# planes 1-3
			| [\xF1-\xF3][\x80-\xBF]{3}			# planes 4-15
			|  \xF4[\x80-\x8F][\x80-\xBF]{2}	# plane 16
		)*$%xs', $string);
	}
	
	var $adb_handle = null;
	var $adb_option_defaults = array(CURLOPT_HEADER => false, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 2);
	
	
	public function httpRest($method,$url,$querry=NULL,$json=NULL,$options=NULL){
	//	global $adb_url,,$adb_option_defaults;
		// Connect 
		if($this->adb_handle == null) $this->adb_handle = curl_init();

		// Compose querry
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => $method // GET POST PUT PATCH DELETE HEAD OPTIONS 
	//		CURLOPT_POSTFIELDS => $json,
			); 
		curl_setopt_array($this->adb_handle,($options + $this->adb_option_defaults)); 
		// send request and wait for responce
	//	$response =  json_decode(curl_exec($this->adb_handle),true);
		$result = null;
		if ($response =  curl_exec($this->adb_handle))
		{
			$result = array();
			$header_size = curl_getinfo($this->adb_handle,CURLINFO_HEADER_SIZE);
			$result['header'] = substr($response, 0, $header_size);
			$result['body'] = substr( $response, $header_size );
		}
		
		echo "Responce from DB: \n";
		print_r($result);
		return $result;
	}

	
	public function ensureUtf8($x) {
		if ($this->is_utf8($x)) return $x;
		return iconv('ISO-8859-1','UTF-8',$x);
	}
	
	public function createPaperLocalizationFile()
	{
		$path = $this->module->getLocalPath().'/views/paper_qualities.tpl';
		$f = fopen($path,"w");
		foreach(ClariprintPaper::qualities() as $i)
		{
			fwrite($f, "{l s='".addslashes($i['value'])."' mod='clariprint'}\n");
		}
		fclose($f);

		$path = $this->module->getLocalPath().'/views/paper_colors.tpl';
		$f = fopen($path,"w");
		foreach(ClariprintPaper::colors() as $i)
		{
			fwrite($f, "{l s='".addslashes($i['value'])."' mod='clariprint'}\n");
		}
		fclose($f);
		$path = $this->module->getLocalPath().'/views/paper_brands.tpl';
		$f = fopen($path,"w");
		foreach(ClariprintPaper::brands() as $i)
		{
			fwrite($f, "{l s='".addslashes($i['value'])."' mod='clariprint'}\n");
		}
		fclose($f);
	}
	
	public function postProcess()
	{
		if (Tools::isSubmit('sync'.$this->table))
		{
/*			$request = new HttpRequest(Configuration::get('CL_SERVER_URL') . '/optimproject/json.wcl');
			$request->setMethod(HTTP_METH_POST);
			$request->setOptions(array(
				'timeout' => 1000,
				'connecttimeout' => 200,
				'dns_cache_timeout' => 200));
			$request->setPostFields(array('login' => Configuration::get('CL_SERVER_LOGIN'),'password' => Configuration::get('CL_SERVER_PASSWORD'),'action' => 'PaperCatalog'));
			$request->send();
			$resp =	json_decode($request->getResponseBody()); */
			$this->module->log("Start sync");

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,Configuration::get('CL_PA_SERVER_URL') . '/optimproject/json.wcl');


			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 2000);
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type" => "application/x-www-form-urlencoded"));
			// curl_setopt($ch, CURLOPT_USERAGENT, $defined_vars['HTTP_USER_AGENT']);
			// Apply the XML to our curl call
			curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
							array('login' => Configuration::get('CL_PA_SERVER_LOGIN'),
									'password' => Configuration::get('CL_PA_SERVER_PASSWORD'),
									'action' => 'PaperCatalog'));


			//print_r(array('login' => Configuration::get('CL_PA_SERVER_LOGIN'),
			//						'password' => Configuration::get('CL_PA_SERVER_PASSWORD'),
			//						'action' => 'PaperCatalog'));
			$res = curl_exec($ch);			
			$resp = json_decode($res);
			$this->module->log("process papers response");

			if ($resp)
			{
				if($resp->success > 0)
				{
					if ($resp->content == 'PaperCatalog' && is_array($resp->result))
					{
						file_put_contents(_PS_UPLOAD_DIR_."/paper.json", json_encode($resp->result,JSON_PRETTY_PRINT));

						$id_shop = (int)Context::getContext()->shop->id;
						$res = array();
						foreach($resp->result as $line)
						{
							$nelem = count($line);
							$x  = new ClariprintPaper();
							$x->id_shop = $id_shop;
							/** @var integer */
							$x->quality = (string)$this->ensureUtf8(array_shift($line));
							$x->brand = (string)$this->ensureUtf8(array_shift($line));
							$x->color = (string)$this->ensureUtf8(array_shift($line));
							$x->weight = (float)array_shift($line);
							if (count($line) > 28)
								$x->thickness = (float)array_shift($line);
							}
							else
							{ 
								$x->thickness = $x->weight;
							}

							$x->recycled = (bool)array_shift($line);
							$x->fsc = (bool)array_shift($line);
							$x->fsc_mixed = (bool)array_shift($line);
							$x->fsc_recycled = (bool)array_shift($line);
							$x->pefc = (bool)array_shift($line);
							$x->pefc_70 = (bool)array_shift($line);
							$x->pefc_recycled = (bool)array_shift($line);
							$x->ecolabel = (bool)array_shift($line);
							$x->blue_angel = (bool)array_shift($line);
							$x->nordic_swan = (bool)array_shift($line);
							$x->apur = (bool)array_shift($line);
							$x->paper_by_nature = (bool)array_shift($line);
							$m1 = (bool)array_shift($line);
							$x->process_of = (bool)array_shift($line);
							$x->process_or = (bool)array_shift($line);
							$x->process_oc = (bool)array_shift($line);
							$x->process_n = (bool)array_shift($line);
							$x->process_h = (bool)array_shift($line);
							$x->process_f = (bool)array_shift($line);
							$x->process_s = (bool)array_shift($line);
							$x->reseller = (bool)array_shift($line);
							$x->factory_stock = (bool)array_shift($line);
							$x->factory = (bool)array_shift($line);
							$x->iso = (string)$this->ensureUtf8(array_shift($line));

							/* en attendant de passer le champs offset UV */
							if 	(!$x->process_of &&
								!$x->process_or &&
								!$x->process_oc &&
								!$x->process_n &&
								!$x->process_h &&
								!$x->process_f)
								$x->process_ofuv = 1;
							$x->add();
							$res[] = $x->id;							
						}
						if (count($res) > 0) {
							ClariprintPaper::cleanup($id_shop,$res);
						}
						
						
					}
					
				}
			}
			
		}
		if (Tools::isSubmit('localize'.$this->table))
		{
			self::createPaperLocalizationFile();
		}
		if (Tools::isSubmit('submitAdd'.$this->table))
		{
			if (isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name']))
			{
				if ($file = fopen($_FILES['file']['tmp_name'],'r'))
				{
					$id_shop = (int)Context::getContext()->shop->id;
					
					$sep  = Tools::getValue('separator');
					if ($sep == 'auto') {
						$l  = fgets($file);
						if (substr_count($l,',') > substr_count($l,';')) $sep = ','; else $sep = ';';
						rewind($file);
					}
					$res = array();
					while ($line = fgetcsv($file, MAX_LINE_SIZE, $sep)) {
						$ll = implode(' ',$line);
						$x  = new ClariprintPaper();
						$x->id_shop = $id_shop;
		
						/** @var integer */
						$x->quality = (string)$this->ensureUtf8(array_shift($line));
						$x->brand = (string)$this->ensureUtf8(array_shift($line));
						$x->color = (string)$this->ensureUtf8(array_shift($line));
						$x->weight = (float)array_shift($line);
						$x->recycled = (bool)array_shift($line);
						$x->fsc = (bool)array_shift($line);
						$x->fsc_mixed = (bool)array_shift($line);
						$x->fsc_recycled = (bool)array_shift($line);
						$x->pefc = (bool)array_shift($line);
						$x->pefc_70 = (bool)array_shift($line);
						$x->pefc_recycled = (bool)array_shift($line);
						$x->ecolabel = (bool)array_shift($line);
						$x->blue_angel = (bool)array_shift($line);
						$x->nordic_swan = (bool)array_shift($line);
						$x->apur = (bool)array_shift($line);
						$x->paper_by_nature = (bool)array_shift($line);
						$x->process_of = (bool)array_shift($line);
						$x->process_or = (bool)array_shift($line);
						$x->process_oc = (bool)array_shift($line);
						$x->process_n = (bool)array_shift($line);
						$x->process_h = (bool)array_shift($line);
						$x->process_f = (bool)array_shift($line);
						$x->process_s = (bool)array_shift($line);
						$x->reseller = (bool)array_shift($line);
						$x->factory_stock = (bool)array_shift($line);
						$x->factory = (bool)array_shift($line);
						$x->add();
						$res[] = $x->id;
					}
					if (count($res) > 0) {
						ClariprintPaper::cleanup($id_shop,$res);
					}
				}
			}
		}
		parent::postProcess();
	}

	public function renderView()
	{
		d("renderView");
		return parent::renderView();
	}


	public function renderForm()
	{
		
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Import Papers'),
				'image' => '../img/t/AdminAttachments.gif'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Dealer:'),
					'name' => 'name',
					'size' => 33,
					'required' => true,
					'lang' => true,
				),
				array(
					'type' => 'select',
					'size' => 1,
					'label' => $this->l('Separator:'),
					'maxlength' => 1,
					'name' => 'separator',
					'default' => ';',
					'desc' => $this->l('Define field speparator'),
					'options' => array(
						'query' => array(array('key' => 'auto', 'value' => 'Auto'), array( 'key' => ',', 'value' => ','), array('key' => ';', 'value' => ';')),
						'id' => 'key',
						'name' => 'value'
					)
				),
				array(
					'type' => 'select',
//					'list' => array(array('key' => 'auto', 'value' => 'Auto'), array( 'key' => 'utf8', 'value' => 'UTF-8'), array('key' => 'latin9', 'value' => 'latin')),
					'options' => array(
						'query' => array(array('key' => 'auto', 'value' => 'Auto'), array( 'key' => 'utf8', 'value' => 'UTF-8'), array('key' => 'latin9', 'value' => 'latin')),
						'id' => 'key',
						'name' => 'value'
					),
					'label' => $this->l('Charset:'),
					'name' => 'separator',
					'desc' => $this->l('File charset')
				),
				array(
					'type' => 'file',
					'label' => $this->l('File:'),
					'name' => 'file',
					'desc' => $this->l('Upload a file from your computer.')
				),
			),
			'submit' => array(
				'title' => $this->l('Save	'),
				'class' => 'button'
			)
		);

		return parent::renderForm();
	}

	
	

	protected function applyDiscountOnInvoice($order_invoice, $value_tax_incl, $value_tax_excl)
	{
		// Update OrderInvoice
		$order_invoice->total_discount_tax_incl += $value_tax_incl;
		$order_invoice->total_discount_tax_excl += $value_tax_excl;
		$order_invoice->total_paid_tax_incl -= $value_tax_incl;
		$order_invoice->total_paid_tax_excl -= $value_tax_excl;
		$order_invoice->update();
	}
	
	public function displayAjaxDeliveryItem()
	{
		$this->context->smarty->assign(
			array(	'deliveries' => $this->getDeliveriesOptions(),
					'k' => uniqid('d'),
					'product_key' => Tools::getValue('product_key')));
		return $this->context->smarty->display(_PS_MODULE_DIR_.'/clariprint/views/front/delivery_item.tpl');
	}
	
}



