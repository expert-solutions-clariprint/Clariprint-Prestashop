<?php

class AdminClariprintServersController extends ModuleAdminController
{

	public function __construct()
	{
		$this->table = 'clariprint_server';
		$this->identifier = 'id';
		$this->bootstrap = true;
		$this->className = 'ClariprintServer';
		$this->lang = false;
		$this->addRowAction('edit'); 
		$this->addRowAction('view');
		$this->addRowAction('delete');
		$this->addRowAction('select');

		$this->allow_export = true;
		$this->deleted = false;
		$this->context = Context::getContext();
		$this->_select = 'gp.name as groupname';
		$this->_join = sprintf('LEFT JOIN %sgroup_lang as gp ON (a.group_id = gp.id_group AND gp.id_lang = %d)',_DB_PREFIX_,(int)$this->context->language->id);
		$this->_orderBy = 'a.name';
		$this->_orderWay = 'DESC';
		$this->_where = '';
		parent::__construct();

		$this->fields_list = array(
			'id' => array(
				'title' => $this->trans('ID', array(),'Admin.Clariprint'), // $this->l('#'),
				'align' => 'left',
//				'width' => 65
			),
			 'name' => array(
				'title' => $this->trans('Name', array(), 'Admin.Clariprint'),
				'align' => 'left',
//				'width' => 65
			),
			'groupname' => array(
				'title' => $this->trans('Group', array(), 'Admin.Clariprint'),
				'align' => 'left',
//				'width' => 65
			),
			'url' => array(
				'title' => $this->trans('URL', array(), 'Admin.Clariprint'),
				'align' => 'left'
			),
			'login' => array(
				'title' => $this->trans('Login', array(), 'Admin.Clariprint'),
				'align' => 'left',
//				'width' => 65
			),
			'key' => array(
				'title' => $this->trans('Key', array(), 'Admin.Clariprint'),
				'align' => 'left',
//				'width' => 65
			),
			'mode' => array(
				'title' => $this->trans('Mode', array(), 'Admin.Clariprint'),
				'align' => 'left',
//				'width' => 65
			) 
		); 
	}
	/*
	public function initToolbar()
	{
		return parent::initToolbar();
	}
*/
	public function renderForm()
	{
		
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('New Clariprint Server'),
				'image' => '../img/t/AdminAttachments.gif'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Name:'),
					'name' => 'name',
					'size' => 33,
					'required' => false,
					'lang' => false,
				),
				array(
					'type' => 'select',
					'size' => 1,
					'label' => $this->l('Customer group:'),
					'name' => 'group_id',
					'required' => false,
					'lang' => false,
					'options' => array(
						'query' => Group::getGroups(Context::getContext()->language->id),
						'id' => 'id_group',
						'name' => 'name'
					)
				),
				array(
					'type' => 'text',
					'label' => $this->l('URL:'),
					'name' => 'url',
					'size' => 100,
					'required' => false,
					'lang' => false,
				),
				array(
					'type' => 'text',
					'label' => $this->l('Login:'),
					'name' => 'login',
					'size' => 100,
					'required' => false,
					'lang' => false,
				),
				array(
					'type' => 'password',
					'label' => $this->l('Password:'),
					'name' => 'pass',
					'size' => 100,
					'required' => false,
					'lang' => false,
				),
				array(
					'type' => 'text',
					'label' => $this->l('API key:'),
					'name' => 'key',
					'size' => 100,
					'required' => false,
					'lang' => false,
				),
				array(
					'type' => 'select',
					'size' => 1,
					'label' => $this->l('Mode:'),
					'maxlength' => 1,
					'name' => 'mode',
					'default' => ';',
					'desc' => $this->l('Set Server mode'),
					'options' => array(
						'query' => array(array('key' => 'direct', 'value' => 'Direct'),
										array( 'key' => 'markeplace', 'value' => 'Market Place')),
						'id' => 'key',
						'name' => 'value'
					)
				),
			),
			'submit' => array(
				'title' => $this->l('Save	'),
				'class' => 'button'
			)
		);

		return parent::renderForm();
	}
}
