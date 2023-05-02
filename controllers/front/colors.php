<?php

class ClariprintColorsModuleFrontController extends ModuleFrontController
{
	var $colors = null;
	
	function colors() {
		if ($this->colors == null) {
			$this->colors = array();
			

			$this->colors['coated-pms'] = array(
				'coat' => 'coated',
				'class' => 'PMS',
				'colors' => array_merge(
							json_decode(file_get_contents(__DIR__.'/../../js/colorly/json/pantone-solid-coated.json')),
							json_decode(file_get_contents(__DIR__.'/../../js/colorly/json/pantone-p-solid-coated.json'))));

			$this->colors['uncoated-pms'] = array(
				'coat' => 'uncoated',
				'class' => 'PMS',
				'colors' => array_merge(
							json_decode(file_get_contents(__DIR__.'/../../js/colorly/json/pantone-solid-uncoated.json')),
							json_decode(file_get_contents(__DIR__.'/../../js/colorly/json/pantone-p-solid-uncoated.json'))));


			$this->colors['coated-metallic'] = array(
				'coat' => 'coated',
				'class' => 'Metal',
				'colors' => array_merge(
							json_decode(file_get_contents(__DIR__.'/../../js/colorly/json/pantone-p-metallic-coated.json')),
							json_decode(file_get_contents(__DIR__.'/../../js/colorly/json/pantone-p-premium-metallics-coated.json'))));

			$this->colors['coated-neons'] = array(
				'coat' => 'coated',
				'class' => 'Spot',
				'colors' => json_decode(file_get_contents(__DIR__.'/../../js/colorly/json/pantone-p-pastels-neons-coated.json')));

			$this->colors['uncoated-neons'] = array(
				'coat' => 'uncoated',
				'class' => 'Spot',
				'colors' => json_decode(file_get_contents(__DIR__.'/../../js/colorly/json/pantone-p-pastels-neons-uncoated.json')));
			
		}
		return $this->colors;
	}
	
	public function displayAjaxSearch() {
		$kind = Tools::getValue('colorkind');
		$c = Tools::getValue('q');
		$c = strtolower(trim($c));
		$c = str_replace(' ','-',$c);
		$coat = null;
		if (substr($c,-1) == 'u') {
			$c = substr($c,0,-1);
			$coat = 'uncoated';
		} else if (substr($c,-1) == 'c') {
			$c = substr($c,0,-1);
			$coat = 'coated';
		}
		if (substr($c,-1) == '-') $c = substr($c,0,-1);
		$results  = array();
		$limit = 0;
		foreach($this->colors() as $k =>  $list) {
			if (($coat == null || $list['coat'] == $coat) && ($kind == null || $list['class'] == $kind))
			{
				// echo "search !!!/n";
				foreach($list['colors'] as $pms) {
					if (preg_match('/'.$c.'/i',$pms->name)) {
						$results[] = $pms;
						$limit++;

						// if ($limit > 30) break; 
					} // else echo $pms->name."\n";
				}
			} // else echo "$k |  $coat != " . $list['coat'] . " &&  ".  $list['class'] ." != $kind \n" ;
		}
		echo Tools::jsonEncode($results);
	}
	
	public function checkColor($c,$kind = null) {
		$c = strtolower(trim($c));
		$c = str_replace(' ','-',$c);
		$coat = null;
		if (substr($c,-1) == 'u') {
			$c = substr($c,0,-1);
			$coat = 'uncoated';
		} else if (substr($c,-1) == 'c') {
			$c = substr($c,0,-1);
			$coat = 'coated';
		}
		if (substr($c,-1) == '-') $c = substr($c,0,-1);
		
		foreach($this->colors() as $list) {
			if (($coat == null | $list['coat'] == $coat) & ($kind == null | $list['class'] == $kind))
			{
				foreach($list['colors'] as $pms) {
					if ($pms->label == $c) {
						$pms->coat = $list['coat'];
						$pms->class = $list['class'];
						return $pms;
					}
				}
			}
		}
		return null;
	}
	
	public function displayAjaxColorcheck()
	{
		$res = array();
		$res['success'] = false;
		if (Tools::getValue('color') != '') {
			
			if ($val = $this->checkColor(Tools::getValue('color'),Tools::getValue('kind',null)))
			{
				$res['success'] = true;
				$val->contrast = (hexdec($val->hex) > 0xffffff/2) ? '000000' : 'ffffff';
				$res['color'] = $val;
				
			}
		}
		die(Tools::jsonEncode($res));
	}
}
