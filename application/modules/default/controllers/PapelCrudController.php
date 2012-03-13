<?php
class PapelCrudController extends Fgsl_Crud_Controller_Abstract
{
	public function init()
	{
		parent::init();
		Zend_Loader::loadClass('Papel');

		$this->_useModules = true;
		$this->_uniqueTemplatesForApp = false;
		$this->_model = new Papel();
		$this->_title = 'Cadastro de Papéis';
		$this->_searchButtonLabel = 'Pesquisar';
		$this->_searchOptions = array('nome'=>'Nome');
		$this->_config();
	}
}