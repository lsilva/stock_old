<?php
class Endereco extends Fgsl_Db_Table_Abstract
	{
	protected $_name = 'cad_end';
	public function __construct()
		{
		parent::__construct();
		$this->_fieldKey = 'end_id';
		$this->_fieldOptions = array();
		$this->_fieldOptions['end_cep'] = array(
			'addValidator'=>array('NotEmpty'),
			'setAttrib'=>array('maxLength'=>'9', 'data'=>'cep')
			);
		$this->_fieldNames = $this->_getCols();

		$this->_fieldLabels = array(
			'end_id'      => 'ID',
			'end_rua'     => 'Rua',
			'end_numero'  => 'Numero',
			'end_complemento' => 'Complemento',
			'end_bairro'  => 'Bairro',
			'end_cidade'  => 'Cidade',
			'end_estado'  => 'Estado',
			'end_pais'    => 'Pais',
			'end_cep'     => 'CEP'
			);

		$this->_lockedFields = array('end_id') ;
		$this->_orderField = 'end_id';
		$this->_searchField = 'end_id';
		$this->_selectOptions = array();
		$this->_typeElement = array(
			'end_id'      => Fgsl_Form_Constants::TEXT,
			'end_rua'     => Fgsl_Form_Constants::TEXT,
			'end_numero'  => Fgsl_Form_Constants::TEXT,
			'end_complemento' => Fgsl_Form_Constants::TEXT,
			'end_bairro'  => Fgsl_Form_Constants::TEXT,
			'end_cidade'  => Fgsl_Form_Constants::TEXT,
			'end_estado'  => Fgsl_Form_Constants::TEXT,
			'end_pais'    => Fgsl_Form_Constants::TEXT,
			'end_cep'     => Fgsl_Form_Constants::TEXT
			);
		$this->_typeValue = array(
			'end_id' 		=> self::INT_TYPE,
			);
		}
	}
