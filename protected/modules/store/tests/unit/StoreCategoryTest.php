<?php

class StoreCategoryTest extends CTestCase
{
	public function testLoadRootCategory()
	{
		// test store category for multilanguage mode
		$model = StoreCategory::model()->findByPk(1);
		$this->assertTrue($model instanceof  StoreCategory);
	}

	public function testLanguageLoad()
	{
		Yii::app()->i18n->setActive('en');
		$model = StoreCategory::model()->findByPk(1);
		$name = 'root_'.time();
		$model->name = $name;
		$model->saveNode();

		$this->assertTrue($model instanceof  StoreCategory);
		$this->assertEquals($model->name, $name);

		Yii::app()->i18n->setActive('ru');
		$model = StoreCategory::model()->findByPk(1);
		$this->assertEquals($model->name, 'root');
	}
}