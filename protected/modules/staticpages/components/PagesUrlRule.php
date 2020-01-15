<?php

class PagesUrlRule extends CBaseUrlRule {

	public function createUrl($manager, $route, $params, $ampersand)
	{
		$pathsMap = Yii::app()->getModule('staticpages')->getPathsMap();

		if ($route === 'staticpages/default/view' && isset($params['id'], $pathsMap[$params['id']]))
			return $pathsMap[$params['id']] . $manager->urlSuffix;
		else
			return false;
	}

	public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
	{
		$pathsMap = Yii::app()->getModule('staticpages')->getPathsMap();

		$id = array_search($pathInfo, $pathsMap);

		if ($id === false)
			return false;

		$_GET['id'] = $id;
		return 'staticpages/default/view';
	}

}