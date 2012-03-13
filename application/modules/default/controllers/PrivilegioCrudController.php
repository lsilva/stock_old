<?php
class PrivilegioCrudController extends Fgsl_Crud_Controller_Abstract
{
	public function init()
	{
		parent::init();
		Zend_Loader::loadClass('Privilegio');

		$this->_useModules = true;
		$this->_uniqueTemplatesForApp = false;
		$this->_model = new Privilegio();
		$this->_title = 'Cadastro de Privilégios';
		$this->_searchButtonLabel = 'Pesquisar';
		$this->_searchOptions = array('nome'=>'Nome');
		$this->_config();

		$this->_helper->layout->disableLayout();
	}

	public function getSelectOptions($fieldName)
		{
		$options = array();
		if($fieldName == 'id_recurso')
			{
			Zend_Loader::loadClass('Acesso');
			$acesso = new Acesso();
			$records = $acesso->fechAll(null,'recurso');
			foreach($records as $record)
				$options[$record->id] = $record->recurso;
			}
		return $options;
		}

	public function insert(array $data)
		{
		$data['acesso'] = $data['acesso']?'true':'false';
		parent::insert($data);
		}

	public function update(array $data, $where)
		{
		$data['acesso'] = $data['acesso']?'true':'false';
		parent::update($data,$where);
		}

	/*public function setRelationships(array &$records)
		{
		Zend_Loader::loadClass('Acesso');
		$acesso = new Acesso();
		$options = array();
		$records = $acesso->fechAll()->toArray();
		foreach($records as $record)
			$options[$record['id']] = $record['recurso'];

		if($fieldName == 'id_recurso')
			{


			}
		return $options;
		}		*/
}