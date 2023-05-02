<?php
require_once(dirname(__FILE__).'/clariprintserver.php');
if (_PS_MODE_DEV_) 
{
	require_once(dirname(__FILE__).'/clariprintasset.php');

}


class ClariprintConnect {
	var $server_url;
	var $session;
	
	static public function saveRequest($r)
	{
		$dir = dirname(__FILE__) . '/../logs/';
		$dir = _PS_ROOT_DIR_.'/log/';
		if (is_dir($dir))
		{
			if (is_array($r)) $r = print_r($r,true);
			file_put_contents($dir.sprintf('request_%s.json',date('Y_m_d_H_i_s')),$r);
		}
	}

	static function getServerConnexion($datas=null,$marketplace =false)
	{
		$url = Configuration::get('CL_MP_SERVER_URL');
		$login = Configuration::get('CL_MP_SERVER_LOGIN');
		$pass = Configuration::get('CL_MP_SERVER_PASS');
		$key = null;
		$id_server = Tools::getValue('id_server');
		if ($id_server) 
		{
			if ($marketplace) {
				$serveurs  = ClariprintServer::marketplaces(Context::getContext()->cart->id_customer);
				if (count($serveurs) > 0) {
					$serveur =  $serveurs[0];
				}
			} else {
				/* $serveurs  = ClariprintServer::directs(Context::getContext()->cart->id_customer);
				if (count($serveurs) > 0) {
					$serveur =  $serveurs[0];
				} */
				$serveur = new ClariprintServer($id_server);
				$serveur = (array)$serveur;
			}
			if (!$serveur)
			{
				// $serveur = new ClariprintServer(Tools::getValue('id_server'));
				$serveurs  = ClariprintServer::directs(Context::getContext()->cart->id_customer);
								if (count($serveurs) > 0) {
									$serveur =  $serveurs[0];
								} 
			}
			if ($serveur) {
				$url = $serveur['url'];
				$login = $serveur['login'];
				$pass = $serveur['pass'];
				$key = $serveur['key'];
			}
		} elseif (is_array($datas) && array_key_exists("solving_server",$datas)) 
		{
			$serveur = new ClariprintServer($datas['solving_server']);
			$url = $serveur->url;
			$login = $serveur->login;
			$pass = $serveur->password;
			
		}
		else if ($marketplace) {
			$url = Configuration::get('CL_MP_SERVER_URL');
			$login = Configuration::get('CL_MP_SERVER_LOGIN');
			$pass = Configuration::get('CL_MP_SERVER_PASS');
		} else {
			$url = Configuration::get('CL_SERVER_URL');
			$login = Configuration::get('CL_SERVER_LOGIN');
			$pass = Configuration::get('CL_SERVER_PASSWORD');
		}
		return ['url' => $url, 'login' => $login, 'pass' => $pass,'key' => $key];
	}

	static public function getHttDirectRequest($action,$datas=null,$timeout=1000)
	{
		$ch = curl_init();
		$conn = self::getServerConnexion();
		if (!$datas) $datas = $conn;
		elseif ($conn['key']) {
			$datas['key'] = $conn['key'];
		} else {
			$datas['pass'] = $conn['pass'];
			$datas['login'] = $conn['login'];
		}
		$datas['action'] = $action;
		curl_setopt($ch, CURLOPT_URL,$conn['url'] . '/optimproject/json.wcl');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($datas));
		
