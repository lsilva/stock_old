<?php
class AcessoPapelController extends Zend_Controller_Action
{
	private $_modelAcesso;
	private $_modelPapel;
	private $_modelAcessoPapel;

	public function init()
	{
		Zend_Loader::loadClass('Acesso');
		Zend_Loader::loadClass('Papel');
		Zend_Loader::loadClass('AcessoPapel');
		$this->_modelAcesso = new Acesso();
		$this->_modelPapel = new Papel();
		$this->_modelAcessoPapel = new AcessoPapel();
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
		$form->setAction('/redeworking/default/acesso-papel/relacionar');
		$form->setMethod('post');

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

		$records = $this->_modelAcesso->fetchAll(null,'recurso');
		$options = array();
		foreach ($records as $record)
		{
			$options[$record->id] = $record->recurso." ( ".$record->privilegio." )";
		}
		$element = new Zend_Form_Element_Select('recurso');
		$element->setLabel('Recurso');
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
		$id_acesso = (int)$post->recurso;
		$id_papel = (int)$post->papel;
		$where = "id_acesso = $id_acesso and id_papel = $id_papel";
		try {
			$row = $this->_modelAcessoPapel->fetchRow($where);
			$atribuido = $this->_isAtribuido($row);
			if (isset($post->atribuir) && !$atribuido)
			{
				$this->_atribuirPapel($id_acesso,$id_papel,$where);
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
		$row = $this->_modelAcessoPapel->fetchRow($where);
		$rowParent = $row->findParentRow($modelo);
		return $modelo=='Acesso'? $rowParent->recurso : $rowParent->nome;
	}

	private function _atribuirPapel($id_acesso,$id_papel,$where)
	{
		$data = array(
			'id_acesso'=>$id_acesso,
			'id_papel'=>$id_papel
			);
		$this->_modelAcessoPapel->insert($data);
		$nomePapel = $this->_getNome('Papel',$where);
		$nomeRecurso = $this->_getNome('Acesso',$where);
		Fgsl_Session_Namespace::set('mensagem',"Um acesso ao recurso $nomeRecurso foi definido para o papel $nomePapel");
	}

	private function _destituirPapel($where)
	{
		$nomePapel = $this->_getNome('Papel',$where);
		$nomeRecurso = $this->_getNome('Acesso',$where);
		Fgsl_Session_Namespace::set('mensagem',"O papel $nomePapel não tem acesso ao recurso $nomeRecurso");
		$this->_modelAcessoPapel->delete($where);
	}

	private function _getTable()
	{
		$papeis = array();
		$rowSet = $this->_modelAcessoPapel->fetchAll();

		foreach ($rowSet as $row)
		{
			$papeis[] = array(
				'Papel'=>'',
				'Acesso'=>''
			);
			$rowParent = $row->findParentRow('Acesso');
			$papeis[count($papeis)-1]['Acesso'] = $rowParent->recurso." ( ".$rowParent->privilegio." )";;
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