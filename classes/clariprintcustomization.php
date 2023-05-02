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

/*
tup[1], //qualite
tup[2], // marque
tup[3], // couleur
tup[4], // grammage
(paper_property!(and(v,PAPER_PROPERTY_RECYCLED))),
(paper_property!(and(v,PAPER_PROPERTY_FSC))),
(paper_property!(and(v,PAPER_PROPERTY_FSC_MIXED))),
(paper_property!(and(v,PAPER_PROPERTY_FSC_RECYCLED))),
(paper_property!(and(v,PAPER_PROPERTY_PEFC))),
(paper_property!(and(v,PAPER_PROPERTY_PEFC_70))),
(paper_property!(and(v,PAPER_PROPERTY_PEFC_RECYCLED))),
(paper_property!(and(v,PAPER_PROPERTY_ECOLABEL))),
(paper_property!(and(v,PAPER_PROPERTY_BLUE_ANGEL))),
(paper_property!(and(v,PAPER_PROPERTY_NORDIC_SWAN))),
(paper_property!(and(v,PAPER_PROPERTY_APUR))),
(paper_property!(and(v,PAPER_PROPERTY_PAPER_BY_NATURE))),
(paper_property!(and(v,PAPER_PROPERTY_of))),
(paper_property!(and(v,PAPER_PROPERTY_or))),
(paper_property!(and(v,PAPER_PROPERTY_oc))),
(paper_property!(and(v,PAPER_PROPERTY_n))),
(paper_property!(and(v,PAPER_PROPERTY_h))),
(paper_property!(and(v,PAPER_PROPERTY_f))),
(paper_property!(and(v,PAPER_PROPERTY_s))),
(paper_property!(and(v,PAPER_PROPERTY_DISTRIB))),
(paper_property!(and(v,PAPER_PROPERTY_USINE))),
(paper_property!(and(v,PAPER_PROPERTY_FAB))))),
*/
$DBG_SQL=array();

class ClariprintCustomization extends ObjectModel
{
	/** @var integer */
	public $id;

	/** @var integer */
	public $id_customization;

	/** @var integer */
	public $id_customer;

	/** @var integer */
	public $id_product;

	/** @var integer */
	public $name;

	/** @var integer */
	public $project;
	
	public $date_add;
	public $date_upd;
	
	public $price_wt;
	
	public $resume;
	
	/** @var string */
//	public $product_xml;
	
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'clariprint_customization',
		'primary' => 'id',
		'multilang' => FALSE,
		'fields' => array(
			/** @var integer */
			'id_customization' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),

			/** @var integer */
			'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),

			/** @var integer */
			'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),

			/** @var string */
			'name' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),

			/** @var string */
			'resume' => array('type' => self::TYPE_HTML),

			/** @var string */
			'price_wt' => array('type' => self::TYPE_FLOAT),

			/** @var string */
			'project' => array('type' => self::TYPE_HTML, 'validate' => 'isString'),
			'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate')
			
		));		
		
	static public function _objectForCustomization($id_customization)
	{
		$sql  = new DbQuery();
		$sql->select('cc.*');
		$sql->from('clariprint_customization','cc');
		$sql->where('id_customization = '.(int)$id_customization);
		self::_objectForCustomization($id_customization);
		return Db::getInstance()->getRow($sql);
	}
	static public function objectForCustomization($id_customization)
	{
		$sql  = new DbQuery();
		$sql->select('cc.*');
		$sql->from('clariprint_customization','cc');
		$sql->rightjoin('customized_data', 'cd', '(cd.value = cc.id)');
		$sql->where('cd.id_customization = '.(int)$id_customization);
		return Db::getInstance()->getRow($sql);
	}
	
	
	static public function objectsForCustomer($id_customer,$page = 0,$nums=30)
	{
		$sql  = new DbQuery();
		$sql->select('cc.name,cc.id,cc.resume,cc.date_add,cc.id_customization,cc.price_wt,cc.id_product');
		$sql->from('clariprint_customization','cc');
		$sql->where('id_customer = '.(int)$id_customer);
		$sql->orderby('cc.date_add desc');
		return Db::getInstance()->executeS($sql);
	}
}
