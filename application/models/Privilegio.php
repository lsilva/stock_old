<?php
class Privilegio extends Fgsl_Db_Table_Abstract
{
	protected $_name = 'privilegios';

	public function getSelectOptions($fieldName)
	{
		$options = array();
		if ($fieldName == 'id_recurso')
		{
			Zend_Loader::loadClass('Acesso');
			$acesso = new Acesso();
			$records = $acesso->fetchAll(null,'recurso');

			foreach($records as $record)
			{
				$options[$record->id] = $record->recurso;
			}
		}
		return $options;
	}
	public function insert(array $data)
	{
		$data['acesso'] = $data['acesso'] ? 'true' : 'false';
		parent::insert($data);
	}
	public function update(array $data,$where)
	{
		$data['acesso'] = $data['acesso'] ? 'true' : 'false';
		parent::update($data,$where);
	}
	public function setRelationships(array &$records)
	{
		Zend_Loader::loadClass('Acesso');
		$objAcesso = new Acesso();
		$rowSet = $objAcesso->fetchAll()->toArray();
		$acessos = array();
		foreach ($rowSet as $row)
		{
			$acessos[$row['id']]=$row['recurso'];
		}
		foreach($records as $id => $element)
		{
			$records[$id]['Recurso'] = $acessos[$element['Recurso']];
		}
	}
}