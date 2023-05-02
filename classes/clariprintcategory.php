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

Customer -> id_clariprint_group => 

*/


class ClariprintCategory
{

	public static function install()
	{
		Clariprint::addTableColumn('customer','id_clariprint_group','int(10) unsigned default null');
		Clariprint::addTableColumn('group','id_clariprint_category','int(10) unsigned default null');
		Clariprint::addTableColumn('category','clariprint_default','int(10) unsigned default null');
	}

	public static function getGroup($id_customer,$create=false) {
		$query = sprintf('SELECT id_clariprint_group FROM %scustomer WHERE id_customer=%d',_DB_PREFIX_,$id_customer);
		
		if ($v = Db::getInstance()->getValue($query))
		{
			return new Group($v);
		} elseif ($create )
		{
			return self::createGroup($id_customer);
		}
		return null;
	}
	private static function createMultiLangField($field)
	{
		$languages = Language::getLanguages(false);
		$res = array();
		foreach ($languages AS $lang)
			$res[$lang['id_lang']] = $field;
		return $res;
	}
	
	public static function createGroup($id_customer) {
		$g = new Group();
		$g->id_customer = $id_customer;
		
		$c = new Customer($id_customer);
		
		$g->name = self::createMultiLangField(substr(sprintf("%s %s",$c->lastname,$c->firstname),0,32));

		$g->price_display_method = 0;
		if ($g->add())
		{
			Db::getInstance()->execute(sprintf("UPDATE %scustomer SET id_clariprint_group = %d WHERE id_customer = %d ",_DB_PREFIX_,$g->id,$id_customer));
			$row = array('id_customer' => (int)($id_customer), 'id_group' => (int)($g->id));
			Db::getInstance()->AutoExecute(_DB_PREFIX_.'customer_group', $row, 'INSERT');

			return $g;
		} else new Exception("Error: cannot create group");
		return null;
	}
	
	
	
	public static function getGroupCategory($id_group,$create=false)
	{
		$query = sprintf('SELECT id_clariprint_category FROM %sgroup WHERE id_group=%d',_DB_PREFIX_,$id_group) ;
		
		if ($v = Db::getInstance()->getValue($query))
		{
			return new Category($v);
		} elseif ($create)
		{
			$cat = self::createCategory($id_group);
			$q = sprintf("UPDATE %sgroup SET id_clariprint_category = %d WHERE id_group = %d ",_DB_PREFIX_,$cat->id,$id_group);
			Db::getInstance()->execute($q);
			return $cat;
		}
		return null;
	}
	
	public static function getDefaultCategory($id_shop=null) {
		$query = sprintf('SELECT id_category FROM %scategory WHERE clariprint_default=%d',_DB_PREFIX_,1); //,$id_shop);
		if ($v = Db::getInstance()->getValue($query))
		{
			return new Category($v);
		} elseif($v = Configuration::get('CL_CATEGORY_TEMPLATE')) return new Category($v);
		return null;
	}
	
	public static function createCategory($id_group) {

		if ($cat = self::getDefaultCategory())
		{
			unset($cat->id);
			unset($cat->id_category);
			$group = new Group($id_group);
			$cat->name = $group->name;
			$cat->active = true;
			if ($cat->add())
			{
				$query = sprintf("DELETE FROM %scategory_group WHERE  id_category = %d",
														_DB_PREFIX_,
														$cat->id);
				Db::getInstance()->execute($query);
				$query = sprintf("INSERT INTO %scategory_group (id_category,id_group) VALUES (%d,%d)",
														_DB_PREFIX_,
														$cat->id,
														$id_group);
				Db::getInstance()->execute($query);
				return $cat;
			}
		} else throw new Exception("Undefined default category");
		return null;
	}
	
	public static function getCategory($id_customer,$create=true)
	{
		if ($grp = self::getGroup($id_customer,$create))
		{
			return self::getGroupCategory($grp->id,$create);
		}
		return null;
	}
	
	
}