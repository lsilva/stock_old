<?php
class Profissional extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'cad_pro';
	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'pro_id';
		$this->_fieldNames = $this->_getCols();
		$this->_fieldLabels = array(
			'cli_id' => 'ID Cliente',
			'pro_id' => 'ID Profissão',
		  'pro_nome_empresa' => 'Nome da empresa', 
			'pro_dt_inicio' => utf8_encode('Data de ínicio'),
			'pro_dt_termino' => utf8_encode('Data de término'),
			'pro_atual' => 'Emprego atual?',
			'pro_salario' => utf8_encode('Salário'),
			'pro_tipo_pgto' => 'Tipo de pagamento',
			'pro_exibir_salario' => utf8_encode('Exibir o salário'),
			'pro_atividade' => 'Atividades exercidas',
			'pro_cargo' => 'Cargo',
			'pro_nivel_cargo' => utf8_encode('Nível do cargo exercído'),
		  'pro_ramo_empresa' => utf8_encode('Ramo de atuação da empresa')
			);
    //Seta atributos especias para os fields
    $this->_fieldOptions = array();
    $this->_fieldOptions['pro_dt_inicio'] = array(
      'setAttrib'=>array('maxLength'=>'10', 'data'=>'data')
      );
    $this->_fieldOptions['pro_dt_termino'] = $this->_fieldOptions['pro_dt_inicio'];
          
    $this->_fieldOptions['pro_cargo'] = array(
      'addValidator'=>array('NotEmpty'),
      'setRequired'=>true,
      'setAttrib'=>array('maxLength'=>'256', 'required'=>'true')
      );
    $this->_fieldOptions['pro_nome_empresa'] = array(
      'addValidator'=>array('NotEmpty'),
      'setRequired'=>true,
      'setAttrib'=>array('maxLength'=>'60', 'required'=>'true')
      );            
      
    $this->_fieldOptions['pro_salario'] = array(
      'addValidator'=>array('NotEmpty'),
      'setRequired'=>true,
      'setAttrib'=>array('maxLength'=>'10','required'=>'true','data'=>'money')
      );
		//Insere atributo title para os campos setando o nome dos labels
		foreach($this->_fieldLabels as $key => $value)
			{
			if(isset($this->_fieldOptions[$key]) && isset($this->_fieldOptions[$key]['setAttrib']))
				$this->_fieldOptions[$key]['setAttrib']['title'] = $value;
			else
				$this->_fieldOptions[$key] = array('setAttrib'=>array('title'=>$value));
			}
			
		$this->_lockedFields = array('cli_id','pro_id');
		$this->_orderField = 'pro_nome_empresa';
		$this->_searchField = 'pro_nome_empresa';
		$bool = array('S'=>'SIM', 'N'=>utf8_encode('NÃO'));
		$this->_selectOptions = array(
		  'pro_atual' => $bool,
		  'pro_tipo_pgto' => array('HR'=>'HORA', 'DI'=>'DIA', 'MS'=>utf8_encode('MÊS'), 'AN'=>'ANO'),
	    'pro_atual' => $bool,
		  'pro_nivel_cargo' => array('ACE'=>'ACESSORIA', 'CHE'=>'CHEFIA', 'SUP'=>'SUPERVISOR', 
		     'DIR'=>'DIRETORIA','GER'=>utf8_encode('GERÊNCIA'),'OPE'=>'OPERACIONAL',
		     'TEC'=>utf8_encode('TÉCNICO'),'ESP'=>'ESPECIALISTA'),
      'pro_ramo_empresa' => array('AGRO'=>utf8_encode('AGROPECUÁRIA'), 'COME'=>'COMERCIO',
		    'INDU'=>utf8_encode('INDÚSTRIA'), 'SERV'=>utf8_encode('SERVIÇO'), 'EDUC'=>utf8_encode('EDUCAÇÃO'), 
		    'SAUD'=>utf8_encode('SAÚDE'), 'PUBL'=>utf8_encode('PÚBLICO'),)
		  );
		$this->_typeElement = array(
      'cli_id' => Fgsl_Form_Constants::TEXT,
      'pro_id' => Fgsl_Form_Constants::TEXT,
		  'pro_nome_empresa' => Fgsl_Form_Constants::TEXT,
      'pro_dt_inicio' => Fgsl_Form_Constants::TEXT,
      'pro_dt_termino' => Fgsl_Form_Constants::TEXT,
      'pro_atual' => Fgsl_Form_Constants::SELECT,
      'pro_salario' => Fgsl_Form_Constants::TEXT,
      'pro_tipo_pgto' => Fgsl_Form_Constants::SELECT,
      'pro_exibir_salario' => Fgsl_Form_Constants::CHECKBOX,
      'pro_atividade' => Fgsl_Form_Constants::TEXTAREA,
      'pro_cargo' => Fgsl_Form_Constants::TEXT,
      'pro_nivel_cargo' => Fgsl_Form_Constants::SELECT,
      'pro_ramo_empresa' => Fgsl_Form_Constants::SELECT,		
			);
		$this->_typeValue = array(
			'cli_id' => self::INT_TYPE,
			'pro_id' => self::INT_TYPE
			);
		}

	public function getCustomSelect($where,$order,$limit)
		{
	  $arrBlock = array();
		foreach($this->_getCols() as $col)
			{			
			if($col == 'pro_dt_inicio')$col = "DATE_FORMAT(`cad_pro`.`pro_dt_inicio`, '%d/%m/%Y') AS pro_dt_inicio";
			if($col == 'pro_dt_termino')$col = "DATE_FORMAT(`cad_pro`.`pro_dt_termino`, '%d/%m/%Y') AS pro_dt_termino";
			
			$field[] = $col;
			}
		$select = $this->getAdapter()->select();
		$select->from(self::$this->_name,$field);
		if($where!==null)
			$select->where($where);
		$select->order($order);
		$select->limit($limit);
		//var_dump($select->__toString());exit;
		return $select;
		}

	public function insert(array $data)
		{
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		$data["cli_id"] = $dataAuth->cli_id;
		$data["pro_id"] = $this->nextID("cli_id='".$dataAuth->cli_id."'");
		$data["pro_salario"] = $this->float2bd($data["pro_salario"]);
		$data["pro_dt_inicio"] = $this->date2db($data["pro_dt_inicio"]);
		$data["pro_dt_termino"] = $this->date2db($data["pro_dt_termino"]);
		parent::insert($data);
		}

	public function date2db($s)
		{
		$dt = explode("/",$s);
		return $dt[2]."-".$dt[1]."-".$dt[0];	
		}	
		
	public function float2bd($s)
		{
		$s = str_replace(".","",$s);
		$s = str_replace(",",".",$s);
		return $s;	
		}
			
	public function update(array $data, $where)
		{
		$data["pro_salario"] = $this->float2bd($data["pro_salario"]);	
    $data["pro_dt_inicio"] = $this->date2db($data["pro_dt_inicio"]);
    $data["pro_dt_termino"] = $this->date2db($data["pro_dt_termino"]);			
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		$where = "cli_id={$dataAuth->cli_id} AND " . $where;
		parent::update($data,$where);
		}

	public function delete($where)
		{
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		$where = "cli_id={$dataAuth->cli_id} AND " . $where;
		parent::delete($where);
		}
	}