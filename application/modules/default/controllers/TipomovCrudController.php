<?php

class TipomovCrudController extends Fgsl_Crud_Controller_Abstract
  {

	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('MovimentoTipo');
    $this->_useModules = true;
    $this->_uniqueTemplatesForApp = true;
    $this->_privateTemplates = true;
    $this->_model = new MovimentoTipo();
    $this->_title = 'Cadastro tipo de movimento';
    $this->_searchButtonLabel = 'Pesquisar';
    $this->_searchOptions = array('pro_id' => 'ID');
    $this->_config();
    }

	/**
	 * Shows insert form
	 * (non-PHPdoc)
	 * @see Crud/Controller/Fgsl_Crud_Controller_Interface#insertAction()
	 */
	public function insertAction()
	  {
		$module = $this->_useModules ? "{$this->_moduleName}/" : '';
		$data = $this->_getDataFromPost();

    $id = Fgsl_Session_Namespace::get('post')->tmv_id;
    $action = empty($id) ? 'insert' : 'edit';

		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save",
		Fgsl_Form_Edit::MODEL => $this->_model
		);

		$this->view->assign('form2', new Fgsl_Form_Edit($options));
		$this->view->assign('action', $action);
		$this->_response->setBody($this->view->render($this->_controllerAction.'/insert.phtml'));
	  }

	/**
	 *
	 * (non-PHPdoc)
	 * @see Crud/Controller/Fgsl_Crud_Controller_Interface#editAction()
	 */
	public function editAction()
	  {
		$fieldKey = $this->_model->getFieldKey();
		$record = $this->_model->fetchRow("{$fieldKey} = {$this->_getParam($fieldKey)}");

		$_POST = array();

		foreach ($this->_fieldNames as $fieldName)
		{
			if (isset($record->$fieldName))
				$_POST[$fieldName] = $record->$fieldName;
		}
		$_POST["tmv_estorno"] = ($_POST["tmv_estorno"]=="S")?"1":"0";
		$_POST["tmv_customedio"] = ($_POST["tmv_customedio"]=="S")?"1":"0";
		$_POST["tmv_custoatual"] = ($_POST["tmv_custoatual"]=="S")?"1":"0";

		Fgsl_Session_Namespace::set('post',new Zend_Filter_Input(null,null,$_POST));

		$this->_forward('insert');
	  }

	public function saveAction()
	  {
	  $this->return_ajax = true;
	  parent::saveAction();
	  }
  }

