<?php
class Contato extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'cad_cnt';
	public $msgerr = '';

	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'cnt_id';
		$this->_fieldNames = $this->_getCols();
		$this->_fieldLabels = array(
			'cli_id' => 'ID Cliente',
			'cnt_id' => 'ID Contato',
		  'cnt_valor' => 'Valor',
  		'cnt_tipo' => 'Tipo do contato'
			);

    //Seta atributos especias para os fields
    $this->_fieldOptions = array();
    $this->_fieldOptions['cnt_valor'] = array(
      'addValidator'=>array('NotEmpty'),
      'setRequired'=>true
      );
    $this->_fieldOptions['cnt_tipo'] = array(
      'addValidator'=>array('NotEmpty'),
      'setRequired'=>true
      );
		//Insere atributo title para os campos setando o nome dos labels
		foreach($this->_fieldLabels as $key => $value)
			{
			if(isset($this->_fieldOptions[$key]) && isset($this->_fieldOptions[$key]['setAttrib']))
				$this->_fieldOptions[$key]['setAttrib']['title'] = $value;
			else
				$this->_fieldOptions[$key] = array('setAttrib'=>array('title'=>$value));
			}
		$this->_orderField = 'cnt_tipo';
		$this->_searchField = 'cnt_valor';

		$this->_selectOptions = array(
		  'cnt_tipo' => array(""=>"Selecione","TELC"=>"Telefone comercial",
		  										"TELR"=>"Telefone residêncial","CEL"=>"Celular",
		  										"EML"=>"E-mail","FCB"=>"Facebook","TWT"=>"Twitter",
		  										"STE"=>"Site","BLG"=>"Blog")
		  );

    $this->_typeElement = array(
    	'cli_id' => Fgsl_Form_Constants::HIDDEN,
			'cnt_id' => Fgsl_Form_Constants::HIDDEN,
		  'cnt_valor' => Fgsl_Form_Constants::TEXT,
  		'cnt_tipo' => Fgsl_Form_Constants::SELECT
		);
		$this->_typeValue = array(
		  'cli_id' => self::INT_TYPE,
			'cnt_id' => self::INT_TYPE
		  );
		}

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
