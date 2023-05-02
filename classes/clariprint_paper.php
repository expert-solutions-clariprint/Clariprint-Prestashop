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

class ClariprintPaper extends ObjectModel
{
	/** @var integer */
	public $id_paper;

	/** @var integer */
	public $id_shop;
		
	/** @var integer */
	public $quality;
	
	/** @var string */
	public $brand;

	/** @var string */
	public $color;

	/** @var string */
	public $weight;

	/** @var string */
	public $thickness;

	/** @var string */
	public $recycled;

	/** @var string */
	public $fsc;

	/** @var string */
	public $fsc_mixed;

	/** @var string */
	public $fsc_recycled;

	public $pefc;
	public $pefc_70;
	public $pefc_recycled;
	public $ecolabel;
	public $blue_angel;
	public $nordic_swan;
	public $apur;
	public $paper_by_nature;
	public $process_of;
	public $process_or;
	public $process_oc;
	public $process_n;
	public $process_h;
	public $process_f;
	public $process_s;

	public $process_o;
	public $process_ofuv;

	public $reseller;
	public $factory_stock;
	public $factory;


	/** @var string */
//	public $product_xml;

	static public function createTable()
	{
		/* Delete and re-create the layered categories table */
		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'clariprint_paper` (
		id_paper int(10) unsigned NOT NULL auto_increment,
		id_shop int(10) unsigned default 1,
		quality varchar(100) default \'\',
		brand varchar(100) default \'\',
		color varchar(100) default \'\',
		weight float default 0.0,
		thickness float default 0.0,
		recycled bool default 0,
		fsc bool default 0,
		fsc_mixed bool default 0,
		fsc_recycled bool default 0,
		pefc bool default 0,
		pefc_70 bool default 0,
		pefc_recycled bool default 0,
		ecolabel bool default 0,
		blue_angel bool default 0,
		nordic_swan bool default 0,
		apur bool default 0,
		paper_by_nature bool default 0,
		process_of bool default 0,
		process_or bool default 0,
		process_oc bool default 0,
		process_n bool default 0,
		process_h bool default 0,
		process_f bool default 0,
		process_s bool default 0,
		reseller bool default 0,
		factory_stock bool default 0,
		factory bool default 0,
		iso varchar(100),

		PRIMARY KEY (`id_paper`),
		KEY `clariprint_paper_key` (`id_shop`,`quality`,`brand`,`color`,`recycled`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;');
	}
	
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'clariprint_paper',
		'primary' => 'id_paper',
		'multilang' => FALSE,
		'fields' => array(
//			'id_paper' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => TRUE),

			'id_shop' => 		array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
		
			/** @var integer */
			'quality' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
	
			/** @var string */
			'brand' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),

			/** @var string */
			'color' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),

			/** @var string */
			'weight' => array('type' => self::TYPE_FLOAT),
			
			/** @var string */
			'thickness' => array('type' => self::TYPE_FLOAT),

