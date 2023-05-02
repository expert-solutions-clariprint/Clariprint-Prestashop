<?php

class ClariprintProductModuleFrontController extends ModuleFrontController
{
	
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
		if ($id_product = Tools::getValue('id_product')) {
			if ($config = Clariprint_Config::objectForProduct($id_product))
			{
				$product = $config->product();
				if (isset($product->options->delivery_list))
				{
					$this->context->smarty->assign(
						array('deliverlist' => $product->options->delivery_list));
				}
			}
		}
		
		$this->context->smarty->assign(
			array(	'deliveries' => $this->module->getDeliveriesOptions(),
					'ui_mode' => Configuration::get('CL_FRONT_UI_MODE'),
					'no_sub_regions' => Configuration::get('CL_DELIVERIES_SUB'),
					'delivery_default'  => Configuration::get('CL_DELIVERY_DEFAULT'),
					'delivery_mode' => 'multiple',
					'delivery_nodelete' => false,
					
					'k' => uniqid('d'),
					'product_key' => Tools::getValue('product_key')));
		return $this->context->smarty->display(_PS_MODULE_DIR_.'/clariprint/views/front/delivery_item.tpl');
	}
}
