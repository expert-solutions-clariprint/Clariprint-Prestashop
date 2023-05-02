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

class ClariprintServer extends ObjectModel
{
	/** @var integer */
	public $id;
		
	/** @var integer */
	public $group_id;

	/** @var integer */
	public $name;
	
	/** @var string */
	public $url;

	/** @var string */
	public $login;

	/** @var string */
	public $pass;

	/** @var string */
	public $key;

	/** @var string */
	public $mode;

	public $date_add;
	public $date_upd;
	
	/** @var string */
//	public $product_xml;

	
	/**
	* @see ObjectModel::$definition
	*/
	public static $definition = array(
		'table' => 'clariprint_server',
		'primary' => 'id',
		'multilang' => FALSE,
		'fields' => array(
			'group_id' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => FALSE),
			'name' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => TRUE),
			'url' => array('type' => self::TYPE_STRING, 'validate' => 'isUrl', 'required' => TRUE),
			'login' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => FALSE),
			'pass' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => FALSE),
			'key' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => FALSE),
			'mode' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => FALSE),
			'date_add' => 	array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
			'date_upd' => 	array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => FALSE)
		),
	);
	
	
	static public function createTable()
	{

		Db::getInstance()->execute(sprintf("CREATE TABLE IF NOT EXISTS `%sclariprint_server` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `group_id` int(11) DEFAULT NULL,
		  `name` varchar(30) DEFAULT NULL,
		  `url` varchar(50) DEFAULT NULL,
		  `login` varchar(50) DEFAULT NULL,
		  `pass` varchar(50) DEFAULT NULL,
		  `key` varchar(50) DEFAULT NULL,
		  `mode` varchar(50) DEFAULT NULL,
		  `state` int(11) DEFAULT '0',
		  `date_add` datetime DEFAULT NULL,
		  `date_upd` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY (`name`)		  
		) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;",_DB_PREFIX_));
		
	}
	
	static public function modes() {
		return array('direct' => 'Direct', 'markeplace' => 'Marketplace');
	}
	
	static public function getSevers($mode=null,$id_customer=null)
	{
		$sql = new DbQuery();
		$sql->select('cs.*');
		$sql->from('clariprint_server', 'cs');
		
		if ($id_customer) {
			$sub = new DbQuery();
			$sub->select('id_group');
			$sub->from('customer_group', 'cg');
			$sub->where(sprintf('id_customer = %d', (int)$id_customer));
			$sql->where(sprintf('group_id IN (%s)',$sub));
		}
		if ($mode)
			$sql->where('mode = \''.pSQL($mode).'\'');

		return Db::getInstance()->executeS($sql);
	}
	static public function directs($id_customer=null)
	{
		return self::getSevers('direct',$id_customer);
	}
	static public function marketplace($id_customer=null)
	{
		return self::getSevers('markeplace');
	}
	static public function marketplaces($id_customer=null)
	{
//		if (!$id_customer ) $id_customer = Context::getContext()->customer->id ; 
		return self::getSevers('markeplace',$id_customer);
	}
}
