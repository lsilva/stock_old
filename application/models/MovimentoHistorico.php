<?php
class MovimentoHistorico extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'movimento_historico';
	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'tmv_id';
		$this->_fieldNames = $this->_getCols();
		$this->_fieldLabels = array(
      'mov_codigo' => 'ID do movimento',
      'mov_data' => 'Data',
      'mov_sequencia' => 'Número da nota',
      'mov_descricao' => 'Descrição',
      'mov_valor_total' => 'Valor total',
      'usu_codigo' => 'ID usuário',
      'cli_id' => 'Cliente / Fornecedor',
      'pgt_codigo' => 'Tipo do pagamento',
      'est_codigo' => 'Local do estoque',
      'tmov_codigo' => 'Tipo de movimento'
			);
    //Seta atributos especias para os fields
    $this->_fieldOptions = array();
    $this->_fieldOptions['mov_descricao'] = array(
      'setAttrib'=>array('maxLength'=>'256')
      );
    $this->_fieldOptions['mov_valor_total'] = array(
      'addValidator'=>array('NotEmpty'),
      'setRequired'=>true,
    	'setAttrib'=>array('maxLength'=>'10', 'data'=>'money', 'required'=>'true')
      );
    $this->_fieldOptions['mov_data'] = array(
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
		//$this->_lockedFields = array('tmv_id');
		$this->_orderField = 'mov_sequencia';
		$this->_searchField = 'mov_sequencia';

		$arrPgto = array("" => "Selecione");
		$arrEstoque = array("" => "Selecione");
		$arrTipoMov = array("" => "Selecione");

		$this->_selectOptions = array(
		  'pgt_codigo' => $arrPgto,
			'est_codigo' => $arrEstoque,
			'tmov_codigo' => $arrTipoMov
		  );

    $this->_typeElement = array(
      'mov_codigo' => Fgsl_Form_Constants::HIDDEN,
      'mov_data' => Fgsl_Form_Constants::TEXT,
      'mov_sequencia' => Fgsl_Form_Constants::TEXT,
      'mov_descricao' => Fgsl_Form_Constants::TEXTAREA,
      'mov_valor_total' => Fgsl_Form_Constants::TEXT,
      'usu_codigo' => Fgsl_Form_Constants::TEXT,
      'cli_id' => Fgsl_Form_Constants::TEXT,
      'pgt_codigo' => Fgsl_Form_Constants::SELECT,
      'est_codigo' => Fgsl_Form_Constants::SELECT,
      'tmov_codigo' => Fgsl_Form_Constants::SELECT
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