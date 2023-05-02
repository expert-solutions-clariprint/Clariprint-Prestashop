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


class ClariprintMargin extends ObjectModel
{
	/** @var integer */
	public $id_margin;

	/** @var integer */
	public $id_shop;
		
	/** @var integer */
	public $id_group;
	
	/** @var string */
	public $paper;

	/** @var string */
	public $print;

	/** @var string */
	public $makeready;

	/** @var string */
	public $delivery;

	/** @var string */
	public $packaging;

	/** @var string */
	public $validity_start;

	/** @var string */
	public $validity_stop;

	/** @var string */
//	public $product_xml;

	static public function createTable()
	{
		/* Delete and re-create the layered categories table */
		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'clariprint_margins` (
		id_margin int(10) unsigned NOT NULL auto_increment,
		id_shop int(10) unsigned default 1,
		id_group int(10) unsigned default null,
		paper float default 0.0,
		print float default 0.0,
		packaging float default 0.0,
		makeready float default 0.0,
		delivery float default 0.0,
		validity_start date,
		validity_stop date,
		PRIMARY KEY (`id_margin`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;');
	}
	
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'clariprint_margins',
        'primary' => 'id_margin',
        'multilang' => FALSE,
        'fields' => array(
			'id_shop' => 		array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),

			'id_group' => 		array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),

			/** @var float */
			'paper' => array('type' => self::TYPE_FLOAT),
			'print' => array('type' => self::TYPE_FLOAT),
			'makeready' => array('type' => self::TYPE_FLOAT),
			'packaging' => array('type' => self::TYPE_FLOAT),
			'delivery' => array('type' => self::TYPE_FLOAT),

