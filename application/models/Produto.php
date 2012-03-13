<?php
class Produto extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'produto';
	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'pro_id';
		$this->_fieldNames = $this->_getCols();
		$this->_fieldLabels = array(
			'pro_id' => 'ID Produto',
		  'pro_nome' => 'Nome do produto',
  		'mrc_id' => 'Marca',
#			'grp_id' => 'Grupo',
			'und_id' => 'Unidade',
			'pro_vlvenda' => 'Valor da venda',
			'pro_descontomax' => '% de desconto máximo',
			'pro_custoatual' => 'Custo atual',
			'pro_customedio' => 'Custo médio total',
			'pro_qtdtotal' => 'Qtd total de produto adquirido',
			'pro_estoqueqtd' => 'Qtd atual no estoque',
			'pro_estoqueale' => 'Qtd para alertar',
			'pro_estoquemin' => 'Qtd mínima',
			'pro_estoqueres' => 'Qtd reservada',
			'pro_peso' => 'Peso bruto (KG)',
			'pro_volume' => 'Volume (LxAxC)',
			'pro_descricao' => 'Descrição',
			'pro_imagem' => 'Imagem do produto'
			);
	    //Seta atributos especias para os fields
	    $this->_fieldOptions = array();
	    $this->_fieldOptions['pro_nome'] = array(
	      'addValidator'=>array('NotEmpty'),
	      'setRequired'=>true,
	      'setAttrib'=>array('maxLength'=>'512', 'required'=>'true')
	      );
	    $this->_fieldOptions['pro_vlvenda'] = array(
	      'addValidator'=>array('NotEmpty'),
	      'setRequired'=>true,
	      'setAttrib'=>array('maxLength'=>'10', 'data'=>'money', 'required'=>'true')
	      );
	    $this->_fieldOptions['pro_descontomax'] = array(
	      'setAttrib'=>array('maxLength'=>'5', 'data'=>'float')
	      );
	    $this->_fieldOptions['pro_estoqueale'] = array(
	      'setAttrib'=>array('maxLength'=>'10', 'data'=>'integer')
	      );
	    $this->_fieldOptions['pro_estoquemin'] = array(
	      'setAttrib'=>array('maxLength'=>'10', 'data'=>'integer')
	      );
	    $this->_fieldOptions['pro_estoqueres'] = array(
	      'setAttrib'=>array('maxLength'=>'10', 'data'=>'integer')
	      );
	    $this->_fieldOptions['pro_peso'] = array(
	      'setAttrib'=>array('maxLength'=>'7', 'data'=>'float')
	      );
	    $this->_fieldOptions['pro_volume'] = array(
	      'setAttrib'=>array('maxLength'=>'32')
	      );
	    $this->_fieldOptions['pro_descricao'] = array(
	      'addValidator'=>array('NotEmpty'),
	      'setRequired'=>true,
	      'setAttrib'=>array('maxLength'=>'1024', 'required'=>'true')
	      );
	    $this->_fieldOptions['pro_imagem'] = array(
	      'setAttrib'=>array('data'=>'image')
	      );
	    $this->_fieldOptions['pro_custoatual'] =
      $this->_fieldOptions['pro_customedio'] =
      $this->_fieldOptions['pro_qtdtotal'] =
      $this->_fieldOptions['pro_estoqueqtd'] =
	    array(
	      'setAttrib'=>array('readonly'=>'readonly')
	      );

		//Insere atributo title para os campos setando o nome dos labels
		foreach($this->_fieldLabels as $key => $value)
			{
			if(isset($this->_fieldOptions[$key]) && isset($this->_fieldOptions[$key]['setAttrib']))
				$this->_fieldOptions[$key]['setAttrib']['title'] = $value;
			else
				$this->_fieldOptions[$key] = array('setAttrib'=>array('title'=>$value));
			}
		$this->_lockedFields = array('grp_id');
		$this->_orderField = 'pro_nome';
		$this->_searchField = 'pro_nome';
    //Load Array de marcas
		Zend_Loader::loadClass('Marca');
		$objMarca = new Marca();
		$arrMarcaTemp = $objMarca->fetchAllAsArray($objMarca->getCustomSelect(NULL,"",""));
		$arrMarca = array("" => "Selecione");
		foreach($arrMarcaTemp as $marca)
  		$arrMarca[$marca["mrc_id"]] = $marca["mrc_nome"];
  	//Load Array de Unidades
		Zend_Loader::loadClass('Unidade');
		$objUnidade = new Unidade();
		$arrUnidadeTemp = $objUnidade->fetchAllAsArray($objUnidade->getCustomSelect(NULL,"",""));
		$arrUnidade = array("" => "Selecione");
		foreach($arrUnidadeTemp as $unidade)
  		$arrUnidade[$unidade["und_id"]] = $unidade["und_nome"];

		$this->_selectOptions = array(
		  'mrc_id' => $arrMarca,
			'und_id' => $arrUnidade
		  );
