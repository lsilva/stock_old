<?php
class ExperienciaCrudController extends Fgsl_Crud_Controller_Abstract
{
	public function init()
	{
		parent::init();
		Zend_Loader::loadClass('Profissional');

		$this->_useModules = true;
		$this->_uniqueTemplatesForApp = false;
		$this->_model = new Profissional();
		$this->_title = 'Experiencia Profissional';
		$this->_searchButtonLabel = 'Pesquisar';
		$this->_searchOptions = array('pro_nome_empresa'=>'Nome da empresa');
		$this->_config();
	}

	public function insertAction()
	  {
		$module = $this->_useModules ? "{$this->_moduleName}/" : '';
		$data = $this->_getDataFromPost();
		
		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."/$module{$this->_controllerAction}/save",
		Fgsl_Form_Edit::MODEL => $this->_model
		);
		$js = $this->_getJs();
	  $form = new Fgsl_Form_Edit($options);
	  $this->view->assign('js', $js);
	  $this->view->assign('form', $form);
	  }

	public function listAction()
		{
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

		$where = "cli_id='".$dataAuth->cli_id."' AND pro_id='".$get->pro_id."'";
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

    $js = $this->_getJs();
    $this->view->assign('js', $js);		
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
					//echo('<pre>');var_dump($row);exit;
				$content ="<table border='1' width='100%'>";
				$content.="	<tr>";
				$content.="		<td colspan='100%' align='right' bgcolor='silver'>".$row["edit"]." - ".$row["remove"]."</td>";
				$content.="	</tr>";
				$content.="	<tr>";
        $arrOption = $this->_model->getSelectOptions("pro_nivel_cargo");
				$content.="		<td><b>".$row["pro_cargo"]["label"]."</b></td>";
				$content.="		<td colspan='100%'>".$row["pro_cargo"]["value"]."</td>";
        $content.=" </tr>";       
        $content.=" <tr>";				
        $content.="   <td><b>".$row["pro_nivel_cargo"]["label"]."</b></td>";
        $content.="   <td>".$arrOption[$row["pro_nivel_cargo"]["value"]]."</td>";
        $content.="   <td><b>".$row["pro_dt_inicio"]["label"]."</b></td>";
        $content.="   <td>".$row["pro_dt_inicio"]["value"]."</td>";        
        $content.=" </tr>";       
        $content.=" <tr>";                
        $content.="   <td><b>".$row["pro_salario"]["label"]."</b></td>";
        $arrOption = $this->_model->getSelectOptions("pro_tipo_pgto");
        $content.="   <td>".$row["pro_salario"]["value"]." ( ".$arrOption[$row["pro_tipo_pgto"]["value"]]." )</td>";
        $content.="   <td><b>".$row["pro_dt_termino"]["label"]."</b></td>";
        $content.="   <td>".$row["pro_dt_termino"]["value"]."</td>";        
        $content.=" </tr>";
        $content.=" <tr>";
        $content.="   <td><b>".$row["pro_atividade"]["label"]."</b></td>";
        $content.="   <td colspan='100%'>".$row["pro_atividade"]["value"]."</td>";
        $content.=" </tr>";
        $content.=" <tr>";
        $content.="   <td><b>".$row["pro_nome_empresa"]["label"]."</b></td>";
        $content.="   <td>".$row["pro_nome_empresa"]["value"]."</td>";
        $arrOption = $this->_model->getSelectOptions("pro_ramo_empresa");
        $content.="   <td><b>".$row["pro_ramo_empresa"]["label"]."</b></td>";
        $content.="   <td>".$arrOption[$row["pro_ramo_empresa"]["value"]]."</td>";        
        $content.=" </tr>";        
				$content.="</table><br>";

				$table.=$content;
				};
			}
		return $table;
		}
	
	public function myGetPagedData()
		{
		$dataAuth = Fgsl_Session_Namespace::get('data_auth');
		$where = "cli_id='".$dataAuth->cli_id."'";
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
			$records[count($records)-1]["edit"] = '<a href="'.$baseUrl.'/'.$module.$this->_controllerAction.'/edit?pro_id='.$row["pro_id"].'">Editar</a>';
			foreach ($this->_fieldNames as $fieldName)
				{
				if ($fieldName == $fieldKey || !isset($row[$fieldName])) continue;
				$records[count($records)-1][$fieldName] = array("label" => $this->_model->getFieldLabel($fieldName),"value" => $row[$fieldName]);
				}
			$records[count($records)-1]['remove'] = '<a href="'.$baseUrl.'/'.$module.$this->_controllerAction.'/remove?pro_id='.$row["pro_id"].'">Remover</a>';
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