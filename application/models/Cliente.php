<?php
class Cliente extends Fgsl_Db_Table_Abstract
{
	protected $_name = 'cad_cli';
	protected $_pes;

	public function __construct()
	{
		parent::__construct();
		$this->_fieldKey = 'cli_id';
		$this->_fieldNames = array();
		$this->_fieldNames[] = 'cli_pf_cpf';
		$this->_fieldNames[] = 'cli_pj_cnpj';
		$colSeq = $this->_fieldNames;
		$cols = $this->_getCols();
		for($i=0;$i<count($cols);$i++)
		{
			if(in_array($cols[$i],$colSeq))continue;
			$this->_fieldNames[] = $cols[$i];
		}
		$this->_fieldLabels = array(
			'cli_id' 		=> 'ID',
			'cli_pf_cpf'	=> 'CPF',
		  'cli_pf_rg'		=> 'RG',
			'cli_pf_nome'	=> 'Nome',
			'cli_pf_sexo'	=> 'Sexo',
			'cli_pf_dt_nascimento'	=> 'Data de Nascimento',
			'cli_pf_estado_civil'		=> 'Estado Civil',
			'cli_pj_cnpj'	=> 'CNPJ',
		  'cli_pj_ie'		=> 'IE',
			'cli_pj_fantasia'	=> 'Nome Fantasia',
			'cli_pj_razao'		=> 'Razao Social',

			'cli_data_insert'	=> 'Data de cadastro',
			'cli_status'	=> 'Status',
		  'cli_path_image' => 'Imagem'
			);
			//Seta atributos especias para os fields
			$this->_fieldOptions = array();
			$this->_fieldOptions['cli_pf_cpf'] = array(
				'addValidator'=>array('NotEmpty'),
				'setRequired'=>true,
				'setAttrib'=>array('maxLength'=>'14', 'data'=>'cpf', 'required'=>'true')
			);
			$this->_fieldOptions['cli_pf_rg'] = array(
				'addValidator'=>array('NotEmpty'),
				'setRequired'=>true,
				'setAttrib'=>array('maxLength'=>'12', 'data'=>'rg', 'required'=>'true')
			);
			$this->_fieldOptions['cli_pj_cnpj'] = array(
				'addValidator'=>array('NotEmpty'),
				'setRequired'=>true,
				'setAttrib'=>array('maxLength'=>'20', 'data'=>'cnpj', 'required'=>'true')
			);
			$this->_fieldOptions['cli_pf_dt_nascimento'] = array(
				'addValidator'=>array('NotEmpty'),
				'setRequired'=>true,
				'setAttrib'=>array('maxLength'=>'10', 'data'=>'data', 'required'=>'true')
			);
			//Insere atributo title para os campos setando o nome dos labels
			foreach($this->_fieldLabels as $key => $value)
				{
				if(isset($this->_fieldOptions[$key]) && isset($this->_fieldOptions[$key]['setAttrib']))
					$this->_fieldOptions[$key]['setAttrib']['title'] = $value;
				else
					$this->_fieldOptions[$key] = array('setAttrib'=>array('title'=>$value));
				}

			$this->_lockedFields = array('cli_status','cli_data_insert','cli_path_image');
			$this->_orderField = 'cli_id';
			$this->_searchField = 'cli_id';
			$this->_selectOptions = array(
  			'cli_pf_sexo' => array('M'=>'Masculino','F'=>'Feminino'),
  			'cli_pf_estado_civil'=>array('SLT'=>'Solteiro','CAS'=>'Casado')
			);
			$this->_typeElement = array(
  			'cli_id' 		=> Fgsl_Form_Constants::HIDDEN,
  			'cli_pf_cpf'	=> Fgsl_Form_Constants::TEXT,
  			'cli_pf_rg'			=> Fgsl_Form_Constants::TEXT,
  			'cli_pf_nome'	=> Fgsl_Form_Constants::TEXT,
  			'cli_pf_sexo'	=> Fgsl_Form_Constants::SELECT,
  			'cli_pf_dt_nascimento'	=> Fgsl_Form_Constants::TEXT,
  			'cli_pf_estado_civil'		=> Fgsl_Form_Constants::SELECT,
  			'cli_pj_cnpj'			=> Fgsl_Form_Constants::TEXT,
  			'cli_pj_ie'			=> Fgsl_Form_Constants::TEXT,
  			'cli_pj_fantasia'	=> Fgsl_Form_Constants::TEXT,
  			'cli_pj_razao'		=> Fgsl_Form_Constants::TEXT,
#  			'cli_data_insert'	=> 'Data de cadastro',
#  			'cli_status'	=> 'Status',
#  		  'cli_path_image' => 'Imagem'
			);
			$this->_typeValue = array(
				'cli_id' 			=> self::INT_TYPE
			);
	}

