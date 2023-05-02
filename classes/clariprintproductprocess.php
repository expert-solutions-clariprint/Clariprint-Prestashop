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


class ClariprintProductProcess extends ObjectModel
{
	/** @var integer */
	public $id_produc;

	/** @var integer */
	public $pdf;
		
	static public function createTable()
	{
		/* Delete and re-create the layered categories table */
		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'clariprint_product_process` (
		id int(11) auto_increment primary key,
		id_product int(10) unsigned NOT NULL,
		pdf longblob,
		INDEX (`id_product`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;');
	}

	/**
	* @see ObjectModel::$definition
	*/
	public static $definition = array(
		'table' => 'clariprint_product_process',
		'primary' => 'id',
		'multilang' => FALSE,
		'fields' => array(
			'id_product' => array('type' => self::TYPE_INT),
			'pdf' => array('type' => self::TYPE_NOTHING)));

	public static function idForProduct($id_product)
	{
		$db = Db::getInstance(_PS_USE_SQL_SLAVE_);
		return $db->getValue(sprintf(
			'SELECT id
			FROM %sclariprint_product_process
			WHERE id_product = %d', _DB_PREFIX_,$id_product));
	}

	public static function pdfForProduct($id_product)
	{
		$db = Db::getInstance(_PS_USE_SQL_SLAVE_);
		$query = sprintf(
			'SELECT pdf
			FROM %sclariprint_product_process
			WHERE id_product = %d', _DB_PREFIX_,$id_product);
		return $db->getValue($query);
	}

	public static function addPdfToProduct($pdf,$id_product)
	{
		$db = Db::getInstance(_PS_USE_SQL_SLAVE_);
		$pdf_escaped = $db->_escape($pdf);
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute(sprintf(
			'INSERT INTO %sclariprint_product_process (id_product,pdf) VALUES (%d,\'%s\') ON DUPLICATE KEY UPDATE pdf = \'%s\'',
					_DB_PREFIX_,$id_product,$pdf_escaped,$pdf_escaped));
	}

}