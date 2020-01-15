<?php

/**
 * Renders admin top menu
 */
class SSystemMenu extends CWidget {

	private $_items;

	/**
	 * Set default items
	 */
	public function init()
	{
		// Minimum configuration
		$this->_items = array(
			'users'=>array(
				'label'=>Yii::t('AdminModule', 'Система'),
				'position'=>1,
			),
			'catalog'=>array(
				'label'=>Yii::t('AdminModule', 'Каталог'),
				'position'=>3,
			),
			'cms'=>array(
				'label'=>Yii::t('AdminModule', 'Сайт'),
				'position'=>4,
			),
		);
	}

	/**
	 * Render menu
	 */
	public function run()
	{
		$found = $this->findMenuFiles();
		$items = CMap::mergeArray($this->_items, $found);

    if (Yii::app()->user->checkAccess('ViewManager') && !Yii::app()->user->getIsSuperuser()) $this->processRole($items);
		$this->processSorting($items);
		$this->widget('application.extensions.mbmenu.MbMenu', array('items'=>$items));
	}

	/**
	 * Sort menu items by position key.
	 * @param $items array menu items
	 */
	protected function processSorting(&$items)
	{
		uasort($items, "SSystemMenu::sortByPosition");
    foreach ($items as $key => $item)
    {
      if (isset($item['items']))
        $this->processSorting($items[$key]['items']);
    }
	}

  protected function processRole(&$items) {
    foreach ($items as $key => $item)
    {
      if(!isset($item['ManagerAccess']) || !$item['ManagerAccess']) {
        unset($items[$key]);
        continue;
      }
      if (isset($item['items'])) {
        foreach($item['items'] as $key2 => $item_params) {
           if(!isset($item_params['ManagerAccess']) || !$item_params['ManagerAccess']) {
             unset($items[$key]['items'][$key2]);
          }
        }
        $this->processRole($items[$key]['items']);
      }
    }
  }

	/**
	 * Find and load module menu files.
	 */
	protected function findMenuFiles()
	{
		$result = array();

		$installedModules = SystemModules::model()->findAll('enabled = true', array(
			'select'=>'name',
		));

		foreach($installedModules as $module)
		{
			$filePath = Yii::getPathOfAlias('application.modules.'.$module->name.'.config').DIRECTORY_SEPARATOR.'menu.php';
			if (file_exists($filePath))
				$result = CMap::mergeArray($result, require($filePath));
		}

		return $result;
	}

	/**
	 *  Sort an array
	 * @static
	 * @param  $a array
	 * @param  $b array
	 * @return int
	 */
	public static function sortByPosition($a, $b)
	{
		if (isset($a['position']) && isset($b['position']))
		{
			if ((int)$a['position'] === (int)$b['position'])
				return 0;
			return ((int)$a['position'] > (int)$b['position']) ? 1 : -1;
		}

		return 1;
	}

}