	public function insert(array $data)
	{
		$data['cli_data_insert'] = date('Y-m-d H:i:s');
		$data['cli_pj_cnpj'] = str_replace(array(".","/","-"), "", $data['cli_pj_cnpj']);
		$data['cli_pj_ie'] = str_replace(array(".","/","-"), "", $data['cli_pj_ie']);
		$data['cli_pf_rg'] = str_replace(array(".","/","-"), "", $data['cli_pf_rg']);
		$data['cli_pf_cpf'] = str_replace(array(".","/","-"), "", $data['cli_pf_cpf']);
		if($this->_pes=='pj')
			{
			$data = $this->removeCampos($data,'pf');
			if($this->CnpjExists($data['cli_pj_cnpj']))
			  throw new Exception('CNPJ já cadastrado.');
			$user	= $data['cli_pj_cnpj'];
			}
		else
			{
			$data = $this->removeCampos($data,'pj');
			if($this->CpfExists($data['cli_pf_cpf']))
			  throw new Exception('CPF já cadastrado.');

		  $data['cli_pf_dt_nascimento'] = $this->dateOnDB($data['cli_pf_dt_nascimento']);

			if($data['cli_pf_dt_nascimento'] == "0000-00-00")
			  throw new Exception('Data de nascimento inválida.');
			$user	= $data['cli_pf_cpf'];
			}
    //Insert Cliente
		parent::insert($data);
	}

	public function update(array $data, $where)
		{
		unset($data['cli_pj_cnpj']);
		unset($data['cli_pf_cpf']);
		if($this->_pes=='pf')
  		{
  	  $data['cli_pf_dt_nascimento'] = $this->dateOnDB($data['cli_pf_dt_nascimento']);
  		if($data['cli_pf_dt_nascimento'] == "0000-00-00")
  		  throw new Exception('Data de nascimento inválida.');
  		}
		parent::update($data,$where);
		}

	public function getCustomSelect($where,$order,$limit)
	  {
		$select = $this->getAdapter()->select();
		$fields = $this->_fieldNames;
		$fields = $this->removeCampos($fields,($this->_pes == "pf" ? "pj" : "pf"));
		$key = array_search('cli_status', $fields);
		unset($fields[$key]);
		$key = array_search('cli_data_insert', $fields);
		unset($fields[$key]);
		$key = array_search('cli_path_image', $fields);
		unset($fields[$key]);

		$select->from(array('CLI'=>'cad_cli'),$fields);
		if($where!==null)
		  $where .= " AND " . ($this->_pes == "pf" ? "cli_pf_cpf != '' " : "cli_pj_cnpj != '' ");
		else
		  $where = ($this->_pes == "pf" ? "cli_pf_cpf != '' " : "cli_pj_cnpj != '' ");
//var_dump($this->_pes,($this->_pes == "pf"));
	  $select->where($where);
		$select->order($order);
		$select->limit($limit);
//		var_dump($select->__toString());exit;
		return $select;
	  }

	/**
	 * Remove um conjunto de campos para n�o serem atualizados
	 * fazendo assim que somente sejam atualizados os campos
	 * ou de PJ ou de PF
	 * @param array $data
	 * @param String $yipe : PJ / PF
	 */
	public function removeCampos(array $data,$type)
	{
		$clone = $data;
		if(!isset($data[0]))
		{
  		foreach($data as $key => $value)
  		{
  			$pos = strrpos($key, strtolower("_{$type}_"));
  			if ($pos !== false)
  			  unset($data[$key]);
  		}
		}
		else
		{
		foreach($data as $key => $value)
  		{
  			$pos = strrpos($value, strtolower("_{$type}_"));
  			if ($pos !== false)
  			  unset($data[$key]);
  		}
		}
		return $data;
	}

	/**
	 * Verifica se o CNPJ j� est� cadastrado
	 * @param String $s
	 * @return Boolean
	 */
	public function CnpjExists($s)
	{
		$result = $this->fetchRow('cli_pj_cnpj=\''.$s.'\'');
		if($result)
			return true;
		else
			return false;
	}
	/**
	 * Verifica se o CNPJ j� est� cadastrado
	 * @param String $s
	 * @return Boolean
	 */
	public function CpfExists($s)
	{
		$result = $this->fetchRow('cli_pf_cpf='.$s);
		if($result)
			return true;
		else
			return false;
	}
	/**
	 * Armazena na variavel se � cadastro de PJ ou PF
	 * @param String $s
	 * @return void
	 */
	public function setPes($s)
	{
		$this->_pes = strtolower($s);
	}

	/**
	 * Retornao conteúdo da variavel pes
	 * @param String $s
	 * @return void
	 */
	public function getPes()
	{
		return $this->_pes;
	}
}