/*		  'pro_tipo_pgto' => array('HR'=>'HORA', 'DI'=>'DIA', 'MS'=>utf8_encode('M�S'), 'AN'=>'ANO'),
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
			'pro_id' => Fgsl_Form_Constants::HIDDEN,
	  	'pro_nome' => Fgsl_Form_Constants::TEXT,
 			'mrc_id' => Fgsl_Form_Constants::SELECT,
#			'grp_id' => Fgsl_Form_Constants::SELECT,
			'und_id' => Fgsl_Form_Constants::SELECT,
			'pro_vlvenda' => Fgsl_Form_Constants::TEXT,
			'pro_descontomax' => Fgsl_Form_Constants::TEXT,
			'pro_custoatual' => Fgsl_Form_Constants::TEXT,
			'pro_custoamedio' => Fgsl_Form_Constants::TEXT,
			'pro_qtdtotal' => Fgsl_Form_Constants::TEXT,
			'pro_estoqueqtd' => Fgsl_Form_Constants::TEXT,
			'pro_estoqueale' => Fgsl_Form_Constants::TEXT,
			'pro_estoquemin' => Fgsl_Form_Constants::TEXT,
			'pro_estoqueres' => Fgsl_Form_Constants::TEXT,
			'pro_peso' => Fgsl_Form_Constants::TEXT,
			'pro_volume' => Fgsl_Form_Constants::TEXT,
			'pro_descricao' => Fgsl_Form_Constants::TEXTAREA,
			'pro_imag-em' => Fgsl_Form_Constants::TEXT
		);
		$this->_typeValue = array(
			'pro_id' => self::INT_TYPE,
			'und_id' => self::INT_TYPE,
			'mrc_id' => self::INT_TYPE,
			//'grp_id' => self::INT_TYPE,
			'pro_vlvenda' => self::FLOAT_TYPE,
		  'pro_descontomax' => self::FLOAT_TYPE,
		  'pro_custoatual' => self::FLOAT_TYPE,
		  'pro_custoamedio' => self::FLOAT_TYPE,
		  'pro_peso' => self::FLOAT_TYPE,
  		'pro_qtdtotal' => self::INT_TYPE,
  		'pro_estoqueqtd' => self::INT_TYPE,
		  'pro_estoqueale' => self::INT_TYPE,
		  'pro_estoquemin' => self::INT_TYPE,
		  'pro_estoqueres' => self::INT_TYPE
			);
		}

	public function insert(array $data)
		{
/*		var_dump("INSERT",$data);exit;
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		$data["cli_id"] = $dataAuth->cli_id;
		$data["pro_id"] = $this->nextID("cli_id='".$dataAuth->cli_id."'");
		$data["pro_salario"] = $this->float2bd($data["pro_salario"]);
		$data["pro_dt_inicio"] = $this->date2db($data["pro_dt_inicio"]);
		$data["pro_dt_termino"] = $this->date2db($data["pro_dt_termino"]);
	*/	parent::insert($data);
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