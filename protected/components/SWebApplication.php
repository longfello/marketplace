<?php

/**
 * Main application class.
 *
 * @package app.components
 *
 * @property SSystemSettings $settings
 * @property SCurrencyManager $currency
 * @property SCart $cart
 * @property RediskaConnection $RediskaConnection
 * @property CDbConnection $sphinxDb
 * @property CDbConnection $db_import
 * @property CAssetManager $assetManager
 * @property CHttpRequest $request
 * @property I18n $i18n
 * @property BaseUser $user The user session information.
 * @property EConfig $config
 * @property CDbConnectionManager $dbConnectionManager
 * @property DGSphinxSearch sphinx
 * @property Gearmand gearman
 * @method BaseUser getUser
 */
class SWebApplication extends CWebApplication
{

	private $_theme=null;

	/**
	 * @param null $config
	 */
	public function __construct($config=null)
	{
		parent::__construct($config);
	}

	/**
	 * Initialize component
	 */
	public function init()
	{
		$this->setSystemModules();
		parent::init();
	}

	/**
	 * Set enabled system modules to enable url access.
	 */
	protected function setSystemModules()
	{
		// Enable installed modules
		$modules = SystemModules::getEnabled();

		if($modules)
		{
			foreach($modules as $module)
				$this->setModules(array($module->name));
		}
	}

	/**
	 * @return CTheme
	 */
	public function getTheme()
	{
		if($this->_theme===null)
			$this->_theme=$this->getThemeManager()->getTheme(Yii::app()->settings->get('core', 'theme'));
		return $this->_theme;
	}
}