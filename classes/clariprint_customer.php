<?php

/*
 * 2013 EXPERT SOLUTIONS
 *
 * ****************************************************
 * @category   Clariprint
 * @package    Clariprint
 * @author    Xavier Péchoultres
 * @site    http://www.clariprint.com
 * @copyright  Copyright (c) 2013 - 2013 EXPERT SOLUTIONS SALR
 * @license    proprietary
 */

class Clariprint_Customer extends ObjectModel
{
	/** @var integer */
	public $id;
		
	/** @var integer */
	public $customer_id;
	
	/** @var string */
	public $clariprint_url;

	/** @var string */
	public $clariprint_login;


	/** @var string */
	public $clariprint_pass;

	/** @var string */
//	public $product_xml;

	public static function createTable()
	{
		Db::getInstance()->execute(sprintf('CREATE TABLE IF NOT EXISTS `%sclariprint_customer`   (
					id int(11) auto_increment primary key,
					customer_id int(11) NOT NULL UNIQUE,
					clariprint_url varchar(200),
					clariprint_login varchar(200),
					clariprint_pass varchar(200)
					) ENGINE=%s DEFAULT CHARSET=utf8;',_DB_PREFIX_,_DB_PREFIX_,_MYSQL_ENGINE_));
					//					CONSTRAINT FOREIGN KEY (customer_id) REFERENCES  `%scustomer` (id_customer) ON DELETE CASCADE

	}
	

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'clariprint_customer',
        'primary' => 'id',
        'multilang' => FALSE,
        'fields' => array(
            'id' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'customer_id' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => TRUE),
            'clariprint_url' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'clariprint_login' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'clariprint_pass' => array('type' => self::TYPE_STRING, 'validate' => 'isString')
        ),
    );
	
	public static function objectForCustomer($customer_id)
	{
	        $result = Db::getInstance()->getRow('SELECT id FROM `'._DB_PREFIX_.'clariprint_customer` sample WHERE sample.`customer_id` = '.(int)$customer_id);
			if ($result) return new Clariprint_Customer($result['id']);
			return null;
	}
	
}
