<?php

/*
 * 2013 EXPERT SOLUTIONS
 *
 * ****************************************************
 * @category   Clariprint
 * @package	Clariprint
 * @author	Xavier Péchoultres
 * @site	http://www.clariprint.com
 * @copyright  Copyright (c) 2013 - 2013 EXPERT SOLUTIONS SALR
 * @license	proprietary
 */

class HTMLTemplateClariprintQuote extends HTMLTemplate
{
	public $customization;
	public $customer;
	
	public function __construct($customization, $smarty, $bulk_mode = false)
	{
		$this->customization = $customization;
		$this->smarty = $smarty;
		
		$this->customer = new Customer($customization->id_customer);
	}
	
	/**
	* Returns the template's HTML content
	*
	* @return string HTML content
	*/
	public function getContent()
	{
		$address = null;
		if ($id_address = Address::getFirstCustomerAddressId($this->customer->id))
		{
			$address = new Address($id_address);
		}
		$this->smarty->assign(array(
			'customer' => $this->customer,
			'customization' => $this->customization,
			'address' => $address
		));
		return $this->smarty->fetch($this->getTemplate('quote'));
	}

	/**
	* Returns the template filename
	*
	* @return string filename
	*/
	public function getFilename()
	{
		return sprintf("imprimeurduroi_%s.pdf",$this->customization->id);
	}

	/**
	* Returns the template filename when using bulk rendering
	*
	* @return string filename
	*/
	public function getBulkFilename()
	{
		
	}
}
