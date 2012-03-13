<?php

class ClienteCrudController extends Fgsl_Crud_Controller_Abstract
	{

	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('Cliente');
    Zend_Loader::loadClass('Contato');
    $this->_useModules = true;
    $this->_uniqueTemplateForApp = true;
    $this->_model = new Cliente();
    $this->_modelContato = new Contato();
    $this->_title = 'Cadastro de Clientes';
    $this->_searchButtonLabel = 'Pesquisar';
    $this->_searchOptions = array('cli_id' => 'ID');
		$this->_model->setPes(Fgsl_Session_Namespace::get('get')->pes);

    $this->_config();
    }

	public function insertAction()
	  {
    $id = Fgsl_Session_Namespace::get('post')->cli_id;
    $action = empty($id) ? 'insert' : 'edit';

    $pes = $this->_model->getPes();
    if(empty($pes) && !empty($id))
      {
      $cpf = Fgsl_Session_Namespace::get('post')->cli_pf_cpf;
      $pes = empty($cpf) ? 'pj' : 'pf';
      }

	  $actionForm = '?pes='.$pes;
  	$data = $this->_addFieldLockedThisForm($pes);
		$module = $this->_useModules ? "{$this->_moduleName}/" : '';

		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save".$actionForm,
		Fgsl_Form_Edit::MODEL => $this->_model
		);

		$this->view->assign('form2', new Fgsl_Form_Edit($options));
		$this->view->assign('formContact', $this->getFormContact());
		$this->view->assign('action', $action);
		$this->view->assign('pes', $pes);
		$this->_response->setBody($this->view->render($this->_controllerAction.'/insert.phtml'));
	  }

	public function saveAction()
		{
		$this->redirect = false;
		$this->return_ajax = true;
		parent::saveAction();
		$this->_redirect($this->getRequest()->getModuleName());
		}

	public function getFormContact()
  	{
    $fieldNames = $this->_modelContato->getFieldNames();
		foreach ($fieldNames as $fieldName)
				$data[$fieldName] = '';

    $module = $this->_useModules ? "{$this->_moduleName}/" : '';

		$options = array(
  		Fgsl_Form_Edit::DATA => $data,
  		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/addContact",
  		Fgsl_Form_Edit::MODEL => $this->_modelContato
		);

  	return new Fgsl_Form_Edit($options);
  	}

	public function addContactAction()
  	{
die("funfo");
  	}

	public function listAction()
  	{
    $pes = $this->_model->getPes();
  	$this->linksPersonalizados["insertLink"] = $this->getUrl().'/insert?pes='.$pes;
  	parent::listAction();
  	}

	public function _addFieldLockedThisForm($pes)
		{
	  $fields = $this->_getDataFromPost();
	  foreach($fields as $key => $value)
		  {
		  $remov = ($pes=='pj'?'pf':'pj');
		  $pos = strrpos($key, "_{$remov}_");
			if ($pos !== false)
				$this->_model->addLockedField($key);
		  }
		return $fields;
		}
	}

