<?php
	class AuxCursos extends Zend_Db_Table
		{
		protected $_name = 'aux_cur';

		public function getAllRows()
			{
			$select = $this->getAdapter()->select();
			$select->from(self::$this->_name);
			$select->order("cur_nome");
			return $this->getAdapter()->fetchAll($select);
			}
		}