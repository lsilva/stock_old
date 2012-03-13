<?php

class ContatoCrudController extends Fgsl_Crud_Controller_Abstract
	{

	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('Contato');
    $this->_useModules = true;
    $this->_uniqueTemplateForApp = false;
    $this->_model = new Contato();
    $this->_title = 'Cadastro de meus Contatos';
    $this->_searchButtonLabel = 'Pesquisar';
    $this->_searchOptions = array('cnt_id' => 'ID');
    $this->_config();
    }

	public function editAction()
	  {
		$module = $this->_useModules ? "{$this->_moduleName}/" : '';
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		$data = $this->_model->getDataByCliId($dataAuth->cli_id);
		if(!$data)
			{
			$message = "Não existe registro cadastrado para este usuário.";
			Fgsl_Session_Namespace::set('mensagem',$message);
			$this->_redirect('index');
			}

		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save",
		Fgsl_Form_Edit::MODEL => $this->_model
		);

//		$js = $this->_getJs();
	  $form = new Fgsl_Form_Edit($options);
    $form->setAttrib('onSubmit','return ValidaForm(this);');
	//  $this->view->assign('js', $js);
	  $this->view->assign('form', $form);//
	  }
	}

