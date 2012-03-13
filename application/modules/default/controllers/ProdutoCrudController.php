<?php

class ProdutoCrudController extends Fgsl_Crud_Controller_Abstract
	{

	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('Produto');
    $this->_useModules = true;
    $this->_uniqueTemplatesForApp = true;
    $this->_privateTemplates = true;
    $this->_model = new Produto();
    $this->_title = 'Cadastro de Produtos';
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

    $id = Fgsl_Session_Namespace::get('post')->pro_id;
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

		Fgsl_Session_Namespace::set('post',new Zend_Filter_Input(null,null,$_POST));

		$this->_forward('insert');
	}

	public function saveAction()
	{
	  $this->return_ajax = true;
	  parent::saveAction();
	}

	public function getnomeAction()
	{
	  $this->_helper->layout->disableLayout();
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data no passado
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // sempre modificado
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Pragma: no-cache"); // HTTP/1.0
    header("Content-Type: application/json; charset=iso-8859-1");
var_dump($_REQUEST);exit;
    $select = $this->_model->getAdapter()->select();
    $select->from($this->_model->getName(),array("pro_id","pro_nome"));
    if($where!="")
      $select->where($where);
    $record = $this->_model->fetchAllAsArray($select);

	  die(Zend_Json::encode($record));
	}
	}

