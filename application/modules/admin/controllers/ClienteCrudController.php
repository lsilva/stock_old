<?php

class Admin_ClienteCrudController extends Fgsl_Crud_Controller_Abstract
	{

	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('Cliente');
    $this->_useModules = true;
    $this->_uniqueTemplateForApp = false;
    $this->_model = new Cliente();
    $this->_title = 'Cadastro de Clientes';
    $this->_searchButtonLabel = 'Pesquisar';
    $this->_searchOptions = array('cli_id' => 'ID');
    $this->_config();
    }

	public function insertAction()
	  {
	  $get = Fgsl_Session_Namespace::get('get');
		//$pes = $get->pes;//
	  $pes = 'pj';
	  $action = '?pes='.$pes;
	  $fields = $this->_getDataFromPost();
	  foreach($fields as $key => $value)
		  {
		  $remov = ($pes=='pj'?'pf':'pj');
		  $pos = strrpos($key, "_{$remov}_");
			if ($pos !== false)
				$this->_model->addLockedField($key);
		  }
		$module = $this->_useModules ? "{$this->_moduleName}/" : '';

		$data = $this->_getDataFromPost();

		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save".$action,
		Fgsl_Form_Edit::MODEL => $this->_model
		);

		$js = '<script type="text/javascript" src="'.BASE_URL.'/public/js/dojo/dojo/dojo.js"></script>';

	  $form = new Fgsl_Form_Edit($options);
	  $this->view->assign('js', $js);
	  $this->view->assign('form', $form);
		$this->view->render('insert.phtml');
	  }

	public function saveAction()
		{
		$get = Fgsl_Session_Namespace::get('get');
		$this->_model->setPes($get->pes);
		parent::saveAction();
		}
	}

