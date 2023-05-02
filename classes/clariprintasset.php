<?php

class ClariprintAsset extends ObjectModel
{
	public $id;
	public $uuid;
	public $name;
	public $kind;
	public $json;
	public $date_add;
	public $date_upd;

	public static $definition = [
		'table' => 'clariprint_asset',
		'primary' => 'id_clariprint_asset',
		'multilang' => false,
		'fields' => [
			// Champs Standards
			'name' => ['type' => self::TYPE_STRING, 'validate' => '', 'size' => 255, 'required' => true],
			'uuid' => ['type' => self::TYPE_STRING, 'validate' => '', 'size' => 255, 'required' => false],
			'kind' => ['type' => self::TYPE_STRING, 'validate' => '', 'size' => 255, 'required' => true],
			'json' => ['type' => self::TYPE_STRING, 'validate' => '', 'required' => false],
			'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false],
			'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false]
		],
	];


	static function loadAsset($uuid)
	{
		$x = explode("|",$uuid);
		$name= $x[0];
		$uuid = ((count($x) > 1) ? $x[1] : null);
		if ($uuid)
			$res = Db::getInstance()->executeS(sprintf("SELECT kind,json,name FROM %sclariprint_asset WHERE uuid = '%s' ",_DB_PREFIX_,$uuid));
		else {
			$res = Db::getInstance()->executeS(sprintf("SELECT kind,json,name FROM %sclariprint_asset WHERE name = '%s' OR uuid = '%s'",_DB_PREFIX_,$name,$name));
				}
		return array_pop($res);
	}

	static function buildLibraryFor($uuid)
	{
		$id = null;
		$library = [
			"require" => [$uuid],
			"templates" => [],
			"groups" => [],
			"layouts" => [],
			"scenes" => [],
			"finalCut" => []
		];
		$loaded = [];
		$toload = [$uuid];
		while(count($toload)) {
			$r = array_pop($toload);
			if (is_string($r))
			{
				$x = explode("|",$r);
				$name= $x[0];
				$uuid = ((count($x) > 1) ? $x[1] : null);
				if (in_array($r, $loaded)) break;
				if ($asset = self::loadAsset($r))
				{
					$r_obj = json_decode($asset['json']);
					if (!$id) $id = $r_obj->name;
					switch ($asset["kind"]) {
						case 'TEMPLATE': $library['templates'][] = $r_obj; break;
						case 'GROUP': $library['groups'][] = $r_obj; break;
						case 'LAYOUT': $library['layouts'][] = $r_obj; break;
						case 'SCENE': $library['scenes'][] = $r_obj; break;
						default: 
							echo " No kind !";
							break;
					}
					if(isset($r_obj->require))
					{
						if (is_array($r_obj->require))
						{
							foreach($r_obj->require as $r)
							{
								$toload[] = $r;
							}
						} else  $toload[] = $r_obj->require;
					}
				}
			}
		}
		return ['id' => $id, 'library' => $library];
	}
}

