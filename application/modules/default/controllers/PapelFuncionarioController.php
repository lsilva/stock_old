<?php
class PapelFuncionarioController extends Zend_Controller_Action
{
	private $_modelWebUser;
	private $_modelPapel;
	private $_modelPapelFuncionario;

	public function init()
	{
		Zend_Loader::loadClass('WebUser');
		Zend_Loader::loadClass('Papel');
		Zend_Loader::loadClass('PapelFuncionario');
		$this->_modelWebUser = new WebUser();
		$this->_modelPapel = new Papel();
		$this->_modelPapelFuncionario = new PapelFuncionario();
//		$this->_helper->layout->disableLayout();
	}

	public function indexAction()
	{
		$this->view->assign('mensagem',Fgsl_Session_Namespace::get('mensagem'));
		$this->view->assign('form',$this->_getForm());
		$this->view->assign('table',$this->_getTable());
		Fgsl_Session_Namespace::remove('mensagem');
	}

	private function _getForm()
	{
		$form = new Zend_Form();
		$form->setAction('/redeworking/default/papel-funcionario/relacionar');
		$form->setMethod('post');

		$records = $this->_modelWebUser->fetchAll(null,'web_login');
		$options = array();
		foreach ($records as $record)
		{
			$options[$record->web_id] = $record->web_login;//FORMATAR CPF/CNPJ
		}
		$element = new Zend_Form_Element_Select('web_id');
		$element->setLabel('Login');
		$element->setMultiOptions($options);
		$form->addElement($element);
		$records = $this->_modelPapel->fetchAll(null,'nome');
		$options = array();
		foreach ($records as $record)
		{
			$options[$record->id] = $record->nome;
		}
		$element = new Zend_Form_Element_Select('papel');
		$element->setLabel('Papel');
		$element->setMultiOptions($options);
		$form->addElement($element);

		$element = new Zend_Form_Element_Submit('atribuir');
		$form->addElement($element);
		$element = new Zend_Form_Element_Submit('destituir');
		$form->addElement($element);

		return $form;
	}

	public function relacionarAction()
	{
		$post = Fgsl_Session_Namespace::get('post');
		$id_funcionario = (int)$post->web_id;
		$id_papel = (int)$post->papel;
		$where = "id_funcionario = $id_funcionario and id_papel = $id_papel";
		try {
			$row = $this->_modelPapelFuncionario->fetchRow($where);
			$atribuido = $this->_isAtribuido($row);
			if (isset($post->atribuir) && !$atribuido)
			{
				$this->_atribuirPapel($id_funcionario,$id_papel,$where);
			}
			if (isset($post->destituir) && $atribuido)
			{
				$this->_destituirPapel($where);
			}
		} catch (Exception $e) {
			Fgsl_Session_Namespace::set('exception',$e);
			$this->_redirect('error/message');
		}
		$this->_forward('index');
	}

	private function _isAtribuido($row)
	{
		$atribuido = true;
		if (is_null($row))
		{
			$atribuido = false;
		}
		return $atribuido;
	}

	private function _getNome($modelo,$where)
	{
		$row = $this->_modelPapelFuncionario->fetchRow($where);
		$rowParent = $row->findParentRow($modelo);
		return $modelo=='WebUser'? $rowParent->web_login : $rowParent->nome;
	}

	private function _atribuirPapel($id_funcionario,$id_papel,$where)
	{
		$data = array(
			'id_funcionario'=>$id_funcionario,
			'id_papel'=>$id_papel
			);
		$this->_modelPapelFuncionario->insert($data);
		$nomePapel = $this->_getNome('Papel',$where);
		$nomeFuncionario = $this->_getNome('WebUser',$where);
		Fgsl_Session_Namespace::set('mensagem',"O papel $nomePapel foi atribuído ao funcionÃ¡rio $nomeFuncionario");
	}

	private function _destituirPapel($where)
	{
		$nomePapel = $this->_getNome('Papel',$where);
		$nomeFuncionario = $this->_getNome('WebUser',$where);
		Fgsl_Session_Namespace::set('mensagem',"O funcionário $nomeFuncionario foi destituído do papel $nomePapel");
		$this->_modelPapelFuncionario->delete($where);
	}

	private function _getTable()
	{
		$papeis = array();
		$rowSet = $this->_modelPapelFuncionario->fetchAll();

		foreach ($rowSet as $row)
		{
			$papeis[] = array(
				'WebUser'=>'',
				'Papel'=>''
			);
			$rowParent = $row->findParentRow('WebUser');
			$papeis[count($papeis)-1]['WebUser'] = $rowParent->web_login;
			$rowParent = $row->findParentRow('Papel');
			$papeis[count($papeis)-1]['Papel'] = $rowParent->nome;
		}
		//$html = new Fgsl_Html();
		//$html->addDecorator('Fgsl_Html_Table');
		//$table = $html->create($papeis,'Fgsl_Html_Table');
		$table = $this->createTable($papeis);
		return $table;
	}
	public function createTable($rows)
		{
		if(is_array($rows))
			{
			$first = true;
			$header = $lines = "";
			foreach($rows as $row)
				{
				$lines.="<tr>";
				foreach($row as $k => $v)
					{
					if($first)
						$header.="<td align='center'>{$k}</td>";
					$lines.="<td>{$v}</td>";
					}
				$first = false;
				$lines.="</tr>";
				}
			$table = "<table border='1'>";
			$table.= "<tr bgcolor='silver'>{$header}</tr>";
			$table.= $lines;
			$table.= "</table>";
			}
		return $table;
		}
}