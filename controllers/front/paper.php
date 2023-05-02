<?php

class ClariprintPaperModuleFrontController extends ModuleFrontController
{

	public function initContent()
	{
		parent::initContent();
		if (Tools::isSubmit('ajax') && Tools::getValue('action') == 'paperselector')
		{
			$val = array(
				'qualities' => array(),
				'brands' => array(),
				'colors' => array(),
				'weights' => array());
			
			$options=array(	'sequential' => Tools::getValue('sequential'),
							'process' => Tools::getValue('process'),
							'processes' => Tools::getValue('processes'),
							'quality' => Tools::getValue('quality'),
							'weight' => Tools::getValue('weight'),
							'brand' => Tools::getValue('brand'),
							'color' => Tools::getValue('color'),
							'iso' => Tools::getValue('iso'),
							'label' => Tools::getValue('label'),

							'qualitiesFilter' => Tools::getValue('qualitiesFilter'),
							'weightsFilter' => Tools::getValue('weightsFilter'),
							'brandsFilter' => Tools::getValue('brandsFilter'),
							'colorsFilter' => Tools::getValue('colorsFilter')
						
						);
			if (false & Tools::getValue('sequential'))
				{
					$val = array();
					$options=array(	'process' => Tools::getValue('process'),
									'processes' => Tools::getValue('processes'),
									'iso' => Tools::getValue('iso'),
									'label' => Tools::getValue('label'),
									'brand' => Tools::getValue('brand'),
									'color' => Tools::getValue('color'),
									'quality' => Tools::getValue('quality'),
									'qualitiesFilter' => Tools::getValue('qualitiesFilter'),
									'weightsFilter' => Tools::getValue('weightsFilter'),
									'brandsFilter' => Tools::getValue('brandsFilter'),
									'colorsFilter' => Tools::getValue('colorsFilter')
						
								);
					$val['brands'] = ClariprintPaper::brands($options);
					$val['qualities'] = ClariprintPaper::qualities($options);
//					$options['quality'] = Tools::getValue('quality');
					if (Tools::getValue('brand'))
					{
					//	if (array_search($options['quality'],$val['qualities']) == FALSE)
					//		$options['quality'] = '';
					}
						
					$val['weights'] = ClariprintPaper::weights($options);
					$options['weight'] = Tools::getValue('weight');
					if (array_search($options['weight'],$val['weights']) == FALSE)
					{	if (count($val['weights']) > 0)
							$options['weight'] = $val['weights'][0];}

					$val['colors'] = ClariprintPaper::colors($options);
//					$val['brands'] = ClariprintPaper::brands($options);
					
				} else 	{
			$val = array(

				'qualities' => ClariprintPaper::qualities($options),
				'brands' => ClariprintPaper::brands($options),
				'colors' => ClariprintPaper::colors($options),
				'weights' => ClariprintPaper::weights($options),
				null);
			}
			if (Tools::getValue('initpapers') == "1")
			{
				$options['all'] = true;
				if (Tools::getValue('onpaperadmin')) {
					$options['qualitiesFilter'] = array();
					$options['weightsFilter'] = array();
					$options['brandsFilter'] = array();
					$options['colorsFilter'] = array();
				}
				$val['allqualities'] = ClariprintPaper::qualities($options);
				$val['allbrands'] = ClariprintPaper::brands($options);
				$val['allcolors'] = ClariprintPaper::colors($options);
				$val['allweights'] = ClariprintPaper::weights($options);
			}
			$errs = array();
			
			if ($v = Tools::getValue('quality'))
			{
				if (array_search($v,$val['qualities']) === FALSE) $errs[] = $this->l('Quality is not available for this weight, brand or color');
			} else $errs[] = $this->l('Quality is required');

			if (Tools::getValue('brand'))
			{
				if (array_search(Tools::getValue('brand'),$val['brands']) === FALSE) $errs[] = $this->l('Brand not available');
			}
			
			if (Tools::getValue('weight'))
			{
				if (array_search(Tools::getValue('weight'),$val['weights']) === FALSE) $errs[] = $this->l('Weight not available');
			} else $errs[] = $this->l('Weight is required');

			if (Tools::getValue('color'))
			{
				if (array_search(Tools::getValue('color'),$val['colors']) === FALSE) $errs[] = $this->l('Color not available');
			}

			$val['status'] = (count($errs) == 0 ? 'ok' : 'error');
			$val['errors'] = implode(' ',$errs);
			

			if (defined('_PS_MODE_DEV_'))
			{
				global $DBG_SQL;
				$val['sql'] = $DBG_SQL;
			}
			die(Tools::jsonEncode($val));
		} else if (Tools::isSubmit('ajax') && Tools::getValue('action') == 'search')
		{
			$options = array(	'process' => Tools::getValue('process'),
							'key' => Tools::getValue('q'),
							'iso' => Tools::getValue('iso'),
							'label' => Tools::getValue('label'),

							'qualitiesFilter' => Tools::getValue('qualitiesFilter'),
							'weightsFilter' => Tools::getValue('weightsFilter'),
							'brandsFilter' => Tools::getValue('brandsFilter'),
							'colorsFilter' => Tools::getValue('colorsFilter'));
			$res = ClariprintPaper::search($options);
			if (defined('_PS_MODE_DEV_'))
			{
				global $DBG_SQL;
				$res[] = $DBG_SQL;
			}
			die(Tools::jsonEncode($res));
			
		}
	}
	
}
