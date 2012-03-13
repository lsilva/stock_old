<?php
class Unidade extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'unidade';
	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'und_id';
		$this->_fieldNames = $this->_getCols();
		$this->_fieldLabels = array(
				'und_id' => 'ID Unidade',
		  	'und_nome' => 'Descrição',
		  	'und_abreviacao' => 'Abreviação'
			);

	    //Seta atributos especias para os fields
	    $this->_fieldOptions = array();
	    $this->_fieldOptions['und_nome'] = array(
	      'addValidator'=>array('NotEmpty'),
	      'setRequired'=>true,
	      'setAttrib'=>array('maxLength'=>'256', 'required'=>'true')
	      );
	    $this->_fieldOptions['und_abreviacao'] = array(
	      'setAttrib'=>array('maxLength'=>'3', 'data'=>'upper')
	      );

		//Insere atributo title para os campos setando o nome dos labels
		foreach($this->_fieldLabels as $key => $value)
			{
			if(isset($this->_fieldOptions[$key]) && isset($this->_fieldOptions[$key]['setAttrib']))
				$this->_fieldOptions[$key]['setAttrib']['title'] = $value;
			else
				$this->_fieldOptions[$key] = array('setAttrib'=>array('title'=>$value));
			}

		$this->_lockedFields = array('und_id');
		$this->_orderField = 'und_nome';
		$this->_searchField = 'und_nome';
/*
		$this->_selectOptions = array(
		  'pro_atual' => $bool,
		  'pro_tipo_pgto' => array('HR'=>'HORA', 'DI'=>'DIA', 'MS'=>utf8_encode('M�S'), 'AN'=>'ANO'),
	    'pro_atual' => $bool,
		  'pro_nivel_cargo' => array('ACE'=>'ACESSORIA', 'CHE'=>'CHEFIA', 'SUP'=>'SUPERVISOR',
		     'DIR'=>'DIRETORIA','GER'=>utf8_encode('GER�NCIA'),'OPE'=>'OPERACIONAL',
		     'TEC'=>utf8_encode('T�CNICO'),'ESP'=>'ESPECIALISTA'),
      'pro_ramo_empresa' => array('AGRO'=>utf8_encode('AGROPECU�RIA'), 'COME'=>'COMERCIO',
		    'INDU'=>utf8_encode('IND�STRIA'), 'SERV'=>utf8_encode('SERVI�O'), 'EDUC'=>utf8_encode('EDUCA��O'),
		    'SAUD'=>utf8_encode('SA�DE'), 'PUBL'=>utf8_encode('P�BLICO'),)
		  );
*/
	    $this->_typeElement = array(
			'und_id' => Fgsl_Form_Constants::TEXT,
	  		'und_nome' => Fgsl_Form_Constants::TEXT,
	    	'und_abreviacao' => Fgsl_Form_Constants::TEXT
		);
		/*$this->_typeValue = array(
			'cli_id' => self::INT_TYPE,
			'pro_id' => self::INT_TYPE
			);*/
		}
/*
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
*/
	}