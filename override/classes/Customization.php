<?php
/**
 * 2018 EXPERT SOLUTION
 *
 *
 * @author    EXPERT SOLUTION SARL <contact@clariprint.com>
 * @copyright 2007-2017 Expert Solutions SARL
 * @license   Proprietary
 */

/**
 * Class CustomizationCore
 */
class Customization extends CustomizationCore
{
	public static function getSupplierPrice($idCustomization)
	{
		if (!(int) $idCustomization) {
			return 0;
		}
		return (float) Db::getInstance()->getValue('SELECT SUM(`supplier_price`) FROM `'._DB_PREFIX_.'customized_data`
			WHERE `id_customization` = '.(int) $idCustomization
		);
	}
}

