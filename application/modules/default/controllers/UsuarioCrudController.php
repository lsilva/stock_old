<?php
class UsuarioCrudController extends Fgsl_Crud_Controller_Abstract
	{
	public function init()
    {
    parent::init();
    Zend_Loader::loadClass('WebUser');
    $this->_useModules = true;
    $this->_uniqueTemplateForApp = true;
    $this->_model = new WebUser();
    $this->_title = 'Alterar senha';
    $this->_searchButtonLabel = 'Pesquisar';
    $this->_searchOptions = array('web_id' => 'ID');
    $this->_config();
    }

	public function editAction()
	  {
		$module = $this->_useModules ? "{$this->_moduleName}/" : '';
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		$data = $this->_model->getData($dataAuth->web_id);
		if(!$data)
			{
			$message = "Usuario n�o encontrado.";
			Fgsl_Session_Namespace::set('message',$message);
			$this->_redirect('index/logout');
			}

		$data['web_new_pass'] = '';
		$data['web_pass_re'] = '';

		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save",
		Fgsl_Form_Edit::MODEL => $this->_model
		);

		$js = $this->_getJs();
	  $form = new Fgsl_Form_Edit($options);
    $form->setAttrib('onSubmit','return ValidaForm(this);');
	  $this->view->assign('js', $js);
	  $this->view->assign('form', $form);//
	  }

	public function _getJs()
		{
		$js = "
		<script type='text/javascript'>
			function ValidaForm(frm)
				{
				var new_pass = document.getElementById('web_new_pass');
				var pass_re = document.getElementById('web_pass_re');

				if(new_pass.value=='')
					{
					alert('N�o houve altera��o!');
					return false;
					}

				if(new_pass.value != pass_re.value)
					{
					alert('Senhas n�o s�o iguais!');
					return false;
					}
				return false;
				}
		</script>
			";
		return $js;
		}
	}

