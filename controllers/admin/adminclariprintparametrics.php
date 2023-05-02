<?php

if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__) . '/../../classes/clariprintasset.php');

/*
function in_array_silent($x,$arr=null)
{
	if (!isset($x)) return false;
	if (!isset($arr)) return false;
	if (!is_array($arr)) return false;
	return @in_array($x,$arr);
}
*/
class AdminClariprintParametricsController extends ModuleAdminController
{

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = ClariprintAsset::$definition['table']; //Table de l'objet
		$this->identifier = ClariprintAsset::$definition['primary']; //Clé primaire de l'objet
		$this->className = ClariprintAsset::class; //Classe de l'objet

		Db::getInstance()->execute(sprintf('CREATE TABLE IF NOT EXISTS %sclariprint_asset (
			id_clariprint_asset INT AUTO_INCREMENT PRIMARY KEY,
			uuid varchar(100),
			name varchar(100),
			kind varchar(10),
			json text,
            `date_add` datetime DEFAULT NULL ,
            `date_upd` datetime DEFAULT NULL)' ,_DB_PREFIX_));


		if (Tools::getValue('id_clariprint_asset'))
		{
			parent::__construct('','new-theme');
		}
		else parent::__construct();
				//Liste des champs de l'objet à afficher dans la liste
		$this->fields_list = [
			'id_sample' => [ //nom du champ sql
				'title' => $this->module->l('ID'), //Titre
				'align' => 'center', // Alignement
				'class' => 'fixed-width-xs' //classe css de l'élément
			],
			'name' => [
				'title' => $this->module->l('name'),
				'align' => 'left',
			],
			'kind' => [
				'title' => $this->module->l('kind'),
				'align' => 'left',
			]			
		];
		//Ajout d'actions sur chaque ligne
		$this->addRowAction('edit');
		$this->addRowAction('delete');
	}




	public function initContent()
	{
		parent::initContent();
		return;
	}

	public function renderForm()
	{
		$this->context->controller->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
		$this->context->controller->addJS(_PS_JS_DIR_ . 'admin/tinymce.inc.js');

		$this->fields_form['submit'] = [
			'title' => $this->trans('Save', [], 'Admin.Actions'),
		];

		if (!($obj = $this->loadObject(true))) {
			return;
		}

		if (Tools::getValue('action') == "SaveAsset")
		{
			$obj->name= Tools::getValue('name');
			if (!$obj->uuid) $obj->uuid = $this->guidv4();
			$obj->kind= Tools::getValue('kind');
			$obj->json= Tools::getValue('json');
			$file = null;
			if ($obj->kind == 'FILE')
			{
				if ($file = Tools::fileAttachment('asset_file',false))
				{
					$obj->json = $file['name'];
				}
			}
			$obj->save();
			if ($obj->id && $file )
			{
				$dir = _PS_ROOT_DIR_.'/img/clariprint_asset/'.$obj->id;
				@mkdir($dir,0777,true);
				move_uploaded_file($file['tmp_name'], $dir.'/'.$obj->json);
			}
		}
		error_reporting(error_reporting() & ~E_NOTICE);

		$this->context->smarty->assign(['asset' => $obj]);
		$this->context->smarty->assign(['assets' => Db::getInstance()->executeS(sprintf("SELECT id_clariprint_asset,name,uuid,kind,
					if(kind = 'FILE',json,null) as json
						FROM %s%s order by name",_DB_PREFIX_,ClariprintAsset::$definition['table']))]);
		$this->context->smarty->assign(['kinds' => [
						'LAYOUT' => "layout",
						'SCENE' => 'Scène',
						'GROUP' => 'Groupe',
						'TEMPLATE' => 'Template',
						'FILE' => 'Fichier',
						'OBJECT' => 'object']]);

		$this->context->smarty->registerPlugin('function','displayCMS',[$this,'DisplayHelp']);
		$this->context->smarty->registerPlugin('function','ClariprintSetupFinishingOptions',[$this,'setupfinishingOptions']);
		$this->context->smarty->registerPlugin('modifier','infoFinishing',[$this,'infoFinishing']);


		$this->context->smarty->assign(['json' => json_decode($obj->json)]);
		$this->context->smarty->addTemplateDir(_PS_MODULE_DIR_.'/clariprint/views/front');

		return parent::renderForm();
	}

	public function DisplayHelp()
	{
	}
	
	public function in_array_silent($params, $smarty = null)
	{
		return true;
	}

	private function guidv4($data = null) {
		// Generate 16 bytes (128 bits) of random data or use the data passed into the function.
		$data = $data ?? random_bytes(16);
		assert(strlen($data) == 16);

		// Set version to 0100
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		// Set bits 6-7 to 10
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

		// Output the 36 character UUID.
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
	private function buildRequest($id,$action='GetSVG', $params = null)
	{
		$loaded = [];
		$asset = new ClariprintAsset($id);
		$obj = json_decode($asset->json);
		$library = [
			"require" => [],
			"templates" => [],
			"groups" => [],
			"layouts" => [],
			"scenes" => [],
			"finalCut" => []
		];
		$form_data = [
			"login" => "",
			"pass" => "",
			"parameters_value" => json_encode($params,JSON_UNESCAPED_SLASHES),
			"action" => $action, // que faire ? GetSVG, GetWebGl, GetMetrix, GetScenes, GetLayouts, GetTemplates
			"canvas_w" => 500,
			"canvas_h" => 500,
			"id" => $obj->name, // name de l'objet principal de l'action
			"library" => null];
		switch ($asset->kind) {
			case 'TEMPLATE': $library['templates'][] = $obj; break;
			case 'GROUP': $library['groups'][] = $obj; break;
			case 'LAYOUT': $library['layouts'][] = $obj; break;
			case 'SCENE': $library['scenes'][] = $obj; break;
			default:
				break;
		}
		$loaded[] = $asset->name;
		$toload = (isset($obj->require) ? $obj->require : []);
		while(count($toload)) {
			$r = array_pop($toload);
			if (is_string($r))
			{
				$x = explode("|",$r);
				$name= $x[0];
				$uuid = ((count($x) > 1) ? $x[1] : null);
				// load de QypAsset
				if (in_array($r, $loaded)) break;
				
				if ($uuid)
					$res = Db::getInstance()->executeS(sprintf("SELECT kind,json,name FROM %sclariprint_asset WHERE uuid = '%s' ",_DB_PREFIX_,$uuid));
				else {
					$res = Db::getInstance()->executeS(sprintf("SELECT kind,json,name FROM %sclariprint_asset WHERE name = '%s' ",_DB_PREFIX_,$name));
				}
				if (count($res) > 0)
				{
					$row = $res[0];
					$r_obj = json_decode($row['json']);
					$loaded[] = $row["name"];
					switch ($row["kind"]) {
						case 'TEMPLATE': $library['templates'][] = $r_obj; break;
						case 'GROUP': $library['groups'][] = $r_obj; break;
						case 'LAYOUT': $library['layouts'][] = $r_obj; break;
						case 'SCENE': $library['scenes'][] = $r_obj; break;
						default: break;
					}
					if(isset($r_obj->require))
					{
						if (is_array($r_obj->require))
						{
							foreach($r_obj->require as $r)
							{
								$toload[] = $r;
							}
						} else  $toload[] = $r_obj->require;
					}
				}
			}
		}


		$form_data["library"] = json_encode($library,JSON_UNESCAPED_SLASHES);
		if (Tools::getValue('clariprint_debug'))
		{
			echo '<pre>';
			echo json_encode($library,JSON_PRETTY_PRINT);
			echo json_encode($form_data,JSON_PRETTY_PRINT);
			echo '</pre>';
			return;
		}
		/*
		*/
		// http_get  => clariprint

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://lrdp.clariprint.com/optimproject/json.wcl',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $form_data
		));

		$response = curl_exec($curl);
		header('Content-type: application/json');
		curl_close($curl);
		echo $response;

		
	}
	function displayAjaxCallApi()
	{
		return $this->buildRequest(Tools::getValue('id'),Tools::getValue('api'),Tools::getValue('params'));
	}
	function displayAjaxSVG()
	{
		return $this->buildRequest(Tools::getValue('id'),'GetSVG',Tools::getValue('params'));
	}

	function displayAjaxSamplerJS()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://lrdp.clariprint.com/js/xl_3D_sampler.js',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_POSTFIELDS => null
		));
		$response = curl_exec($curl);
		header('Content-type: application/javascript');
		curl_close($curl);
		echo $response;
	}



