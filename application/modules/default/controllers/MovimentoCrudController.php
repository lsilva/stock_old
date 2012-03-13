<?php

class MovimentoCrudController extends Fgsl_Crud_Controller_Abstract
	{
  public $_tipo = '';
	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('MovimentoHistorico');
    Zend_Loader::loadClass('Produto');
    $this->_useModules = true;
    $this->_uniqueTemplateForApp = true;
    $this->_model = new MovimentoHistorico();
    $this->_modelProduto = new Produto();
    $this->_title = 'Movimentação';
    $this->_searchButtonLabel = 'Pesquisar';
    $this->_searchOptions = array('mov_sequencia' => 'Sequencia');
		$this->_tipo = Fgsl_Session_Namespace::get('get')->tipo;
    $this->_config();
    }

	public function insertAction()
	  {
    $id = Fgsl_Session_Namespace::get('post')->mov_id;
    $data = $this->_getDataFromPost();
    $dataProduto = $this->_getDataFromPost($this->_modelProduto->getFieldNames());
    $action = empty($id) ? 'insert' : 'edit';

    if(empty($this->_tipo))
      $this->_tipo = 'ent';

		$module = $this->_useModules ? "{$this->_moduleName}/" : '';

		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save?tipo={$this->_tipo}",
		Fgsl_Form_Edit::MODEL => $this->_model
		);

		$optionsProduto = array(
		Fgsl_Form_Edit::DATA => $dataProduto,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save?tipo={$this->_tipo}",
		Fgsl_Form_Edit::MODEL => $this->_modelProduto
		);

		$this->view->assign('form2', new Fgsl_Form_Edit($options));
		$this->view->assign('formProduto', new Fgsl_Form_Edit($optionsProduto));
		$this->view->assign('action', $action);
		$this->view->assign('tipo', $this->_tipo);
		$this->_response->setBody($this->view->render($this->_controllerAction.'/insert.phtml'));
	  }

	public function saveAction()
		{
		$this->redirect = false;
		$this->return_ajax = true;
		parent::saveAction();
		$this->_redirect($this->getRequest()->getModuleName());
		}

	public function listAction()
  	{
  	$this->linksPersonalizados["insertLink"] = $this->getUrl().'/insert?tipo='.$this->_tipo;
  	parent::listAction();
  	}
	}