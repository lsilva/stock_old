<?php

class EnderecoCrudController extends Fgsl_Crud_Controller_Abstract
	{

	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('Endereco');
    $this->_useModules = true;
    $this->_uniqueTemplateForApp = false;
    $this->_model = new Endereco();
    $this->_title = 'Cadastro de Endereços';
    $this->_searchButtonLabel = 'Pesquisar';
    $this->_searchOptions = array('end_id' => 'ID');
    $this->_config();
    }
	}

