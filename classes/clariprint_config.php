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

class Clariprint_Config extends ObjectModel
{
	/** @var integer */
	public $id;
		
	/** @var integer */
	public $product_id;
	
	/** @var string */
	public $product_kind;

	/** @var string */
	public $dynamic;

	/** @var string */
	public $product_json;

	/** @var intger */
	public $id_customization_field;
	
	


	public $date_add;
	public $date_upd;
	public $date_cal;
	
	/** @var string */
//	public $product_xml;

	
	/**
	* @see ObjectModel::$definition
	*/
	public static $definition = array(
		'table' => 'clariprint_product',
		'primary' => 'id',
		'multilang' => FALSE,
		'fields' => array(
			'product_id' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => TRUE),
			'dynamic' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => FALSE),
			'id_customization_field' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => TRUE),
			'product_kind' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'product_json' => array('type' => self::TYPE_HTML, 'validate' => 'isString'),
			'date_add' => 	array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
			'date_upd' => 	array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => FALSE),
			'date_cal' => 	array('type' => self::TYPE_DATE,  'required' => FALSE)
		),
	);
	
	
	static public function createTable()
	{

		Db::getInstance()->execute("CREATE TABLE IF NOT EXISTS `%sclariprint_product` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `product_id` int(11) DEFAULT NULL,
		  `product_kind` varchar(30) DEFAULT NULL,
		  `product_json` longblob,
		  `id_customization_field` int(11) DEFAULT NULL,
		  `state` int(11) DEFAULT '0',
		  `dynamic` int(11) DEFAULT '0',
		  `date_add` datetime DEFAULT NULL,
		  `date_upd` datetime DEFAULT NULL,
		  `date_cal` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `product_id` (`product_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;",_DB_PREFIX_);
	}
	
	
	public function add($auto_date = true, $null_values = false) {
		$this->createCustomizationField();
		return parent::add();
	}
	
	public function createCustomizationField()
	{
		if ($this->id_customization_field) {
			$cf = new CustomizationField($this->id_customization_field);
			if (Validate::isLoadedObject($cf)) {
				return;
			}
		}
		
		$cf = new CustomizationField();
		$cf->id_product = $this->product_id;
		$cf->type = 1;
		$cf->required = true;
		$cf->is_module = 1;
		$cf->name = array();
		foreach(Language::getLanguages() as $l)
		{
			$cf->name[$l['id_lang']] = 'Product configuration';
		}
		if ($cf->add())
		{
			$this->id_customization_field  = $cf->id;
		} else {
			throw new PrestaShopException('Cannot create customization field for Clariprint');
			die();
		}
	}

	
	public function update($null_values = false)
	{
		$this->createCustomizationField();
		return parent::update($null_values);
	}
	
	public function delete() {
		if ($this->id_customization_field) {
			$cf = new CustomizationField($this->id_customization_field);
			if ($cf->delete()) return parent::delete();
			return null;
		} else return parent::delete();
	}
	
	static public function deleteForProductId($id_product=null)
	{
		if ($id_product)
		{
			$ids =	Db::getInstance()->executeS('SELECT id FROM `'._DB_PREFIX_.'clariprint_product`  WHERE `product_id` = '.(int)$id_product);
			foreach($ids as $rid)
			{
				$cc = new Clariprint_Config($rid['id']);
				$cc->delete();
			}
		}
			
	}

	public static function objectForProduct($id_product){
		$result = Db::getInstance()->getRow('SELECT id FROM `'._DB_PREFIX_.'clariprint_product`  WHERE `product_id` = '.(int)$id_product);
		if ($result) return new Clariprint_Config($result['id']);
		return null;
	}
	
	public static function loadByIdProduct($id_product){
		$id = Db::getInstance()->getValue('SELECT id FROM `'._DB_PREFIX_.'clariprint_product` sample WHERE sample.`product_id` = '.(int)$id_product);
		return new Clariprint_Config($id);
	}

	public static function isDynamic($id_product) {
		return  Db::getInstance()->getValue(sprintf('SELECT dynamic FROM `%sclariprint_product`  WHERE `product_id` = %d', _DB_PREFIX_, $id_product));
	}
	
	public function product() {
		if ($this->product_json) return json_decode($this->product_json);
		return null;
	}
	static public function cleanDatabase()
	{
		Db::getInstance()->execute(sprintf('DELETE FROM %sclariprint_product WHERE NOT product_id IN (SELECT id_product FROM %sproduct)',_DB_PREFIX_,_DB_PREFIX_));
	}
}

