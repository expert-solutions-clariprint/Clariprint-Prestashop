<?php

require_once dirname(__FILE__).'/../../classes/clariprintconnect.php';
require_once dirname(__FILE__).'/../../classes/clariprintconnect.php';
require_once dirname(__FILE__).'/../../classes/clariprintmargin.php';

class ClariprintSolverModuleFrontController extends ModuleFrontController
{
	static public function conformUrl($html) {
		// picto_Decoupe
		$html = preg_replace('/src="(.*)picto_decoupe/','src="/modules/clariprint/img/cutting/',$html);
		return $html;
	}


	public function extraCosts($req) {
		$res = 0.0;
		
		if (array_key_exists('extra',$reg))
		{
			$extra = $req['extra'];
			if (is_array($extra))
			{
				foreach($extra as $k => $val) {
				}
			}
		}
		return $res;
	}
	
	public function displayAjaxPriceRequest()
	{

		if ($res = $this->callClariprint(Tools::getValue('product'),!Tools::isSubmit('no_cache')))
		{
			if (isset($res->success)) {
				$v = $res->response;
				$proof = 0;
				if (isset($res->costs))
				{	
					$res->supplier_costs = $res->costs;
					if ($costs = $res->costs)
					{
						$pdiscount = 0;
						$fixed_price = -1;
						if ($req = $res->request)
						{
							if (is_array($req)) {
								if ($req['proofing'] == 'hard')
									$proof = (float)Configuration::get('CL_HARD_PROOF');
								if (array_key_exists('discounts',$req))
								{
									$qt = (array_key_exists('quantity',$req) ? $req['quantity'] : 1);
									$pdiscount = ClariprintMargin::getProductDiscount($req['discounts'],$qt,$req['discounts_group']);
									$fixed_price = ClariprintMargin::getProductFixed($req['discounts'],$qt,$req['discounts_group']); 
								}
							} elseif (is_object($req))
							{	
								if ($req->proofing == 'hard')
									$proof = (float)Configuration::get('CL_HARD_PROOF');
									
								if (isset($req->discounts))
								{
									$pdiscount = ClariprintMargin::getProductDiscount($req->discounts,$req->quantity,$req->discounts_group);
									$fixed_price = ClariprintMargin::getProductFixed($req->discounts,$req->quantity,$req->discounts_group);
								}
							} 
						}
						$res->use_public_forcart =false;
						if ($gd = - Group::getReduction((int)  Context::getContext()->cookie->id_customer))
						{
							if (!$pdiscount) 
								$pdiscount = - Group::getReduction((int)  Context::getContext()->cookie->id_customer);
							$res->use_public_forcart =true;
						}
							
						
						$id_customer = Context::getContext()->customer->id;

						if (!Tools::isSubmit('nodiscount') && $id_customer)
						{
							$res->costs = ClariprintMargin::applyForCustomer($id_customer,$costs->paper,$costs->print,$costs->makeready,$costs->packaging,$costs->delivery,$proof,$pdiscount);
						}
						else 
						{
							$res->debug = "use default group";
							$res->costs = ClariprintMargin::applyForGroup(1,$costs->paper,$costs->print,$costs->makeready,$costs->packaging,$costs->delivery,$proof,$pdiscount);
						}
				
						if ($fixed_price > 0)
						{
							$res->response = $fixed_price;
							$res->costs['discount'] = 100 * $fixed_price / $res->costs['public'] - 100;
						} else $res->response = $res->costs['total'];
						$res->uniquekey = md5(Tools::getValue('product'));
						$res->fixed_price = $fixed_price;
						$v = $res->response;
						$prix_public = (float)$res->costs['public'];
						$public = Tools::displayPrice($prix_public, null);
						$res->responseTxt = '';
						if ((int)$res->costs['discount'] != 0)
						{
							if (Configuration::get('CL_SHOW_DISCOUNT')) {
								$res->responseTxt = '<p class="clariprint_public_price">'. sprintf($this->module->l('Prix public : %s HT'),$public).'</p>';
								$res->responseTxt .= '<p class="clariprint_discount">'.sprintf($this->module->l('Remise : %.2f%%'),$res->costs['discount']);
							}
							$res->responseTxt .= '<p class="clariprint_price">'.sprintf('Votre prix : %.2f €HT',$v).'</p>';
//							Tools::displayPrice($v, null) . sprintf(' HT (%.2f%% incluse)',$res->costs['discount']); 

						} else $res->responseTxt = '<p class="clariprint_price">'.sprintf($this->module->l('Votre prix : %.2f €HT'),$v).'</p>';

						if ($proof) $res->responseTxt .= '<br><b>BAT PAPIER</b>';
						

						/*if (array_key_exists('delivery_time',$res->request))
						{
							$v = $res->request['delivery_time'];
							$res->responseTxt = '<p class="clariprint_deliverytime">'.sprintf($this->module->l('Delivery time (working days) : %d days'),$v).'</p>';
						} */
					}
				} else $res->responseTxt = Tools::displayPrice($v, null) . $this->module->l('HT');
				
				if (!isset($res->html)) $res->html = '';
				
				
				if (isset($res->weight)) {
					$res->html .= sprintf($this->module->l('Poids total : %.2f Kg'),$res->weight);
				}
				if (isset($res->delais))
				{
					$v = (int)$res->delais;
					// $v = 0 + ceil($delai / 80); // Heuree / 8 haures /jours => jours
					$res->delais_initial = $res->delais;

					if (isset($res->request->delivery_time))
					{
						$res->delais_complementaire = $res->request->delivery_time;
						$v += (int)$res->request->delivery_time;
					} elseif (is_array($res->request) && isset($res->request['delivery_time'])) {
						$res->delais_complementaire = $res->request['delivery_time'];
						$v += (int)$res->request['delivery_time'];
					} else {
						$res->message_delais = "pas délais comp";
					} 
					// $res->html .= '<div class="clariprint_deliverytime">'.sprintf($this->trans('Delivery time (working days) : %d days'),$v).'</div>';
					if ($proof) $res->html .= '<div><b>'. $this->l('BAT PAPIER').'</b></div>';
					$res->html .= '<div class="clariprint_deliverytime mb-2">'.sprintf('Production (jours ouvrés) : %d jours',$v).'</div>';
					if ($_SERVER['REMOTE_ADDR'] == '81.220.42.58___')
					{
						$res->html .= $this->trans('Delivery time (working days) : ');
						$res->html .= $this->trans('Delivery time (working days) : %d days',[],'modules.clariprint.solver');
						$res->html .= $this->l('Delivery time (working days) : %d days');
						//$res->html .= '<pre>'. print_r($this->context->getTranslator()->getCatalogue()->all(),true). '</pre>';
					}
//					$res->html .= '<div class="clariprint_deliverytime">'.sprintf($this->module->l('Delivery time (working days) : %d days'),$v).'</div>';
				}



				$res->html = self::conformUrl($res->html);
					

				$res->priceHT = Tools::displayPrice($v, null); 
				$res->priceTTC = Tools::displayPrice($v * 1.2 , null); //[TODO] mettre la bonne taxe xp 
				$cookie = Context::getContext()->cookie;
				$cookie->last_clariprint_call = $v;
			}
			$res->uid = 'cc_'.uniqid();
			$bob = Tools::jsonEncode($res);
			$db = Db::getInstance();
			$db->execute(sprintf('INSERT INTO `%sclariprint_solver_cache` (id_cache,created,value) VALUES (\'%s\',NOW(),\'%s\')',
											_DB_PREFIX_,
											$res->uid,
											$db->escape($bob,true)));
		} else {
//			print_r($res);
			// $res = new ;
			$res = new stdClass();
			$res->success = false;
			$res->info = '???';
			
		}
		if (!_PS_MODE_DEV_ && $res){
			$res->supplier_costs = null;
			$res->quote_process = null;
			$res->costs = null;
		}
		echo Tools::jsonEncode($res);
	}

