<?php
class EscolaridadeCrudController extends Fgsl_Crud_Controller_Abstract
{
	public function init()
	{
		parent::init();
		Zend_Loader::loadClass('Escolaridade');

		$this->_useModules = true;
		$this->_uniqueTemplatesForApp = false;
		$this->_model = new Escolaridade();
	  $get = Fgsl_Session_Namespace::get('get');
		$this->_model->setType($get->type?$get->type:'cur');
		$this->_title = 'Escolaridade';
		$this->_searchButtonLabel = 'Pesquisar';
		$this->_searchOptions = array('esc_nome'=>'Nome Instituição');
		$this->_config();

	  $get = Fgsl_Session_Namespace::get('get');
		$this->type = ($get->type?$get->type:'cur');
	}

	public function insertAction()
	  {
	  $action = '?type='.$this->type ;
	  $arrBlock = array();
	  $arrBlock['esc'] = array('esc_ordem','esc_carga_horaria','esc_cursando','esc_diploma');
	  $arrBlock['cur'] = array('esc_carga_semestre','esc_tipo_curso','aux_cur_id','esc_status');
  	$this->_addFieldLockedThisForm($arrBlock[$this->type]);
		$module = $this->_useModules ? "{$this->_moduleName}/" : '';

		$data = $this->_getDataFromPost();

		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save".$action,
		Fgsl_Form_Edit::MODEL => $this->_model
		);
		$js = $this->_getJs();
	  $form = new Fgsl_Form_Edit($options);
	  $this->view->assign('js', $js);
	  $this->view->assign('form', $form);
	  }

	public function listAction()
		{
		$this->linksPersonalizados['insertLink'] = $this->getUrl().'/insert?type='.$this->type;

		Zend_Paginator::setDefaultScrollingStyle('Sliding');

		Zend_View_Helper_PaginationControl::setDefaultViewPartial('list.phtml');

		$this->_currentPage = $this->_getParam('page',1);
		$this->_currentPage = $this->_currentPage < 1 ? 1 : $this->_currentPage;

		$totalOfItems = $this->_itemsPerPage;

		$this->_lastPage = (int)(($totalOfItems/$this->_itemsPerPage));

		$paginator = $this->myGetPagedData();
		$records = $this->myGetProcessedRecords($paginator->getCurrentItems());

		$this->_model->setRelationships($records);
		$this->_table = $this->createTable($records);

		$this->configureViewAssign();
		//Alteração para que os links do assign possam ser editaveis
		if(count($this->linksPersonalizados)>0)
			foreach($this->linksPersonalizados as $k => $v)
				$this->view->assign($k,$v);

		$this->view->render('list.phtml');
		}

	public function editAction()
		{
		$fieldKey = $this->_model->getFieldKey();
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
	  $get = Fgsl_Session_Namespace::get('get');

		$where = "cli_id='".$dataAuth->cli_id."' AND esc_id='".$get->esc_id."'";
		$select = $this->_model->getCustomSelect($where,'','');

		$record = $this->_model->fetchAllAsArray($select);

		foreach ($this->_fieldNames as $fieldName)
		{
			if (isset($record[0][$fieldName]))
				{
				$data[$fieldName] = $record[0][$fieldName];
				}
		}

		$module = $this->_useModules ? "{$this->_moduleName}/" : '';

		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save",
		Fgsl_Form_Edit::MODEL => $this->_model
		);

		$this->view->assign('form', new Fgsl_Form_Edit($options));
		$this->view->render('insert.phtml');
		}

	public function createTable($rows)
		{
		if(is_array($rows))
			{
			$table = "";
			foreach($rows as $row)
				{
				$content ="<table border='1' width='100%'>";
				$content.="	<tr>";
				$content.="		<td colspan='100%' align='right' bgcolor='silver'>".$row["edit"]." - ".$row["remove"]."</td>";
				$content.="	</tr>";
				$content.="	<tr>";
				$content.="		<td><b>".$row["esc_tipo_curso"]["label"]."</b></td>";
//
				$arrOption = $this->_model->getSelectOptions("esc_tipo_curso");
				$content.="		<td colspan='100%'>".$arrOption[$row["esc_tipo_curso"]["value"]]."</td>";
				$content.="	</tr>";
				$content.="	<tr>";
				$content.="		<td><b>".$row["esc_nome"]["label"]."</b></td>";
				$content.="		<td colspan='100%'>";
				$content.=			$row["esc_nome"]["value"]."<br>";
				$content.=			$row["esc_uf"]["value"]." - ".$row["esc_cidade"]["value"];
				$content.="		</td>";
				$content.="	</tr>";
				$content.="	<tr>";
				$content.="		<td><b>".$row["aux_cur_id"]["label"]."</b></td>";

				$arrOption = $this->_model->getSelectOptions("aux_cur_id");
				$content.="		<td colspan='100%'>".@$arrOption[0][$row["aux_cur_id"]["value"]]."</td>";
				$content.="	</tr>";
				$content.="	<tr>";
				$content.="		<td><b>".$row["esc_status"]["label"]."</b></td>";

				$arrOption = $this->_model->getSelectOptions("esc_status");
				$content.="		<td>".$arrOption[$row["esc_status"]["value"]]."</td>";
				$content.="		<td><b>".$row["esc_dt_inicio"]["label"]."</b></td>";
				$content.="		<td>".$row["esc_dt_inicio"]["value"]."</td>";
				$content.="	</tr>";
				$content.="	<tr>";
				$content.="		<td><b>".$row["esc_carga_semestre"]["label"]."</b></td>";
				$content.="		<td>".$row["esc_carga_semestre"]["value"]."</td>";
				$content.="		<td><b>".$row["esc_dt_termino"]["label"]."</b></td>";
				$content.="		<td>".$row["esc_dt_termino"]["value"]."</td>";
				$content.="	</tr>";
				$content.="</table><br>";

				$table.=$content;
				};
			}
		return $table;
		}

	public function myGetPagedData()
		{
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		$where = "cli_id='".$dataAuth->cli_id."' AND esc_tipo_registro='".$this->type."'";
		$select = $this->_model->getCustomSelect($where,$this->_model->getOrderField(),$this->_itemsPerPage,($this->_currentPage-1)*$this->_itemsPerPage);
		$rows = $this->_model->fetchAllAsArray($select);
		$this->_logProfile($this->_profiler->getLastQueryProfile());
		$paginator = Zend_Paginator::factory($rows);
		$paginator->setCurrentPageNumber($this->_currentPage)
					->setItemCountPerPage($this->_itemsPerPage);
		return $paginator;
		}

	public function myGetProcessedRecords(ArrayIterator $currentItems)
		{
		$baseUrl = $this->getFrontController()->getBaseUrl();
		$module = $this->_useModules ? $this->_moduleName.'/' : '';
		$fieldKey = $this->_model->getFieldKey();
		$records = array();
		foreach ($currentItems as $row)
			{
			$records[] = array();
			$id = $row[$fieldKey];
			$records[count($records)-1]["edit"] = '<a href="'.$baseUrl.'/'.$module.$this->_controllerAction.'/edit?esc_id='.$row["esc_id"].'&type='.$this->type.'">Editar</a>';
			foreach ($this->_fieldNames as $fieldName)
				{
				if ($fieldName == $fieldKey || !isset($row[$fieldName])) continue;
				$records[count($records)-1][$fieldName] = array("label" => $this->_model->getFieldLabel($fieldName),"value" => $row[$fieldName]);
				}
			$records[count($records)-1]['remove'] = '<a href="'.$baseUrl.'/'.$module.$this->_controllerAction.'/remove?esc_id='.$row["esc_id"].'&type='.$this->type.'">Remover</a>';
			}
		return $records;
		}

	public function saveAction()
		{
		$this->redirect = true;
		$this->return_ajax = true;
		parent::saveAction();
		$this->_redirect($this->getRequest()->getModuleName());
		}

	public function _addFieldLockedThisForm($arrFields)
		{
	  $fields = $this->_getDataFromPost();
	  foreach($fields as $key => $value)
		  {
			if (in_array($key, $arrFields))
				$this->_model->addLockedField($key);
		  }
		}

	public function _getJs()
		{
		$js = "
		<script type='text/javascript'>
			var frm = document.getElementsByTagName('form')[0];
			frm.setAttribute('onSubmit','thisFormValid() ;return false;');

			function thisFormValid()
				{
				if(validaForm(frm))
					enviaDados(frm,frm.getAttribute(\"action\"));
				}
		</script>
			";
		return $js;
		}
	}