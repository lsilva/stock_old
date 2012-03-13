<?php

class UnidadeCrudController extends Fgsl_Crud_Controller_Abstract
	{

	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('Unidade');
    $this->_useModules = true;
    $this->_uniqueTemplateForApp = false;
    $this->_model = new Unidade();
    $this->_title = 'Cadastro de Unidades';
    $this->_searchButtonLabel = 'Pesquisar';
    $this->_searchOptions = array('und_id' => 'ID');
    $this->_config();
    }
	}