	public static function displayAjaxcreateTables()
	{
		return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'clariprint_solver_cache`(
			`id_cache` varchar(32) NOT NULL,
			`created` datetime NOT NULL,
			`value` LONGBLOB,
			INDEX (`id_cache`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
	}

	public static function dropTables()
	{
		return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'clariprint_solver_cache`');
	}
	
	static function cleanCache() {
		return Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'clariprint_solver_cache` ');
	}

	static function refreshCache() {
		return Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'clariprint_solver_cache` WHERE  DATEDIFF(NOW(),created) > 20 ');
	}

	static function checkCache($product) {
		if ($v = Db::getInstance()->getValue('SELECT value FROM `'._DB_PREFIX_.'clariprint_solver_cache` WHERE  id_cache=\''. md5($product) .'\' AND DATEDIFF(NOW(),created) < 20 ORDER BY created desc'))
		{
			$x =  json_decode($v);
			$x->uid = md5($v);
			return $x;
		}
		return null;
	}
	static function addToCache($product,$result) {
		$db = Db::getInstance();
		if (is_object($result)) $result = json_encode($result);
		if (is_array($result)) $result = json_encode($result);
		$val = $db->escape($result,true);
		$uid = md5($product);
		$db->execute(sprintf('INSERT INTO `'._DB_PREFIX_.'clariprint_solver_cache` (id_cache,created,value) VALUES (\'%s\',NOW(),\'%s\')',
										$uid,$val,$val));
		return $uid;
	}
	
	static function getPdf($product) {
		$db = Db::getInstance();
		if (is_object($product)) $product = json_encode($product);
		if (is_array($product)) $product = json_encode($product);
		if ($v == self::checkCache($product))
			return $v->quote_process;
		return null;
	}
	
	static function uniqueKey($product) {
		return md5($product);
	}

	var $logfile = null;
	public function log($value='')
	{
		if (!$this->logfile) {
			$this->logfile = fopen($this->module->getLocalPath().'logs/frontsolver.log','a');
		}
		if ($this->logfile)
		{
			if (is_array($value) || is_object($value))
				fwrite($this->logfile,print_r($value,true)."\n");
			else
				fwrite($this->logfile, $value. "\n");
		}
	}
	
	public function callClariprint($product,$use_cache=true)
	{
		$this->log('callClariprint');
		if ($use_cache) {
			if ($x = self::checkCache($product)) {
				return $x;
			}
		}
		$v = ClariprintConnect::quoteRequestQuery($product);
		$this->log("resulte");
		$this->log($v);
		if ($use_cache) {
			$this->log('store in cache ...');
			if ($v) {
				if (isset($v->success) && $v->response > 0) {
					$v->uid = self::addToCache($product,$v);
				}
			}
		}
//		if ($v) $v->quote_process = null;


		return $v;
	}
	
	public function displayAjaxGetNewProjectId() {
		Tools::jsonEncode(ClariprintConnect::GetNewProjectId(Tools::getValue('reference'),Tools::getValue('group'),Tools::getValue('code')));
	}

	public function displayAjaxsolveRFQ() {
		$project = array();
		parse_str(Tools::getValue('project'),$project);
		Tools::jsonEncode(ClariprintConnect::SolveRFQ($project,Tools::getValue('project_id')));
	}

	public function displayAjaxFetchRFQ() {
		Tools::jsonEncode(ClariprintConnect::FetchRFQ(Tools::getValue('session_id')));
	}

	public function displayAjaxFreeRFQSession() {
		Tools::jsonEncode(ClariprintConnect::FreeRFQSession(Tools::getValue('session_id')));
	}

	public function displayAjaxMoreDetailsForResultId($session_id,$options) {
		Tools::jsonEncode(ClariprintConnect::MoreDetailsForResultId(Tools::getValue('session_id'),Tools::getValue('options')));
	}


	public function displayAjaxGetParametric3D()
	{
		$product = Tools::getValue('product');
		$x = ClariprintConnect::getParametric3D($product);
		echo $x->response;
	}
	public function displayAjaxGetParametricSVG()
	{
		$product = Tools::getValue('product');
		$x = ClariprintConnect::getParametricSVG($product);
		echo Tools::jsonEncode($x->response);
	}
}