	public function setupfinishingOptions($params,$smarty) {
		$mod = $this->module;
		$product = $params['product'];
		$options = $this->finishingOptions();
		$front_inline_varnish = array();
		$back_inline_varnish = array();

		$front_all = true; // in_array_silent('all',$product->options->finishing_front);
		$back_all = true; // in_array_silent('all',$product->options->finishing_front);
		foreach($this->finishinghInlineFull() as $kvel => $vel)
		{
			if ($front_all || in_array_silent($kvel,$product->options->finishing_front)) $front_inline_varnish[$kvel] = $vel;
			if ($back_all || in_array_silent($kvel,$product->options->finishing_back)) $back_inline_varnish[$kvel] = $vel;
		}
		$front_offline_varnish = array();
		$back_offline_varnish = array();

		foreach($this->finishingOfflineFull() as $kvel => $vel)
		{
			if ($front_all || in_array_silent($kvel,$product->options->finishing_front)) $front_offline_varnish[$kvel] = $vel;
			if ($back_all ||in_array_silent($kvel,$product->options->finishing_back)) $back_offline_varnish[$kvel] = $vel;
		}
		$front_varnish_set = array();
		$back_varnish_set = array();
		
		foreach($this->list_vernis_combines as $kvel => $vel)
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

			'PELLIC_SOL' => array('kind' => 'PelliculageSol','finition' => 'MAT','index' => 60, 'info' => $this->l('Floor Lamination')));
			
			
			
	}
	public function finishingMatchInlineFull($f) {
		return ($f['index'] <= 19);
	}
	public function finishinghInlineFull() {
		return array_filter($this->finishingOptions(), array($this,"finishingMatchInlineFull"));
	}
		public function finishingMatchOfflineFull($f) {
		return ($f['index'] >= 20 /* && $f['index'] <= 60 */);
	}
	public function finishingOfflineFull() {
		return array_filter($this->finishingOptions(), array($this,'finishingMatchOfflineFull'));
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


}
