<?php
/**
 * 2018 EXPERT SOLUTION
 *
 *
 * @author    EXPERT SOLUTION SARL <contact@clariprint.com>
 * @copyright 2007-2017 Expert Solutions SARL
 * @license   Proprietary
 */

class OrderDetail extends OrderDetailCore
{
	/*
	Clariprint
	ajoute le prix de fabrication au tarif d'achat
	*/
	protected function setSpecificPrice(Order $order, $product = null)
	{
		parent::setSpecificPrice($order, $product);
		
		if ($product && isset($product['id_customization']))
			$this->purchase_supplier_price += Customization::getSupplierPrice($product['id_customization']);
		
		
	}	
}