<?php



class AdminClariprintImportControllerCore extends AdminImportControllerCore {
	
	public function __construct()
	{
		parent::__construct();
		$this->entities = array(
			'ClariprintPaper');
			
}