			'validity_start' => array('type' => self::TYPE_DATE,'validate' => 'isDate'),
			'validity_stop' => array('type' => self::TYPE_DATE,'validate' => 'isDate')
        ));


	public static function forCustomer($id_customer)
	{
		$query = sprintf('SELECT * FROM %sclariprint_margins as t_m,  %sgroup as t_g, %scustomer_group as t_cg
			WHERE t_m.id_group = t_g.id_group
			AND t_cg.id_group = t_g.id_group
			AND t_cg.id_customer = %d
			AND t_m.validity_start < NOW()
			AND t_m.validity_stop > NOW()', _DB_PREFIX_,_DB_PREFIX_,_DB_PREFIX_,$id_customer);
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
	}
	public static function forGroup($id_group)
	{
		$query = sprintf('SELECT * FROM %sclariprint_margins as t_m
			WHERE t_m.id_group = %d
			AND t_m.validity_start < NOW()
			AND t_m.validity_stop > NOW()', _DB_PREFIX_,$id_group);
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
	}

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
	
	static function isGroup($group) {
		if (Context::getContext()->customer->isLogged())
		{
			$id_customer = Context::getContext()->customer->id;
			$query = sprintf('SELECT COUNT(*) FROM %scustomer_group as t_cg
				WHERE t_cg.id_group = %d
				AND t_cg.id_customer = %d',
				 _DB_PREFIX_,$group,$id_customer);
			$res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
			return $res[0];
		} else return false;
	}

	static function getProductDiscount($discounts,$quantity,$group) {
		if (self::isGroup($group)) {
			if (is_array($discounts))
			{
				foreach($discounts as $d) {
					$dd = (array)$d;
					if (array_key_exists('value',$d))
						if ($quantity <= (int)$dd['quantity'] ) return (float)$dd['value'];
				}
			}
		}
		return 0;
	}

	static function getProductFixed($discounts,$quantity,$group) {
		if (self::isGroup($group)) {
			if (is_array($discounts))
			{
				foreach($discounts as $d) {
					$dd = (array)$d;
					if (array_key_exists('fixed',$d))
						if ($quantity == (int)$dd['quantity'] ) return (float)$dd['fixed'];
				}
			}
		}
		return -1;
	}

	
	static function globalMargins() {
		return array(
			'paper' => (float)Configuration::get('CL_MARGIN_PAPER') / 100,
			'makeready' =>  (float)Configuration::get('CL_MARGIN_MAKEREADY') / 100,
			'print' =>  (float)Configuration::get('CL_MARGIN_PRINT') / 100,
			'delivery' =>  (float)Configuration::get('CL_MARGIN_DELIVERY') / 100,
			'packaging' => (float)Configuration::get('CL_MARGIN_PACKAGING') / 100);
	}
	
	
	static public function applyBaseMargins($arr) {
		$in = $arr;
		$margins =  self::globalMargins();

		$arr['paper'] = $arr['paper'] * (1 + $margins['paper']);
		$arr['makeready'] = $arr['makeready'] * (1 + $margins['makeready']);
		$arr['print'] = $arr['print'] * (1 + $margins['print']);
		$arr['delivery'] = $arr['delivery'] * (1 + $margins['delivery']);
		$arr['packaging'] = $arr['packaging'] * (1 + $margins['packaging']);
		$arr['total'] = ceil((float)$arr['packaging'] + (float)$arr['delivery'] + (float)$arr['print'] + (float)$arr['makeready'] + (float)$arr['paper'] +  (float)$arr['proof']);
		$arr['base_margins'] = $margins;
		return $arr;
	}

	static function applyForGroup($id_group = 1,$paper,$printing,$makeready,$packaging,$delivery,$proof=0,$discount=0) {
		if ($id_group == null) $id_group = 1; // default : visitor


		if ($discount) {

			$tot = ceil($paper+$printing +$makeready +$delivery + $packaging + $proof);
			$res = array('paper' => $paper,'print' => $printing, 'makeready' => $makeready,'delivery' => $delivery, 'packaging'=> $packaging, 'proof' => $proof, 'total' => ceil($tot));

			$res = self::applyBaseMargins($res);
			
			$max = $res['total'];

			$dp = (100+$discount) / 100;
			$paper = $dp * $res['paper'];
			$printing = $dp * $res['print'];
			$makeready = $dp * $res['makeready'];
			$packaging = $dp * $res['packaging'];
			$delivery = $dp * $res['delivery'];
			$proof = $dp * $res['proof'];
			
			$tot = ceil($paper+$printing +$makeready +$delivery + $packaging + $proof);
			
			$res = array('paper' => $paper,'print' => $printing, 'makeready' => $makeready,'delivery' => $delivery, 'proof' => $proof, 'packaging'=> $packaging, 'total' => ceil($tot));

			$res['discount'] = 100 * $res['total'] / $max - 100;
			$res['group'] = $id_group;
			$res['public'] = $max;
			return $res;
		}

		$tot = ceil($paper+$printing +$makeready +$delivery + $packaging + $proof);

		
		$res = array('paper' => $paper,'print' => $printing, 'makeready' => $makeready,'delivery' => $delivery, 'packaging'=> $packaging, 'proof' => $proof, 'total' => ceil($tot));
		$res = self::applyBaseMargins($res);

		$paper = $res['paper'];
		$printing = $res['print'];
		$makeready = $res['makeready'];
		$delivery = $res['delivery'];
		$packaging = $res['packaging'];
		$proof = $res['proof'];
		$tot = $res['total'];

		$max = ceil($tot);
		$tot *= 100;
		foreach(self::forGroup($id_group) as $m) {
			$n_paper = (1 + $m['paper'] / 100) * $paper;
			$n_printing = (1 + $m['print'] / 100) * $printing;
			$n_makeready = (1 + $m['makeready'] / 100) * $makeready;
			$n_delivery = (1 + $m['delivery'] / 100) * $delivery;
			$n_packaging = (1 + $m['packaging'] / 100) * $packaging;
			$n_tot = ceil($n_delivery + $n_makeready + $n_printing + $n_paper + $proof);
			if ($n_tot < $tot) {
				$res = array('paper' => $n_paper,'print' => $n_printing, 'makeready' => $n_makeready,'delivery' => $n_delivery, 'packaging'=> $n_packaging,
					 'proof' => $proof, 'total' => $n_tot );
			}
			if ($n_tot > $max) $max = $n_tot;
		}
		$res['discount'] = 100 * $res['total'] / $max - 100;
		$res['group'] = $id_group;
		$res['public'] = $max;
		return $res;
	}
	
	static function applyForCustomer($id_customer,$paper,$printing,$makeready,$packaging,$delivery,$proof=0,$discount=0) {
		
		if ($id_customer == null) $id_customer = Context::getContext()->cookie->id_customer;
		if ($discount) {

			$tot = ceil($paper+$printing +$makeready +$delivery + $packaging + $proof);
			$res = array('paper' => $paper,'print' => $printing, 'makeready' => $makeready,'delivery' => $delivery, 'packaging'=> $packaging, 'proof' => $proof, 'total' => ceil($tot));

			$res = self::applyBaseMargins($res);

			$max = ceil($res['total']);

			$dp = (100+$discount) / 100;
			$paper = $dp * $res['paper'];
			$printing = $dp * $res['print'];
			$makeready = $dp * $res['makeready'];
			$packaging = $dp * $res['packaging'];
			$delivery = $dp * $res['delivery'];
			$proof = $dp * $res['proof'];
			$tot = $max * $dp;
//			$tot = ($paper+$printing +$makeready +$delivery + $packaging + $proof);
			
			$res = array('paper' => $paper,'print' => $printing, 'makeready' => $makeready,'delivery' => $delivery, 'packaging'=> $packaging, 'total' => $tot);

			$res['discount'] = 100 * $res['total'] / $max - 100;
			$res['public'] = $max;
			$res['cart_price'] = $max;
			return $res;
		}
		
		$tot = ($paper+$printing +$makeready +$delivery + $packaging + $proof);

		$res = array('paper' => $paper,'print' => $printing, 'makeready' => $makeready,'delivery' => $delivery, 'packaging'=> $packaging, 'proof' => $proof, 'total' => ceil($tot));

		$res = self::applyBaseMargins($res);

		$paper = $res['paper'];
		$printing = $res['print'];
		$makeready = $res['makeready'];
		$delivery = $res['delivery'];
		$packaging = $res['packaging'];
		$proof = $res['proof'];
		$tot = $res['total'];

		$max = ceil($tot);
		$tot *= 100;
		
		foreach(self::forCustomer($id_customer) as $m) {
			$res['customer_margins'] = $m;			
			$n_paper = (1 + $m['paper'] / 100) * $paper;
			$n_printing = (1 + $m['print'] / 100) * $printing;
			$n_makeready = (1 + $m['makeready'] / 100) * $makeready;
			$n_delivery = (1 + $m['delivery'] / 100) * $delivery;
			$n_packaging = (1 + $m['packaging'] / 100) * $packaging;
			$n_tot = ceil($n_delivery + $n_makeready + $n_printing + $n_paper + $proof + $n_packaging);
			if ($n_tot < $tot) {
				$res = array('paper' => $n_paper,'print' => $n_printing, 'makeready' => $n_makeready,'delivery' => $n_delivery, 'packaging'=> $n_packaging, 'proof' => $proof, 'total' => $n_tot );
			}
			if ($n_tot > $max) $max = $n_tot;
		}
		$res['public'] = $max;
		$res['discount'] = 100 * $res['total'] / $max - 100;
		$res['cart_price'] = $max;
		return $res;
	}
}