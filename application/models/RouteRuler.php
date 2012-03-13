<?php
class RouteRuler implements Fgsl_Route_Ruler_Interface
{
	public function hasRouteStartup()
	{
		return false;
	}

	public function hasRouteShutdown()
	{
		return true;
	}

	public function hasRoutePreDispatch()
	{
		return false;
	}

	public function hasRoutePosDispatch()
	{
		return false;
	}

	public function getRouteStartup($currentRoute)
	{
		return $currentRoute;
	}

	public function getRouteShutdown($currentRoute)
	{
		$newRoute = $currentRoute;
		if ($currentRoute['module'] == 'default' || $currentRoute['module'] == 'admin')
		{
			if ($currentRoute['controller'] !== 'index' && $currentRoute['action'] !== 'pre-login')
			{
				$dataAuth = Fgsl_Session_Namespace::get('data_auth');
				if (!isset($dataAuth))
				{
					Fgsl_Session_Namespace::set('mensagem',"Acesso negado a esta p·gina.");
					$newRoute['controller'] = 'index';
					$newRoute['action'] = 'index';
				}
				else
				{
					$newRoute = $this->_routeAdminPostAcl($currentRoute);
				}
			}
		}
		return $newRoute;

	}

	public function getRoutePreDispatch($currentRoute)
	{
		return $currentRoute;
	}

	public function getRoutePosDispatch($currentRoute)
	{
		return $currentRoute;
	}

	private function _routeAdminPostAcl(array $currentRoute)
	{
		$newRoute = $currentRoute;
		$acl = Fgsl_Session_Namespace::get('acl');
		if (!$acl->hasRole('administrador'))
		{
			$roles = $acl->getRoles();
			$temAcesso = false;
			foreach($roles as $role)
			{
				if ($acl->isAllowed($role,$currentRoute['controller'],$currentRoute['action']))
				{
					$temAcesso = true;
					break;
				}
			}
			if (!$temAcesso)
			{
				$newRoute['controller'] = 'index';
				$newRoute['action'] = 'index';
				Fgsl_Session_Namespace::set('mensagem',"Acesso negado ao privil√©gio {$currentRoute['action']} do recurso {$currentRoute['controller']}");
			}
		}
		return $newRoute;
	}
}
