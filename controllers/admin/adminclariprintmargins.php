<?php
	
define('MAX_LINE_SIZE', 0);


class AdminClariprintMarginsController extends ModuleAdminController
{

	public function __construct()
	{
        $this->bootstrap = true;
		$this->table = 'clariprint_margins';
		$this->identifier = 'id_margin';
		$this->className = 'ClariprintMargin';
		$this->lang = false;
		$this->addRowAction('edit'); 
		$this->addRowAction('view');
		$this->addRowAction('delete');
		$this->addRowAction('select');

		$this->allow_export = true;
		$this->deleted = false;
		$this->context = Context::getContext();
		parent::__construct();

		$this->_join = '';
		$this->_orderBy = 'validity_stop';
		$this->_orderWay = 'DESC';
		$this->_where = '';
		if (!isset($id_lang))
			$id_lang = Context::getContext()->language->id;

		
		$this->_select = sprintf('tshop.name as shop_name, tgroup.name as group_name ');
		$this->_join = '
			LEFT JOIN `'._DB_PREFIX_.'group_lang` tgroup ON (tgroup.`id_group` = a.`id_group` AND  tgroup.`id_lang` = '.(int)$id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'shop` tshop ON (tshop.`id_shop` = a.`id_shop`)';

		
		$groups = Group::getGroups($this->default_form_language, true);
		$this->fields_list = array(
			'shop_name' => array(
				'title' => $this->l('Shop'),
				'align' => 'left',
				'width' => 65,
				'type' => 'shop'
			),
			'group_name' => array(
				'title' => $this->l('Group'),
				'align' => 'left',
				'width' => 65,
//				'filter_key' => 'group',
//				'tmpTableFilter' => true
//				'type' => 'select',
//				'list' => $groups,
//				'filter_key' => 'name'
			),
			
			'paper' => array(
				'title' => $this->l('Paper'),
				'align' => 'left',
				'type' => 'percent',
				'width' => 65
			),
			'print' => array(
				'title' => $this->l('Printing'),
				'align' => 'left',
				'type' => 'percent',
				'width' => 65
			),
			'makeready' => array(
				'title' => $this->l('Makeready'),
				'align' => 'left',
				'type' => 'percent',
				'width' => 65
			),
			'delivery' => array(
				'title' => $this->l('Delivery'),
				'align' => 'left',
				'type' => 'percent',
				'width' => 65
			),
			'validity_start' => array(
				'title' => $this->l('Validity start'),
				'align' => 'right',
				'width' => 65,
				'type' => 'date'
			),
			'validity_stop' => array(
				'title' => $this->l('Validity end'),
				'align' => 'right',
				'type' => 'date',
				'width' => 65
			)

		); 

		$this->fields_options = array(
			'margins' => array(
				'title' =>	$this->l('Margins'),
				'image' => '../img/admin/exchangesrate.gif',
				'icon' =>	'tab-preferences',
//				'submit' => array(
//					
//				),
				'submit' => array('title' => $this->trans('Save', array(), 'Admin.Actions')),
				'fields' =>	array(
					'CL_MARGIN_PAPER' => array(
						'title' => $this->l('Paper'),
						'desc' => $this->l('Global margin on Papers'),
						'validation' => 'isFloat',
						'cast' => 'floatval',
						'suffix' => ' %',
						'type' => 'text'
					),
					'CL_MARGIN_PRINT' => array(
						'title' => $this->l('Printing'),
						'desc' => $this->l('Global margin on printing'),
						'validation' => 'isFloat',
						'cast' => 'floatval',
						'type' => 'text',
						'suffix' => ' %'
					),
					'CL_MARGIN_MAKEREADY' => array(
						'title' => $this->l('Makeready'),
						'desc' => $this->l('Global margin on makeready'),
						'validation' => 'isFloat',
						'cast' => 'floatval',
						'type' => 'text',
						'suffix' => ' %'
					),

					'CL_MARGIN_PACKAGING' => array(
						'title' => $this->l('Packaging'),
						'desc' => $this->l('Global margin on packaging'),
						'validation' => 'isFloat',
						'cast' => 'floatval',
						'type' => 'text',
						'suffix' => ' %'
					),
					'CL_MARGIN_DELIVERY' => array(
						'title' => $this->l('Delivery'),
						'desc' => $this->l('Global margin on delivery'),
						'validation' => 'isFloat',
						'cast' => 'floatval',
						'type' => 'text',
						'suffix' => ' %'
					))));

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
	/*
	
	public function initProcess()
	{
		parent::initProcess();
	}
	

	public function initToolbar()
	{

		$res = parent::initToolbar();
		return $res;
		
	}
	*/
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
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
        $this->addJqueryPlugin('effects.core');
        $this->addJqueryPlugin('fancybox');
		$this->addJqueryPlugin('datepicker');
		
		// if ($this->tabAccess['edit'] == 1 && $this->display == 'view')
//		{
//			$this->addJS(_PS_JS_DIR_.'admin_order.js');
//			$this->addJS(_PS_JS_DIR_.'tools.js'); 
//			$this->addJqueryPlugin('autocomplete');
//		}
		
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
		
//		echo "Responce from DB: \n";
//		print_r($result);
		return $result;
	}

	
	public function ensureUtf8($x) {
		if ($this->is_utf8($x)) return $x;
		return iconv('ISO-8859-1','UTF-8',$x);
	}
	

	public function renderView()
	{
		return parent::renderView();
	}


	public function renderForm()
	{
		$groups = Group::getGroups($this->default_form_language, true);
		
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Margins'),
				'image' => '../img/t/AdminAttachments.gif'
			),
			'input' => array(
				array(
					'type' => 'select',
					'label' => $this->l('Shop:'),
					'name' => 'id_shop',
					'options' => array(
						'query' => Shop::getShops(true),
						'id' => 'id_shop',
						'name' => 'name'
					)
				),
				array(
					'type' => 'select',
					'label' => $this->l('Customer group:'),
					'name' => 'id_group',
					'options' => array(
						'query' => $groups,
						'id' => 'id_group',
						'name' => 'name'
					),
					'hint' => $this->l('The group will be as applied by default.'),
					'desc' => $this->l('Apply the discount\'s price of this group.')
				),
			
				array(
					'type' => 'text',
					'label' => $this->l('Paper:'),
					'name' => 'paper',
					'size' => 6,
					'required' => true,
					'lang' => false,
				),

				array(
					'type' => 'text',
					'label' => $this->l('Printing:'),
					'name' => 'print',
					'size' => 6,
					'required' => true,
					'lang' => false,
				),
				
				array(
					'type' => 'text',
					'label' => $this->l('Makeready:'),
					'name' => 'makeready',
					'size' => 6,
					'required' => true,
					'lang' => false,
				),
				
				array(
					'type' => 'text',
					'label' => $this->l('Delivery:'),
					'name' => 'delivery',
					'size' => 6,
					'required' => true,
					'lang' => false,
				),
				
				array(
					'type' => 'date',
					'label' => $this->l('Validity start:'),
					'name' => 'validity_start',
					'size' => 6,
					'required' => true,
					'lang' => false,
					'hint' => $this->trans('Format: 2011-12-31 (inclusive).', array(), 'Admin.Orderscustomers.Help')
				),
				
				array(
					'type' => 'date',
					'label' => $this->l('Validity end:'),
					'name' => 'validity_stop',
					'size' => 6,
					'required' => true,
					'hint' => $this->trans('Format: 2011-12-31 (inclusive).', array(), 'Admin.Orderscustomers.Help'),
					'lang' => false,
				)
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right',
				'id' => 'submitSave',
				'icon' => 'process-icon-download-alt'
			)
		);
		$this->show_toolbar = false;

		
		$this->fields_value = array(
			'validity_start' => date('Y-m-d'),
			'validity_stop' => date('Y-m-d')
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
	
	
}



