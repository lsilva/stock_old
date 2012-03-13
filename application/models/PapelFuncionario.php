<?php
class PapelFuncionario extends Zend_Db_Table_Abstract
{
	protected $_name = 'papeis_funcionario';
	public $msgerr = '';
	protected $_referenceMap = array(
		'Papel' => array(
			'columns' => 'id_papel',
			'refTableClass' => 'Papel',
			'refColumns' => 'id'
			),
		'WebUser' => array(
			'columns' => 'id_funcionario',
			'refTableClass' => 'WebUser',
			'refColumns' => 'web_id'
			)
		);

	public function insert(array $data)
		{
		try
			{
			return parent::insert($data);
			}
		catch(Exception $e)
			{
			$this->msgerr = $e->getMessage();
			return false;
			}
		}
}