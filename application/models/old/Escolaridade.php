<?php
class Escolaridade extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'cad_esc';
	protected $_type;
	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'esc_id';
		$this->_fieldNames = $this->_getCols();
		$this->_fieldLabels = array(
			'cli_id' => 'ID Cliente',
			'esc_id' => 'ID Escolaridade',
			'esc_tipo_registro' => 'Tipo do registro',
			'esc_tipo_curso' => 'Tipo do curso',
			'esc_nome' => utf8_encode('Nome da instituição'),
			'esc_cidade' => 'Cidade',
			'esc_uf' => 'Estado',
			'aux_cur_id' => 'Nome do curso',
			'esc_dt_inicio' => utf8_encode('Data de ínicio'),
			'esc_dt_termino' => utf8_encode('Data de término'),
			'esc_status' => 'Status',
			'esc_carga_semestre' => 'Quantidade de semestres',
			'esc_observacao' =>utf8_encode('Observações'),
			'esc_carga_horaria' => 'Quantidade de horas',
			'esc_diploma' => 'Possui diploma?',
			'esc_cursando' => 'Cursando?',
			'esc_ordem' => utf8_encode('Ordem de exibição')
			);
		//Seta atributos especias para os fields
		$this->_fieldOptions = array();
		$this->_fieldOptions['esc_uf'] = array(
			'setAttrib'=>array('onChange'=>'sendUF(this.value)')
			);
		$this->_fieldOptions['esc_dt_inicio'] = array(
			'addValidator'=>array('NotEmpty'),
			'setRequired'=>true,
			'setAttrib'=>array('maxLength'=>'10', 'data'=>'data'/*, 'required'=>'true'*/)
			);
		$this->_fieldOptions['esc_dt_termino'] = $this->_fieldOptions['esc_dt_inicio'];
		//Insere atributo title para os campos setando o nome dos labels
		foreach($this->_fieldLabels as $key => $value)
			{
			if(isset($this->_fieldOptions[$key]) && isset($this->_fieldOptions[$key]['setAttrib']))
				$this->_fieldOptions[$key]['setAttrib']['title'] = $value;
			else
				$this->_fieldOptions[$key] = array('setAttrib'=>array('title'=>$value));
			}
		Zend_Loader::loadClass('AuxCursos');
		$objAuxCursos = new AuxCursos();
		$return = $objAuxCursos->getAllRows();

		$arrCursos = array('' => 'Selecione');
		if(is_array($return))
			foreach($return as $row)
				$arrCursos[$row["cur_id"]] = utf8_encode($row["cur_nome"]);

		$this->_lockedFields = array('cli_id','esc_id','esc_tipo_registro');
		$this->_orderField = 'esc_nome';
		$this->_searchField = 'esc_nome';
		$this->_selectOptions = array(
			'esc_tipo_curso' => array(
				'ENF' => utf8_encode('Ensino Fundamental (1º Grau)'),
				'ENM' => utf8_encode('Ensino Médio (2º Grau)'),
				'EMP' => utf8_encode('Ensino Médio Profissionalizante (2º Grau Técnico)'),
				'SUP' => 'Superior',
				'POS' => utf8_encode('Pós-graduação'),
				'MST' => 'Mestrado',
				'DRO' => 'Doutorado'
				),
			'esc_cidade'=>array(''=>'Selecione um estado'),
			'esc_uf' => array(
				'AC' => 'Acre',
				'AL' => 'Alagoas',
				'AM' => 'Amazonas',
				'AP' => utf8_encode('Amapá'),
				'BA' => 'Bahia',
				'CE' => utf8_encode('Ceará'),
				'DF' => 'Distrito Federal',
				'ES' => utf8_encode('Espírito Santo'),
				'GO' => utf8_encode('Goiás'),
				'MA' => utf8_encode('Maranhão'),
				'MG' => 'Minas Gerais',
				'MS' => 'Mato Grosso do Sul',
				'MT' => 'Mato Grosso',
				'PA' => utf8_encode('Pará'),
				'PB' => utf8_encode('Paraíba'),
				'PE' => 'Pernambuco',
				'PI' => utf8_encode('Piauí'),
				'PR' => utf8_encode('Paraná'),
				'RJ' => 'Rio de Janeiro',
				'RN' => 'Rio Grande do Norte',
				'RO' => utf8_encode('Rondônia'),
				'RR' => 'Roraima',
				'RS' => 'Rio Grande do Sul',
				'SC' => 'Santa Catarina',
				'SE' => 'Sergipe',
				'SP' => utf8_encode('São Paulo'),
				'TO' => 'Tocantins'
				),
			'aux_cur_id'=>array($arrCursos),
			'esc_status' => array(
				'AND' => 'Em andamento',
				'TRA' => 'Trancado',
				'INT' => 'Interrompido',
				'CON' => utf8_encode('Concluído')
				),
			'esc_diploma'=>array('S'=>'SIM','N'=>utf8_encode('NÃO')),
			'esc_cursando'=>array('S'=>'SIM','N'=>utf8_encode('NÃO'))
			);
		$this->_typeElement = array(
			'cli_id' => Fgsl_Form_Constants::TEXT,
			'esc_id' => Fgsl_Form_Constants::TEXT,
			'esc_tipo_registro' => Fgsl_Form_Constants::TEXT,
			'esc_tipo_curso' => Fgsl_Form_Constants::SELECT,
			'esc_nome' => Fgsl_Form_Constants::TEXT,
			'esc_cidade' => Fgsl_Form_Constants::SELECT,
			'esc_uf' => Fgsl_Form_Constants::SELECT,
			'aux_cur_id' => Fgsl_Form_Constants::SELECT,
			'esc_dt_inicio' => Fgsl_Form_Constants::TEXT,
			'esc_dt_termino' => Fgsl_Form_Constants::TEXT,
			'esc_status' => Fgsl_Form_Constants::SELECT,
			'esc_carga_semestre' => Fgsl_Form_Constants::TEXT,
			'esc_observacao' => Fgsl_Form_Constants::TEXTAREA,
			'esc_carga_horaria' => Fgsl_Form_Constants::TEXT,
			'esc_diploma' => Fgsl_Form_Constants::SELECT,
			'esc_cursando' => Fgsl_Form_Constants::SELECT,
			'esc_ordem' => Fgsl_Form_Constants::TEXT
			);
		$this->_typeValue = array(
			'cli_id' => self::INT_TYPE,
			'esc_id' => self::INT_TYPE
			);
		}

	public function getCustomSelect($where,$order,$limit)
		{
	  $arrBlock = array();
	  $arrBlock['esc'] = array('esc_ordem','esc_carga_horaria','esc_cursando','esc_diploma');
	  $arrBlock['cur'] = array('esc_carga_semestre','esc_tipo_curso','aux_cur_id','esc_status');
		foreach($this->_getCols() as $col)
			{
			if (in_array($col, $arrBlock[self::$this->_type]))continue;
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
		$data["esc_id"] = $this->nextID("cli_id='".$dataAuth->cli_id."'");
		parent::insert($data);
		}

	public function update(array $data, $where)
		{
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

	/**
	 * Armazena na variavel se é cadastro de CURSO-CUR ou ESCOLARIDADE-ESC
	 * @param String $s
	 * @return void
	 */
	public function setType($s)
		{
		$this->_type = $s;
		}

	public function nextID($where)
		{
		$select = $this->getAdapter()->select();
		$select->from(self::$this->_name,array("max(esc_id) AS MAX"));
		$select->where($where);
		$record = $this->fetchAllAsArray($select);

		return $record[0]["MAX"]+1;
		}
	}