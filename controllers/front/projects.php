<?php
require dirname(__FILE__) . '/../../classes/clariprintcustomization.php';
require dirname(__FILE__) . '/../../classes/HTMLTemplateClariprintQuote.php';

class ClariprintProjectsModuleFrontController extends ModuleFrontController
{

	/* trace(string|array|object)
	 * ecrit dans le fichier log 
	 */
	var $tracefile = null;
	function trace($txt)
	{
		if (!$this->tracefile) 
		{
			$this->tracefile = fopen(_PS_MODULE_DIR_.'/clariprint/logs/projects_ctrl.log','a');
			fwrite($this->tracefile,'------------------------------ '. date('c')."\n");
		}
		if (is_object($txt) || is_array($txt))
			fwrite($this->tracefile,print_r($txt,true));
		else fwrite($this->tracefile,$txt);
		fwrite($this->tracefile,"\n");
		fflush($this->tracefile);
	}

	public function initContent()
	{
		parent::initContent();
		
		if (Tools::getValue('pdf'))
		{
			$this->printPdf(Tools::getValue('id_customisation'));
			return null;
		}
		
		if (Context::getContext()->customer->isLogged()) {
			
			$this->context->smarty->assign(
				array( 
					'id_currency' => $this->context->currency->id,
					'link' => $this->context->link,
					'projects' => ClariprintCustomization::objectsForCustomer($this->context->customer->id))
			);
			
		}
		else {
			
			$this->context->smarty->assign(
				array(
					'projects' => [],
					 'message' => $this->l('You need to be logged')));
			
		}
		$this->setTemplate('module:clariprint/views/templates/front/projects.tpl');
	}
	
	public function displayAjaxAddToCart()
	{
		$this->trace('displayAjaxAddToCart');
		$debug = ($_SERVER['REMOTE_ADDR'] == '81.220.42.58') || (Tools::getValue('debug') == 'wtf');
		$x = new ClariprintCustomization(Tools::getValue('id_customization'));

		if ($debug) echo "la";

		if ($x->id_customer != Context::getContext()->customer->id)
		{
			$this->redirect_after = '404';
			$this->redirect();
			return;
		}
		$project = json_decode($x->project);
		$this->trace('projet');
		$this->trace($project);
		
/*		if ($x->id_customization)
		{
			// deja utilisé, une copy doit être faite
			$x->date_add = null;
			$x->id_customization = null;
			$x->id = null;
			
			$x->add();
		}
		*/
		
		$cart = & Context::getContext()->cart;
		if (!$cart->id) {
			$this->trace('Nouveau du panier');
			if (Context::getContext()->cookie->id_guest) {
				$guest = new Guest(Context::getContext()->cookie->id_guest);
				$this->context->cart->mobile_theme = $guest->mobile_theme;
			}
			$cart->add();
			if ($this->context->cart->id) {
				$this->context->cookie->id_cart = (int)$this->context->cart->id;
			}
		}
		$this->trace("id_cart:". $cart->id);
		$this->trace("context cart->id:". $this->context->cart->id);
		$this->trace("cookie id_cart:". $this->context->cookie->id_cart);
		
		if (!$x->id_product)
		{
			$this->redirect_after = '404';
			$this->redirect();
			return;
		}

		$presta_product = new Product($x->id_product);
//		echo $project->costs->total;

		$this->trace("product: ".$presta_product->id);
		
		$price = $project->costs->total;
		if ($project->use_public_forcart)
		{
			$price = ceil($project->costs->public);
		}

		if (!$price)
		{
			$price = ceil($project->costs);
		}

		$price = $price - $presta_product->price;

		$supplier_price = $presta_product->price;
		if (isset($project->supplier_costs))
			$supplier_price = (float)$project->supplier_costs->total;

		$id_product_attribute = 0;
		$cfg = Clariprint_Config::objectForProduct($x->id_product);

		$this->trace("cfg");
		$this->trace($cfg);
		$this->trace("--------------------------------");

		$type = Product::CUSTOMIZE_TEXTFIELD;
		$quantity = 1	;
		if ($cfg)
			$index = $cfg->id_customization_field;
		
		if (!$index)
		{
			$index = Db::getInstance()->getValue(sprintf("select id_customization_field from %scustomization_field where id_product = %d",
						_DB_PREFIX_, $x->id_product));
		}

		Db::getInstance()->execute(
			'INSERT INTO `'._DB_PREFIX_.'customization` (`id_cart`, `id_product`, `id_product_attribute`, `quantity`)
			VALUES ('.(int)$cart->id.', '.(int)$x->id_product.', 0, '.(int)$quantity.')'
			);
		$id_customization = Db::getInstance()->Insert_ID();

		$this->trace("id_customization = $id_customization");

		if (!$id_customization) {
			echo 'ERRRRROOOR';
			die();
		}
		$weight = 0.0;
		//$index = 1;

		
		$query = 'INSERT INTO `'._DB_PREFIX_.'customized_data` (`id_customization`, `type`, `index`, `value`,`id_module`, `price`, `weight`,`supplier_price`)
				VALUES ('.(int)$id_customization.', 1, '.$index.', \''. $x->id .'\','. $this->module->id . ','.(float)$price.','. (float)$weight .','.(float)$supplier_price .')';

		$this->trace($query);

		Db::getInstance()->execute($query);

		$x->id_customization = $id_customization;
		$x->update();
		$id_product_attribute = Db::getInstance()->getValue(sprintf("SELECT id_product_attribute FROM  %sproduct_attribute where id_product = %d",
						_DB_PREFIX_,$x->id_product));

		$this->trace("updateQty   id_product:$x->id_product,id_product_attribute:$id_product_attribute,:id_customization$id_customization ");
		$cartd = $cart->updateQty(1, $x->id_product,$id_product_attribute,$id_customization);
		$this->trace("redirect ... ");
		$this->trace($cartd);

		Tools::redirect($this->context->link->getPageLink('cart'));
		die();
	}
	
	public function printPdf($id_customization)
	{
		if ($x = new ClariprintCustomization($id_customization))
		{
			if ($x->id_customer != Context::getContext()->customer->id)
			{
				$this->redirect_after = '404';
				$this->redirect();
				return;
			}
			if ($x->resume == '') {
				$prj = json_decode($x->project);
				$x->resume = $prj->html;
				
				$reduction = Group::getReduction(Context::getContext()->customer->id);
				
				if ($reduction)
					$x->price_wt = (float)$prj->costs->total * (100 - $reduction) / 100.;
				else $x->price_wt = (float)$prj->costs->total;
				
				$x->update();
//				print_r($prj);
//				die();
			} else {
				$reduction = Group::getReduction(Context::getContext()->customer->id);
				if ($reduction)
					$x->price_wt = $x->price_wt * (100. - $reduction) / 100.;
			}
			$pdf = new PDF($x,'ClariprintQuote',$this->context->smarty);
			$pdf->render('output.pdf','I');
			die();
		} else {
			$this->redirect_after = '404';
			$this->redirect();
		}
	}
}
