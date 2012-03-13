<?php
class WebUser extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'web_usu';
	protected $_dependentTables = array('PapelFuncionario');
	public $msgerr = '';//

	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'web_id';
		$padrao = array(
			'addValidator'=>array('NotEmpty'),
			'setAttrib'=>array('maxLength'=>'30')
			);
		$this->_fieldOptions['web_pass'] = $padrao;
		$this->_fieldOptions['web_new_pass'] = $padrao;
		$this->_fieldOptions['web_pass_re'] = $padrao;

		$this->_fieldNames = $this->_getCols();
		$this->_fieldNames[] = 'web_new_pass';
		$this->_fieldNames[] = 'web_pass_re';

		$this->_fieldLabels = array(
			'web_id' 		=> 'ID',
			'cli_id'    => 'Cliente',
			'web_login' => 'Usuario',
			'web_pass'  => 'Senha atual',
			'web_new_pass'  => 'Nova senha',
			'web_pass_re'		=> 'Repita a nova senha',
			'web_fuso'  => 'Fusuhorario',
			'web_idioma'=> 'Idioma'
			);
		$this->_lockedFields = array('web_id','web_login','cli_id','web_fuso','web_idioma');
		$this->_orderField = 'web_id';
		$this->_searchField = 'web_id';
		$this->_selectOptions = array('web_idioma'=>array('pt-br','Português Brasil'));
		$this->_typeElement = array(
			'cli_id' 		=> Fgsl_Form_Constants::TEXT,
			'web_id' 		=> Fgsl_Form_Constants::TEXT,
			'web_login' => Fgsl_Form_Constants::TEXT,
			'web_pass'  => Fgsl_Form_Constants::PASSWORD,
			'web_new_pass'  => Fgsl_Form_Constants::PASSWORD,
			'web_pass_re'		=> Fgsl_Form_Constants::PASSWORD,
			'web_fuso'  => Fgsl_Form_Constants::TEXT,
			'web_idioma'=> Fgsl_Form_Constants::SELECT
			);
		$this->_typeValue = array(
			'cli_id' 		=> self::INT_TYPE,
			'web_id' 		=> self::INT_TYPE
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

	public function insert(array $data)
		{
		try
			{
			$data['web_pass'] = self::encrypt($data['web_pass']);
			return parent::insert($data);
			}
		catch(Exception $e)
			{
			$this->msgerr = $e->getMessage();
			return false;
			}
		}


	/**
	 * Função para encrpitar senha do cliente
	 *
	 * @param String $s
	 * @return String
	 */
	public static function encrypt($s)
		{
		return strrev(md5(sha1($s)));
		}


}