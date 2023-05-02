<?php

define('MAX_LINE_SIZE', 0);
require_once dirname(__FILE__) .'/../../classes/clariprintproductprocess.php';

class AdminClariprintDownloadController extends ModuleAdminController
{
	protected $display_header = false;
	protected $display_footer = false;

	public $content_only = true;

	protected $template;
	public $filename;

	public function postProcess()
	{
		if ($id_attachment = (int)Tools::getValue('process'))
			$this->process = (int)Tools::getValue('process'));
		else 
			die(Tools::displayError('Unknown attachment.'));
			
	}

	public function display()
	{	
		header('Content-Description: File Transfer');
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename='.$this->l('Dossier_de_frabiication_').'.pdf';
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		$content = ClariprintProductProcess::pdfForProduct($this->process);

		header('Content-Length: ' . strlen($content));
		ob_clean();
		flush();
		echo $content;
	}
}

