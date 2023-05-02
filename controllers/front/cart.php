<?php
/*


	 

			[base_category]
				|
	{Product} - category - groupe - [Customer]
*/
require_once(dirname(__FILE__) . '/../../classes/clariprintcategory.php');

require_once(dirname(__FILE__) . '/../../classes/clariprintproductprocess.php');

require_once(dirname(__FILE__) . '/../../classes/clariprintmargin.php');

require_once(dirname(__FILE__) . '/../../classes/clariprint_config.php');
require_once(dirname(__FILE__) . '/../../classes/clariprintcustomization.php');




class ClariprintCartModuleFrontController extends ModuleFrontController
{

	var $msgs = array();
	private static function createMultiLangField($field)
	{
		$languages = Language::getLanguages(false);
		$res = array();
		foreach ($languages AS $lang)
			$res[$lang['id_lang']] = $field;
		return $res;
	}


	public function displayAjaxAddToCart()
	{
		$this->msgs = array();

		$this->addToCart();
		$res = array('success' => true);
		if (count($this->msgs) > 0)
		{
			$res['messages'] = $this->msgs;
			$res['success'] = false;
		}
		if (Tools::isSubmit('reload'))
			header('Location: '.Tools::getValue('reload'));
		die(Tools::jsonEncode($res));
	}

	
	function getWithIdCache($id_cache) {
		if ($v = Db::getInstance()->getValue('SELECT value FROM `'._DB_PREFIX_.'clariprint_solver_cache` WHERE  id_cache=\''.$id_cache.'\' AND DATEDIFF(NOW(),created) < 20 ORDER BY created desc'))
		{
			$x =  json_decode($v);
			$x->uid = $id_cache;
			return $x;
		}
		return null;
	}

	public function displayAjaxSaveProject()
	{
		if (!$this->context->customer->isLogged()) {
			echo 'Customer is unknown';
			return;
		}
		$id_product = Tools::getValue('clariprint_product_id');
		$project_name = Tools::getValue('clariprint_project_name');
		$id_cache =	Tools::getValue('clariprint_solver_uid');
		$result = $this->getWithIdCache($id_cache);
		$form = Tools::getValue('clariprint_form');

		if (!$result) {
			echo "error";
			return "error";
		}
		if ($cfg = Clariprint_Config::objectForProduct($id_product))
		{
			$cp = new ClariprintCustomization();
//			$cp->id_customization = $id_customization;
			$cp->id_product = $id_product;
			$cp->name = $project_name;
			$cp->resume = $result->html;
			if ($result->use_public_forcart && $result->costs->public)
				$cp->price_wt = $result->costs->public; // $result->priceHT;
			else
				$cp->price_wt = $result->costs->total; // $result->priceHT;
			$cp->id_customer = $this->context->customer->id;
			$cp->project =  json_encode($result);
			echo $result->priceHT;
			if ($cp->add()) echo 'OK';
		}
		echo 'error';
	}

