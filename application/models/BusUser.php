<?php
class BusUser extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'bus_usu';
	//protected $_dependentTables = array('WebUser','BusUser','Contato','Endereco');
	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'bus_id';
		$this->_fieldNames = $this->_getCols();

		$this->_fieldLabels = array(
			'bus_id' 		=> 'ID',
			'cli_id'    => 'Cliente',
			'eqp_id' 		=> 'Equipe',
			'bus_admin' => 'Administrador',
			'bus_bloqueia'	=> 'Bloqueado',
			'bus_expira'   	=> 'Expirar'
			);
		$this->_lockedFields = array('bus_id') ;
		$this->_orderField = 'bus_id';
		$this->_searchField = 'bus_id';
		$this->_selectOptions = array(
			'bus_bloqueia'=>array('S'=>'SIM','N'=>'NÃO'),
			'bus_expira'=>array('S'=>'SIM','N'=>'NÃO')
			);
		$this->_typeElement = array(
			'bus_id' 		=> Fgsl_Form_Constants::TEXT,
			'cli_id'    => Fgsl_Form_Constants::TEXT,
			'eqp_id' 		=> Fgsl_Form_Constants::TEXT,
			'bus_admin' => Fgsl_Form_Constants::TEXT,
			'bus_bloqueia'	=> Fgsl_Form_Constants::SELECT,
			'bus_expira'   	=> Fgsl_Form_Constants::SELECT
			);
		$this->_typeValue = array(
			'cli_id' 		=> self::INT_TYPE,
			'bus_id' 		=> self::INT_TYPE,
			'eqp_id'    => self::INT_TYPE
			);
		}
	/**
	 * Verifica se o usuário passado já tem cadastro
	 * @param string $s
	 * @return boolean
	 */
	public function LoginExists($s)
		{
		$result = $this->fetchRow('web_login='.$s);
		if($result)
			return true;
		else
			return false;
		}
}