			/** @var string */
			'recycled' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),

			/** @var string */
			'fsc' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),

			/** @var string */
			'fsc_mixed' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),

			/** @var string */
			'fsc_recycled' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),

			'pefc' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'pefc_70' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'pefc_recycled' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'ecolabel' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'blue_angel' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'nordic_swan' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'apur' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'paper_by_nature' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'process_of' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'process_or' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'process_oc' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'process_n' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'process_h' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'process_f' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'process_s' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'process_o' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'process_ofuv' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'reseller' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'factory_stock' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'factory' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'iso' => array('type' => self::TYPE_STRING, 'validate' => 'isString')
		));

	public static function cleanup($shop_id,$but) {
		Db::getInstance()->query(sprintf('DELETE FROM `%sclariprint_paper` where id_shop=%d AND NOT id_paper IN (%s)',_DB_PREFIX_,$shop_id,implode(',',$but)));
	}

	public static function qualities($options=null) { return self::select('quality',$options); }
	public static function weights($options=null) { return self::select('weight',$options); }
	public static function colors($options=null) { return self::select('color',$options); }
	public static function brands($options=null) { return self::select('brand',$options); }

	static public function sqlArray($sql)
	{
		$res = array();
		$db = Db::getInstance();
		if($r = $db->query($sql))
		{
			while ($row = $db->nextRow($r))
			{
					$res[] = array_shift($row);
			}
		} 
		return $res;
	}
	public static function sqlArrayTrans($sql,$id_lang = null)
	{
		$res = array();
		$db = Db::getInstance();
		
		if($r = $db->query($sql))
		{
			while ($row = $db->nextRow($r))
			{
				$val = array_shift($row);
				$txt = Context::getContext()->getTranslator()->trans($val);
				if ($txt == '') $txt = $val;
				$res[] = array('value' => $val, 'txt' => $txt);
			}
		} 
		return $res;
	}
	static public function executeS($sql)
	{
		$db = Db::getInstance();
		return $db->executeS($sql);
	}

	private static function sqlArrayString($arr)
	{
		$db = Db::getInstance();
		$sql = '';
		$sep = '';
		foreach($arr as $a) {
			$sql .= sprintf("%s'%s'",$sep,$db->escape($a)); 
			$sep =  ',';
		}
		return '('.$sql.')';
	}
	private static function sqlArrayFloat($arr)
	{
		$sql = '';
		$sep = '';
		foreach($arr as $a) {
			$sql .= sprintf("%s%f",$sep,$a); 
			$sep =  ',';
		}
		return '('.$sql.')';
	}
	
	static public function isoFilter($val)
	{
		switch ($val) {
			case 'coated': return array('Gloss-coated paper','Matt-coated paper','Gloss-coated, web paper');
			case 'uncoated': return array('Uncoated, white paper');
			default: return array($val);
		}
	}
	
	static public function sqlString($v)
	{
		return sprintf("'%s'",$v);
	}
	
	public static function select($prop=null,$options=null,$orderby=null) {

		if ($orderby==null) $orderby = $prop;
		if (!$options) $options = array();
		if (!isset($options['shop'])) $options['shop'] = Context::getContext()->shop->id;
		if ($prop)
			$sql = sprintf('SELECT distinct %s FROM `%sclariprint_paper` where id_shop=%d ',$prop, _DB_PREFIX_,$options['shop']);
		else $sql = sprintf('SELECT * FROM `%sclariprint_paper` where id_shop=%d ', _DB_PREFIX_,$options['shop']);

		if (isset($options['process']))
		{
			if ($options['process'] <> '' && $options['process'] <> 'custom')
				$sql .= sprintf(' AND `process_%s`=1 ',$options['process']);
		}

		if (isset($options['processes']))
		{
			if (is_array($options['processes']))
			{
				$cst = array();
				foreach($options['processes'] as $p) 
				{
					$cst[] = sprintf('`process_%s`=1',$p);
//					$sql .= sprintf(' AND `process_%s`=1 ',$p);
				}
				if (count($cst) > 0)
					$sql .= sprintf('AND (%s)',implode(' OR ',$cst));
			}
		}

		$db = Db::getInstance();
		
		if (!array_key_exists('all',$options)) {

			if (isset($options['quality']) && $prop != 'quality')
				{if ($options['quality'] != '') $sql .= sprintf(' AND `quality`=\'%s\' ',$db->escape($options['quality']));}

			if (isset($options['color']) && $prop != 'color' && $options['color'] != '') 
				{if ($options['color'] != '') $sql .= sprintf(' AND `color`=\'%s\' ',$db->escape($options['color']));}

			if (isset($options['brand']) && $prop != 'brand')
				{if ($options['brand'] != '') $sql .= sprintf(' AND `brand`=\'%s\' ',$db->escape($options['brand']));}

			if (isset($options['weight']) && $prop != 'weight')
				{if ($options['weight'] > 0) $sql .= sprintf(' AND `weight`=%f ',$options['weight']);}

			if (isset($options['label']) && $prop != 'label')
				{if ($options['label'] != '') $sql .= sprintf(' AND `%s`=1 ',$options['label']);}

			if (isset($options['key']))
				{if ($options['key'] != '') 
					$sql .= sprintf(' AND (brand like \'%%%s%%\' OR quality LIKE \'%%%s%%\' OR color LIKE \'%%%s%%\')',
							$options['key'],$options['key'],$options['key']);
			}
		}

		if (isset($options['iso']) && $prop != 'iso')
		{
			if ($options['iso'] != '')
				$sql .= sprintf(' AND `iso` IN (%s) ', implode(',', array_map(array('ClariprintPaper','sqlString'), self::isoFilter($options['iso']))));
		}
		
		if (isset($options['qualitiesFilter']) && is_array($options['qualitiesFilter']))
			{if (count($options['qualitiesFilter']) > 0) $sql .= sprintf(' AND quality IN %s',self::sqlArrayString($options['qualitiesFilter']));}
		if (isset($options['weightsFilter']) && is_array($options['weightsFilter']))
			{if (count($options['weightsFilter']) > 0) $sql .= sprintf(' AND weight IN %s',self::sqlArrayFloat($options['weightsFilter']));}
		if (isset($options['brandsFilter']) && is_array($options['brandsFilter']))
			{if (count($options['brandsFilter']) > 0) $sql .= sprintf(' AND brand IN %s',self::sqlArrayString($options['brandsFilter']));}
		if (isset($options['colorsFilter']) && is_array($options['colorsFilter']))
			{if (count($options['colorsFilter']) > 0) $sql .= sprintf(' AND color IN %s',self::sqlArrayString($options['colorsFilter']));}

		$sql  .= sprintf(' ORDER BY %s',$orderby);
		global $DBG_SQL;
		$DBG_SQL[] = $sql;
		if (Tools::isSubmit('debug')) {

			echo $sql . "<br><hr><br>";
			
		}
		if ($prop) return self::sqlArrayTrans($sql);
		return self::executeS($sql);

	}
	
	public static function importCSV($file)
	{
			
	}
	
	public static function loadDatas()
	{
	}
	
	
	public static function search($options) {
		return self::select(null,$options,'quality,brand,color,weight');
		
	}
}