	public function displayAjaxAddToCart2()
	{
		$id_product = Tools::getValue('clariprint_product_id');
		// $product_name = Tools::getValue('clariprint_product_name');
		$project_name = Tools::getValue('clariprint_project_name');
		
		$id_cache =	Tools::getValue('clariprint_solver_uid');
		
		$result = $this->getWithIdCache($id_cache);
		
		if (!$result) {
			echo "error";
			return "error";
		}
		
		if ($cfg = Clariprint_Config::objectForProduct($id_product))
		{
			$cfg->createCustomizationField();
			$cart = & Context::getContext()->cart;
			if (!$cart->id) {
				if (Context::getContext()->cookie->id_guest) {
					$guest = new Guest(Context::getContext()->cookie->id_guest);
					$this->context->cart->mobile_theme = $guest->mobile_theme;
				}
				$cart->add();
				if ($this->context->cart->id) {
					$this->context->cookie->id_cart = (int)$this->context->cart->id;
				}
			}
			
			$type = Product::CUSTOMIZE_TEXTFIELD;
			$index = $cfg->id_customization_field;


			if (!$index)
			{
				echo "error";
				die();	
			}
			$price = $result->response;
			if ($result->use_public_forcart)
			{
				
				$price = ceil($result->costs->public);
			}
			
			// $price = $result->response;
			$weight = $result->weight;

			$presta_product = new Product($id_product);
			$price -= $presta_product->price;
			$supplier_price = $presta_product->price;
			if (isset($result->supplier_costs))
				$supplier_price = (float)$result->supplier_costs->total;
			
			$product = $cfg->product();
			
			$project_name = Tools::getValue('clariprint_project_name');
			$exising_customization = Db::getInstance()->executeS(
				'SELECT cu.`id_customization`, cd.`index`, cd.`value`, cd.`type` FROM `'._DB_PREFIX_.'customization` cu
				LEFT JOIN `'._DB_PREFIX_.'customized_data` cd
				ON cu.`id_customization` = cd.`id_customization`
				WHERE cu.id_cart = '.(int)$cart->id.'
				AND cu.id_product = '.(int)$id_product.'
				AND in_cart = 0'
			);
			if ($exising_customization && false) {
				if ($exising_customization) {
					// If the customization field is alreay filled, delete it // index = 
					foreach ($exising_customization as $customization) {
						if ($customization['type'] == $type && $customization['index'] == $index) {
							Db::getInstance()->execute('
								DELETE FROM `'._DB_PREFIX_.'customized_data`
								WHERE id_customization = '.(int)$customization['id_customization'].'
								AND type = '.(int)$customization['type'].'
								AND `index` = '.(int)$customization['index']);
							if ($type == Product::CUSTOMIZE_FILE) {
								@unlink(_PS_UPLOAD_DIR_.$customization['value']);
								@unlink(_PS_UPLOAD_DIR_.$customization['value'].'_small');
							}
							break;
						}
					}
					$id_customization = $exising_customization[0]['id_customization'];
				} 
			} else {
				$id_product_attribute = 0;
				$quantity = 0;
				Db::getInstance()->execute(
					'INSERT INTO `'._DB_PREFIX_.'customization` (`id_cart`, `id_product`, `id_product_attribute`, `quantity`)
					VALUES ('.(int)$cart->id.', '.(int)$id_product.', '.(int)$id_product_attribute.', '.(int)$quantity.')'
					);
				$id_customization = Db::getInstance()->Insert_ID();
			}
			$cp = new ClariprintCustomization();
			$cp->id_customization = $id_customization;
			/** @var integer */
			$cp->id_product = $id_product;
			/** @var integer */
			$cp->name = $project_name;
			if ($this->context->customer->isLogged()) {
				$cp->id_customer = $this->context->customer->id;
			}
			
			/** @var integer */
			$cp->project =  json_encode($result);
			$cp->add();
			
			$query = 'INSERT INTO `'._DB_PREFIX_.'customized_data` (`id_customization`, `type`, `index`, `value`,`id_module`, `price`, `weight`,`supplier_price`)
				VALUES ('.(int)$id_customization.', 1, '.$index.', \''. $cp->id .'\','. $this->module->id . ','.(float)$price.','. (float)$weight .','.(float)$supplier_price .')';
			
			
			if (Db::getInstance()->execute($query)) {
				header('Content-Type: application/json');
				echo $id_customization;
				return $id_customization;
			}
			echo 0;
			return 0;
		}
		
		$this->msgs = array();
		return "ERREUR";
		$res = array('success' => true);
		if (count($this->msgs) > 0)
		{
			$res['messages'] = $this->msgs;	
			$res['success'] = false;
		}
		die(Tools::jsonEncode($res));
	}

	public function displayAjaxAddProject()
	{
		$this->msgs = array();

		$this->copyProduct();
		$res = array('success' => true);
		if (count($this->msgs) > 0)
		{
			$res['messages'] = $this->msgs;
			$res['success'] = false;
		}
		die(Tools::jsonEncode($res));
	}


	public function addToCart2()
	{
		return "coucou";
	}
	
	public function addToCart()
	{



		return $this->copyProduct(true);
	}


	public function copyProduct($addtocart=false)
	{
		$id_customer = Context::getContext()->customer->id;
		if (!$id_customer) {
			$this->msgs[] = 'You must be connected to add custom products to you cart';
			return false;
		}
		
		$category = ClariprintCategory::getCategory($id_customer);

		$product =  new Product(Tools::getValue("clariprint_product_id"));


		if ($pref = Tools::getValue('clariprint_product_key'))
		{
			$res = ClariprintConnect::checkCache($pref);
			if (!$res->costs) $res = null;
			
		}
		if ($costs = $res->costs)
		{
			$proof = 0;
			$pdiscount = 0;
			$fixed_price = -1;
			if ($req = $res->request)
			{
				if (is_array($req)) {
					if ($req['proofing'] == 'hard')
						$proof = (float)Configuration::get('CL_HARD_PROOF');
					if ($req['discounts']) {
						$pdiscount = ClariprintMargin::getProductDiscount($req['discounts'],$req['quantity'],$req['discounts_group']);
						$fixed_price = ClariprintMargin::getProductFixed($req['discounts'],$req['quantity'],$req['discounts_group']);
					}
				} elseif (is_object($req))
				{	if ($req->proofing == 'hard')
						$proof = (float)Configuration::get('CL_HARD_PROOF');
					if (isset($req->discounts))
					{
						$pdiscount = ClariprintMargin::getProductDiscount($req->discounts,$req->quantity,$req->discounts_group);
						$fixed_price = ClariprintMargin::getProductFixed($req->discounts,$req->quantity,$req->discounts_group);
					}
						
				} 
			}


			if (!Tools::isSubmit('nodiscount') && $id_customer)
			{
				$res->costs = ClariprintMargin::applyForCustomer($id_customer,$costs->paper,$costs->print,$costs->makeready,$costs->packaging,$costs->delivery,$proof,$pdiscount);
			}
			else 
			{
				$res->costs = ClariprintMargin::applyForGroup(1,$costs->paper,$costs->print,$costs->makeready,$costs->packaging,$costs->delivery,$proof,$pdiscount);
			}

			if ($fixed_price > 0)
			{
				$product->price = $fixed_price;
			} else $product->price = $res->costs['total'];

		}

		if ($product  && $res)
		{
			$id_product_old = $product->id;
			unset($product->id);
			unset($product->id_product);
			$product->indexed = 0;
			$product->active = 	1;
			$product->quantity = 1;
			$product->out_of_stock = 0;

			$product->id_category_default = $category->id;
						
			$product->wholesale_price = $res->response;
//			$product->price = $res->costs->total; // $res->response;

			$name = Tools::getValue('clariprint_project_reference');
			if (!$name) $name = strftime("À la demande : %H:%M:%S");
			$product->name = $this->createMultiLangField($name);

			$product->description = $this->createMultiLangField(Tools::getValue('clarprint_html_description'));
			if (isset($res->text))
			{
				$product->description_short = $this->createMultiLangField(nl2br($res->text));
			} else  {
				$product->description = $this->createMultiLangField(Tools::getValue('clarprint_html_description'));
			}

			$product->reference = $product->supplier_reference;

			/* AND Category::duplicateProductCategories($id_product_old, $product->id) */
			if (Configuration::get('CL_USE_CALCULATED_WEIGHT')) {
				if (isset($res->weight)) {
					$product->weight = $res->weight;
				} else $product->weight = 0;
			}

			if ($product->add()
				AND ($combinationImages = Product::duplicateAttributes($id_product_old, $product->id)) !== false
				AND Product::duplicateAccessories($id_product_old, $product->id)
				AND Product::duplicateFeatures($id_product_old, $product->id)
				AND Pack::duplicate($id_product_old, $product->id)
				AND Product::duplicateCustomizationFields($id_product_old, $product->id)
				AND Product::duplicateTags($id_product_old, $product->id)
				AND Product::duplicateDownload($id_product_old, $product->id))
			{
				$product->addToCategories($category->id);
				$product->available_for_order = true;
				
				$product->reference = sprintf('FAB'.$product->id);
				$product->update();
				// check attachements => faire un hook pour le module Attachements plus tard ...
				$need_attachments = Db::getInstance()->getValue(sprintf('SELECT clariprint_attachments FROM %sproduct WHERE id_product = %d',_DB_PREFIX_,$id_product_old));
				if ($need_attachments) {
					
					Db::getInstance()->getValue(sprintf('UPDATE %sproduct SET clariprint_attachments = %d WHERE id_product = %d',_DB_PREFIX_,$need_attachments,$product->id));
				}

				if ($product->hasAttributes())
					Product::updateDefaultAttribute($product->id);

				if (!Tools::getValue('noimage') AND !Image::duplicateProductImages($id_product_old, $product->id, $combinationImages))
					$this->_errors[] = Tools::displayError('An error occurred while copying images.');
				else
				{
					ClariprintProductProcess::addPdfToProduct(base64_decode($res->quote_process),$product->id);
					Hook::exec('actionProductAdd', array('product' => $product));
					Search::indexation(false, $product->id);
					$cart = self::$cart;
					if ($addtocart) {
						if (!isset(self::$cart->id) OR !self::$cart->id)
						{
							self::$cart->add();
							if (self::$cart->id)
								self::$cookie->id_cart = (int)(self::$cart->id);
						}
						self::$cart->updateQty(1, $product->id);
					}
				}
			}
			else
				$this->_errors[] = Tools::displayError('An error occurred while creating object.');
		}
	}
}