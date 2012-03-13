<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function __construct($application)
	{
		parent::__construct($application);
		$this->_connectDatabase();
		$this->_loadComponents();
		$this->_sanitizeData();
	}

	private function _connectDatabase()
	{
		$resource = $this->getPluginResource('db');
		$db = $resource->getDbAdapter();
		Zend_Registry::set('db',$db);
	}

	private function _loadComponents()
	{
		require_once 'Zend/Loader.php';
		require_once 'Zend/Loader/Autoloader.php';
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Fgsl');
		$autoloader->registerNamespace('Zend');
		Zend_Loader::loadClass('RouteRuler');
	}

	private function _sanitizeData()
	{
		Fgsl_Session_Namespace::init('session');
		$filterInput = new Zend_Filter_Input(null,null,$_POST);
		$filterInput->setDefaultEscapeFilter('StripTags');
		Fgsl_Session_Namespace::set('post',$filterInput);
		//GET
		$filterURL = new Zend_Filter_Input(null,null,$_GET);
		$filterURL->setDefaultEscapeFilter('StripTags');
		Fgsl_Session_Namespace::set('get',$filterURL);

		Fgsl_Session_Namespace::set('ROUTE_RULER', new RouteRuler());
	}

}
