<?php
class AcessoCrudController extends Fgsl_Crud_Controller_Abstract
{
	public function init()
	{
		parent::init();
		Zend_Loader::loadClass('Acesso');

		$this->_useModules = true;
		$this->_uniqueTemplatesForApp = false;
		$this->_model = new Acesso();
		$this->_title = 'Cadastro de Acessos';
		$this->_searchButtonLabel = 'Pesquisar';
		$this->_searchOptions = array('recurso'=>'Recurso');
		$this->_config();
	}
}