<?php
class IndexController extends Zend_Controller_Action
{#PROTOCOLO SUPORTE SPEEDY : 204188035
	public function init()# 57515477
		{
		Zend_Loader::loadClass('WebUser');
		Zend_Loader::loadClass('AcessoPapel');
		Zend_Loader::loadClass('PapelFuncionario');
		//$this->_helper->layout->disableLayout();
		}

	public function indexAction()
		{
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		if (!isset($dataAuth))
			$this->_redirect('default/index/pre-login');
		}

	public function preLoginAction()
		{
		$form = new Zend_Form();
		$form->setAction(HTTP_ROOT.'/default/index/login');
		$form->setMethod('post');
		$element = new Zend_Form_Element_Text('web_login');
		$element->setLabel('Usuario');
		$form->addElement($element);
		$element = new Zend_Form_Element_Password('web_pass');
		$element->setLabel('Senha');
		$form->addElement($element);
		$element = new Zend_Form_Element_Submit('login');
		$form->addElement($element);
		$this->view->assign('form',$form);

		$message = Fgsl_Session_Namespace::get('message');
		Fgsl_Session_Namespace::remove('message');
		$this->view->assign('message',$message);
		}

	public function loginAction()
		{
		try
			{
			$post = Fgsl_Session_Namespace::get('post');
			$authAdapter = new Zend_Auth_Adapter_DbTable(WebUser::getDefaultAdapter());
			$authAdapter->setTableName('web_usu');
			$authAdapter->setIdentityColumn('web_login');
			$authAdapter->setCredentialColumn('web_pass');
			$authAdapter->setIdentity($post->web_login);
			//die(WebUser::encrypt($post->web_pass));
			$authAdapter->setCredential(WebUser::encrypt($post->web_pass));
			$resultado = $authAdapter->authenticate();

			if ($resultado->isValid())
			{
				$dataAuth = $authAdapter->getResultRowObject(null,'web_pass');
				Fgsl_Session_Namespace::set('data_auth',$dataAuth);
				Fgsl_Session_Namespace::set('acl',$this->_getAcl($dataAuth->web_id));
			}
			else
			{
				$mensagens = '';
				foreach ($resultado->getMessages() as $mensagem)
				{
					$mensagens .= $mensagem;
				}
				Fgsl_Session_Namespace::set('message',$mensagens);
			}
		}
		catch (Exception $e)
		{
			Fgsl_Session_Namespace::remove('data_auth');
			Fgsl_Session_Namespace::set('message',$e->getMessage());
		}
		$this->_redirect('default');
	}

	public function logoutAction()
	{
		Fgsl_Session_Namespace::remove('data_auth');
		$this->_redirect('default');
	}

	private function _getAcl($matricula)
		{
		$acl = new Zend_Acl();
		$arrRoles = $this->_getArrayRoles($matricula);
		foreach($arrRoles as $role => $resorces)
			{
			$acl->addRole($role);
			foreach ($resorces as $resorce => $permission)
				{
				$acl->addResource($resorce);
				$acl->allow($role,$resorce,$permission);
				}
			}
		return $acl;
		}

		/**
		 *
		 * Responsavel por retornar a estrutura das regras
		 * e permiss�es do usu�rio
		 *
		 * @param string $matricula
		 * @return Array
		 */
	private function _getArrayRoles($matricula)
		{
		$papelFuncionario = new PapelFuncionario();
		$rowSet = $papelFuncionario->fetchAll('id_funcionario = '.$matricula);
		$acessoPapel = new AcessoPapel();
		$first=true;
		foreach($rowSet as $row)
			{
			$rowPapel = $row->findParentRow('Papel');
			$arrRoles[$rowPapel->nome] = array();
			$subRowSetAcesso = $acessoPapel->fetchAll('id_papel = '.$row->id_papel);
			foreach ($subRowSetAcesso as $subRow)
				{
				$recursoPrivilegio = $subRow->findParentRow('Acesso');
				if(!is_array($arrRoles[$rowPapel->nome][$recursoPrivilegio->recurso]))
					$arrRoles[$rowPapel->nome][$recursoPrivilegio->recurso] = array();
				$arrRoles[$rowPapel->nome][$recursoPrivilegio->recurso][] = $recursoPrivilegio->privilegio;
				}
			}
		return $arrRoles;
		}

}