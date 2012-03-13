<?php

class MarcaCrudController extends Fgsl_Crud_Controller_Abstract
	{

	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('Marca');
    $this->_useModules = true;
    $this->_uniqueTemplateForApp = false;
    $this->_model = new Marca();
    $this->_title = 'Cadastro de Marcas';
    $this->_searchButtonLabel = 'Pesquisar';
    $this->_searchOptions = array('mrc_id' => 'ID');
    $this->_config();
    }
	}