		$res = curl_exec($ch);
		curl_close($ch);
		if (defined('_PS_MODE_DEV_'))
		{

			$logf = fopen(_PS_ROOT_DIR_.'/log/solver.log','a');
			fwrite($logf,"\n--------------- ". date('d/m/Y H:i:s'). ">>>>\n");
			if (defined('_PS_MODE_DEV_'))  fwrite($logf,"DEBUG\n");
			fwrite($logf,$res);
			fwrite($logf,"\n--------------- ". date('d/m/Y H:M'). " <<<<<<<< \n");
					fwrite($logf,"\n--------------- ". date('d/m/Y H:M'). ">>>>\n");
					fwrite($logf,$res);
					fwrite($logf,"\n--------------- ". date('d/m/Y H:M'). " <<<<<<<< \n");
			
			fclose($logf);		
		}
		return json_decode($res);
	}

	static public function getHttRequestCurl($action,$datas=null,$timeout=1000,$json_auth = false,$marketplace=false) {
		$ch = curl_init();
		$id_server = Tools::getValue('id_server');
		
		$url = '';
		$login = '';
		$pass = '';
		$key = '';
		if (!$datas) return null;
		$lang_code = Context::getContext()->language->iso_code;
		if ($id_server) 
		{
			if ($marketplace) {
				$serveurs  = ClariprintServer::marketplaces(Context::getContext()->cart->id_customer);
				if (count($serveurs) > 0) {
					$serveur =  $serveurs[0];
				}
			} else {
				/* $serveurs  = ClariprintServer::directs(Context::getContext()->cart->id_customer);
				if (count($serveurs) > 0) {
					$serveur =  $serveurs[0];
				} */
				$serveur = new ClariprintServer($id_server);
				$serveur = (array)$serveur;
			}
			if (!$serveur)
			{
				// $serveur = new ClariprintServer(Tools::getValue('id_server'));
				$serveurs  = ClariprintServer::directs(Context::getContext()->cart->id_customer);
								if (count($serveurs) > 0) {
									$serveur =  $serveurs[0];
								} 
			}
			if ($serveur) {
				$url = $serveur['url'];
				$login = $serveur['login'];
				$pass = $serveur['pass'];
				$key = $serveur['key'];
			}
		} elseif (is_array($datas) && array_key_exists("solving_server",$datas)) 
		{
			$serveur = new ClariprintServer($datas['solving_server']);
			$url = $serveur->url;
			$login = $serveur->login;
			$pass = $serveur->password;
			
		}
		else if ($marketplace) {
			$url = Configuration::get('CL_MP_SERVER_URL');
			$login = Configuration::get('CL_MP_SERVER_LOGIN');
			$pass = Configuration::get('CL_MP_SERVER_PASS');
		} else {
			$url = Configuration::get('CL_SERVER_URL');
			$login = Configuration::get('CL_SERVER_LOGIN');
			$pass = Configuration::get('CL_SERVER_PASSWORD');
		}
		
		curl_setopt($ch, CURLOPT_URL,$url . '/optimproject/json.wcl');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type" => "application/x-www-form-urlencoded"));
		// curl_setopt($ch, CURLOPT_USERAGENT, $defined_vars['HTTP_USER_AGENT']);
		// Apply the XML to our curl call
		curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
		curl_setopt($ch, CURLOPT_POST, 1);
		
		$fields = array('action' => $action, 
						'add_delais' => 'true',
						'lang' => strtoupper($lang_code));
		if ($json_auth) {
			if ($key)
				$fields['key'] = $key;
			else {
				$datas['login'] = $login;
				$datas['password'] = $pass;
			}
			$fields['datas'] = json_encode($datas);
		} else {
			if ($key)
				$fields['key'] = $key;
			else {
				$fields['login'] = $login;
				$fields['password'] = $pass;
			}
			$fields['datas'] = $datas;
		}
		
		
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($fields));
		
		self::saveRequest($fields);
		$data = curl_exec($ch);
		curl_close($ch);
		$logf = fopen(_PS_ROOT_DIR_.'/log/solver.log','a');
		fwrite($logf,"\n--------------- ". date('d/m/Y H:M'). ">>>>\n");
		if (defined('_PS_MODE_DEV_'))  fwrite($logf,"DEBUG\n");
		fwrite($logf,$data);
		fwrite($logf,"\n--------------- ". date('d/m/Y H:M'). " <<<<<<<< \n");
		if (defined('_PS_MODE_DEV_'))
		{
					fwrite($logf,"\n--------------- ". date('d/m/Y H:M'). ">>>>\n");
					fwrite($logf,$data);
					fwrite($logf,"\n--------------- ". date('d/m/Y H:M'). " <<<<<<<< \n");
		}
		fclose($logf);		
		
		if (defined('_PS_MODE_DEV_'))
		{
			$logdir = _PS_ROOT_DIR_.'/log/';
			if (is_dir($logdir))
			{
				$logf = fopen($logdir.'/solver.log','a');
				if ($logf)
				{
					fwrite($logf,"\n--------------- ". date('d/m/Y H:i:s'). ">>>>\n");
					fwrite($logf,$data);
					fwrite($logf,"\n--------------- ". date('d/m/Y H:i:s'). " <<<<<<<< \n");
				}
			}
			
		}
		
		if ($json_auth) return $data;
		if ($data)
			return json_decode($data);
		
		return null;
	}
	static public function getHttRequest($action,$datas=null,$timeout=1000,$auth=false,$marketplace=false) {
		return self::getHttRequestCurl($action,$datas,$timeout,$auth,$marketplace);
	}
	static public function getHttRequestJsonAuth($action,$datas=null,$timeout=1000) {
		echo self::getHttRequestCurl($action,$datas,$timeout,true,true);
	}

	static public function checkAuth() {
		$r = self::getHttRequest('CheckAuth');
		if (is_object($r))
		{
			return $r->success;
		}
	}
	
	static public function quoteRequest($product)
	{
		
		if ($x = self::getHttRequest('QuoteRequest',$product,30000)) {
			if (isset($x->response)) {
				$x->request = $product;
				if ($v= $x->response)
				{
					$x->responseTxt = Tools::displayPrice($v, null) . 'HT'; 
				}
			}
			return $x;
		}
		return null;
	}

	static public function quoteRequestJson($json_product)
	{
		$x = self::getHttRequest('QuoteRequest',$json_product,30000);
		if ($v= $x->response)
		{
			$x->request = $json_product;
			$x->responseTxt = Tools::displayPrice($v, null) . 'HT'; 
		}
		return  $x;
	}

	static public function quoteRequestQuery($product)
	{
		$vals = array();
		parse_str($product,$vals);		
		if (_PS_MODE_DEV_)
		{
			$cp = $vals['clariprint_product'];
			if (isset($cp['parts']))
			{
				foreach($cp['parts'] as &$p)
				{
					if ($p['kind'] == 'parametric')
					{
						// $library = ClariprintAsset::buildLibraryFor($p['layout']);
						$library  = $p;
						$form_data = [
							"login" => "",
							"pass" => "",
							"parameters_value" => json_encode($p,JSON_UNESCAPED_SLASHES),
							"action" => 'GetBusinessTree', // que faire ? GetSVG, GetWebGl, GetMetrix, GetScenes, GetLayouts, GetTemplates
							"canvas_w" => 500,
							"canvas_h" => 500,
							"id" => $p['layout'], // name de l'objet principal de l'action
							"__library" => json_encode($library,JSON_UNESCAPED_SLASHES)];
						$bprod = self::getHttpSugarRequest('GetBusinessTree',$form_data);
						$form_price_data = [
							'datas' => ($bprod ? json_encode($bprod) : null),
						];
						$prices = null;
						if ($bprod)
							$prices = self::getHttDirectRequest('QuoteFromSugarCrepe',$form_price_data);
						if($prices) {
							$prices->request = $vals['clariprint_product'];

							return $prices;
						}
						else return null;
					}
				}

			} 
			// return null;
		}
		$p = str_replace("\\/","/",json_encode($vals)); //  json_encode($vals,64 /*JSON_UNESCAPED_SLASHES*/ );
		
		$x = self::getHttRequest('QuoteRequest',$p,30000);
		
		
		if ($x)
		{
			$x->request = $vals['clariprint_product'];
			if ($v = $x->response)
			{
				$x->responseTxt = Tools::displayPrice($v, null) . ' HT'; 
			}
		}
		
		
		return $x;
	}
	static function checkCache($product) {
		if ($v = Db::getInstance()->getValue('SELECT value FROM `'._DB_PREFIX_.'clariprint_solver_cache` WHERE  id_cache=\''. md5($product) .'\''))
			return json_decode($v);
		return null;
	}
	static function addToCache($product,$result) {
		$db = Db::getInstance();
		if (is_object($result)) $result = json_encode($result);
		if (is_array($result)) $result = json_encode($result);
			
		$db->execute(sprintf('INSERT INTO `'._DB_PREFIX_.'clariprint_solver_cache` (id_cache,created,value) VALUES (\'%s\',NOW(),\'%s\')',
										md5($product),$db->escape($result,true)));
										
										
		return md5($product);
	}


	static function GetNewProjectId($reference,$group=null,$code='PRESTASHOP') {
		return self::getHttRequestJsonAuth('GetNewProjectId',
							array(	'project_group' => ($group ? $group : $_SERVER['HTTP_HOST']),
									'project_code' => $code,
									'reference' => $reference
								));
	}

	static function solveRFQ($project,$project_id) {
		return self::getHttRequestJsonAuth('solveRFQ', array(
			'project' => $project,
			'project_id' => $project_id));
	}

	static function FetchRFQ($session_id) {
		return self::getHttRequestJsonAuth('FetchRFQ',array(
									'session_id' => $session_id
								));
	}

	static function FreeRFQSession($session_id) {
		return self::getHttRequestJsonAuth('FreeRFQSession',array(
									'session_id' => $session_id
								));
	}

	static function MoreDetailsForResultId($session_id,$options) {
		return self::getHttRequestJsonAuth('MoreDetailsForResultId',array(
									'session_id' => $session_id,
									'options' => $options
								));
	}


	static function getParametric3D($request)
	{
		$vals = array();
		parse_str($request,$vals);
		$cp = $vals['clariprint_product'];
		if (isset($cp['parts']))
		{
			foreach($cp['parts'] as &$p)
			{
				if ($p['kind'] == 'parametric')
				{
					// print_r($p);

					// $library = ClariprintAsset::buildLibraryFor($p['layout']);
					if (isset($library['library']['3D_scene']))
					{

						$library = ClariprintAsset::buildLibraryFor($library['library']['3D_scene']);
					}
					$form_data = [
							"login" => "",
							"pass" => "",
							"parameters_value" => json_encode($p,JSON_UNESCAPED_SLASHES),
							"action" => 'GetWebGl', // que faire ? GetSVG, GetWebGl, GetMetrix, GetScenes, GetLayouts, GetTemplates
							"canvas_w" => 500,
							"canvas_h" => 500,
							"id" => $p['layout']]; // name de l'objet principal de l'action
							// "library" => json_encode($p,JSON_UNESCAPED_SLASHES)];
						$bprod = self::getHttpSugarRequest('GetWebGl',$form_data);
						return $bprod;
				}
			}
		} 
		return null;
	}
	static function getParametricSVG($request)
	{
		$vals = array();
		parse_str($request,$vals);
		$cp = $vals['clariprint_product'];
		if (isset($cp['parts']))
		{
			foreach($cp['parts'] as &$p)
			{
				if ($p['kind'] == 'parametric')
				{
					$library = $p; // ClariprintAsset::buildLibraryFor($p['layout']);
					$form_data = [
							"login" => "",
							"pass" => "",
							"parameters_value" => json_encode($p,JSON_UNESCAPED_SLASHES),
							"action" => 'GetSVG_WF', // que faire ? GetSVG, GetWebGl, GetMetrix, GetScenes, GetLayouts, GetTemplates
							"canvas_w" => 500,
							"canvas_h" => 500,
							"id" => $library['id'], // name de l'objet principal de l'action
							"library" => json_encode($library,JSON_UNESCAPED_SLASHES)];
						$bprod = self::getHttpSugarRequest('GetSVG_WF',$form_data);
						return $bprod;
				}
			}
		} 
		return null;
	}

	static function getLayouts()
	{
		$form_data = [
			"login" => "",
			"pass" => "",
			"action" => 'GetLayoutsIds'];
		return  self::getHttpSugarRequest('GetLayoutsIds',$form_data);
	}

	static public function getHttpSugarRequest($action,$datas=null,$timeout=1000)
	{
		$ch = curl_init();
		$conn = [
			'url' => 'https://sugar.clariprint.com'
		];
		$datas['action'] = $action;
		curl_setopt($ch, CURLOPT_URL,$conn['url'] . '/json.wcl');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($datas));
		
		$res = curl_exec($ch);
		curl_close($ch);
		if (defined('_PS_MODE_DEV_'))
		{

			$logf = fopen(_PS_ROOT_DIR_.'/log/sugar.log','a');
			fwrite($logf,"\n--------------- ". date('d/m/Y H:i'). ">>>>\n");
			fwrite($logf,print_r($datas,true)."\n");


			if (defined('_PS_MODE_DEV_'))  fwrite($logf,"DEBUG\n");
			fwrite($logf,$res);
			fwrite($logf,"\n--------------- ". date('d/m/Y H:M'). " <<<<<<<< \n");
					fwrite($logf,"\n--------------- ". date('d/m/Y H:i'). ">>>>\n");
					fwrite($logf,$res);
					fwrite($logf,"\n--------------- ". date('d/m/Y H:i'). " <<<<<<<< \n");
			
			fclose($logf);		
		}
		$rep = json_decode($res);
		return (isset($rep->response) ? $rep->response : $rep);
	}

	static function sugarGetJsonParameters($layout)
	{
		$form_data = [
			"login" => "",
			"pass" => "",
			"id" => $layout,
			"action" => 'GetJsonParameters'];
		return self::getHttpSugarRequest('GetJsonParameters',$form_data);
	}

}
