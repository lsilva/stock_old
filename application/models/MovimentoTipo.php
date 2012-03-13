<?php
class MovimentoTipo extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'movimento_tipo';
	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'tmv_id';
		$this->_fieldNames = $this->_getCols();
		$this->_fieldLabels = array(
			'tmv_id' => 'ID Tipo',
		  'tmv_descricao' => 'Descricao',
  		'tmv_tipo' => 'Tipo de movimento',
			'tmv_estorno' => 'Estorno',
			'tmv_customedio' => 'Afeta custo médio',
			'tmv_custoatual' => 'Afeta custo atual'
			);

    //Seta atributos especias para os fields
    $this->_fieldOptions = array();
    $this->_fieldOptions['tmv_descricao'] = array(
      'addValidator'=>array('NotEmpty'),
      'setRequired'=>true,
      'setAttrib'=>array('maxLength'=>'128', 'required'=>'true')
      );
		//Insere atributo title para os campos setando o nome dos labels
		foreach($this->_fieldLabels as $key => $value)
			{
			if(isset($this->_fieldOptions[$key]) && isset($this->_fieldOptions[$key]['setAttrib']))
				$this->_fieldOptions[$key]['setAttrib']['title'] = $value;
			else
				$this->_fieldOptions[$key] = array('setAttrib'=>array('title'=>$value));
			}
		//$this->_lockedFields = array('tmv_id');
		$this->_orderField = 'tmv_descricao';
		$this->_searchField = 'tmv_descricao';

		$this->_selectOptions = array(
		  'tmv_tipo' => array("E"=>"ENTRADA","S"=>"SAÍDA")
		  );
    $this->_typeElement = array(
			'tmv_id' => Fgsl_Form_Constants::TEXT,
		  'tmv_descricao' => Fgsl_Form_Constants::TEXT,
  		'tmv_tipo' => Fgsl_Form_Constants::SELECT,
			'tmv_estorno' => Fgsl_Form_Constants::CHECKBOX,
			'tmv_customedio' => Fgsl_Form_Constants::CHECKBOX,
			'tmv_custoatual' => Fgsl_Form_Constants::CHECKBOX
		);
		$this->_typeValue = array(
			'tmv_id' => self::INT_TYPE
		  );
		}

	public function insert(array $data)
		{
		$data["tmv_estorno"] = (empty($data["tmv_estorno"])?"N":"S");
		$data["tmv_customedio"] = (empty($data["tmv_customedio"])?"N":"S");
		$data["tmv_custoatual"] = (empty($data["tmv_custoatual"])?"N":"S");
	  parent::insert($data);
		}

	public function update(array $data, $where)
		{
		$data["tmv_estorno"] = (empty($data["tmv_estorno"])?"N":"S");
		$data["tmv_customedio"] = (empty($data["tmv_customedio"])?"N":"S");
		$data["tmv_custoatual"] = (empty($data["tmv_custoatual"])?"N":"S");
		parent::update($data,$where);
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

	public function delete($where)
		{
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		$where = "cli_id={$dataAuth->cli_id} AND " . $where;
		parent::delete($where);
		}
*/
	}