<?php

class StaticPage extends CMultiLangActiveRecord {
	public $_parent_id;
	public $_slug;
  public $MultiLangFields = array('page_title', 'content', 'meta_title', 'meta_description', 'meta_keywords');

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'pages';
	}

	public function rules()
	{
		return array(
			array('slug, page_title_ru, meta_title_ru, page_title_en, meta_title_en', 'required'),
			array('content_ru, meta_description_ru, meta_keywords_ru,  content_en, meta_description_en, meta_keywords_en, parent_id, layout', 'safe'),
			array('parent_id', 'compare', 'operator' => '!=', 'compareAttribute' => 'id', 'allowEmpty' => true, 'message' => 'Узел не может быть сам себе родителем.'),
			array('slug', 'match', 'pattern' => '/^[\w][\w\-]*+$/', 'message' => 'Разрешённые символы: строчные буквы латинского алфавита, цифры, дефис.'),
			array('page_title_ru, page_title_en', 'match', 'pattern' => '/^\d+$/', 'not' => true, 'message' => 'Заголовок страницы не может состоять из одного числа.'), // иначе будут проблемы при генерации хлебных крошек
			array('layout', 'default', 'setOnEmpty' => true, 'value' => null),
			array('is_published', 'boolean'),
			array('id, slug, page_title_ru, page_title_en, is_published', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'                  => 'ID',
			'lft'                 => 'Левый ключ',
			'rgt'                 => 'Правый ключ',
			'level'               => 'Уровень',
			'parent_id'           => 'Родитель',
			'slug'                => 'Текстовый идентификатор',
			'layout'              => 'Шаблон',
			'is_published'        => 'Опубликована',
			'page_title_ru'       => 'Заголовк',
			'content_ru'          => 'Содержимое страницы',
			'meta_title_ru'       => 'Мета-заголовок',
			'meta_description_ru' => 'Описание страницы',
			'meta_keywords_ru'    => 'Ключевые слова',
			'page_title_en'       => 'Заголовк',
			'content_en'          => 'Содержимое страницы',
			'meta_title_en'       => 'Мета-заголовок',
			'meta_description_en' => 'Описание страницы',
			'meta_keywords_en'    => 'Ключевые слова',
		);
	}

	public function defaultScope()
	{
		return array(
			'order' => 'root, lft',
		);
	}

	public function scopes()
	{
		return array(
			'published' => array(
				'condition' => 'is_published = 1',
			),
		);
	}

	public function behaviors()
	{
		return array(
			'nestedSetBehavior' => array(
				'class' => 'ext.nested-set.NestedSetBehavior',
				'leftAttribute' => 'lft',
				'rightAttribute' => 'rgt',
				'levelAttribute' => 'level',
				'rootAttribute' => 'root',
				'hasManyRoots' => true,
			),
		);
	}

	protected function afterFind()
	{
		parent::afterFind();

		$this->_parent_id = $this->parent_id;
		$this->_slug = $this->slug;
	}

	protected function afterSave()
	{
		parent::afterSave();

		if ($this->parent_id !== $this->_parent_id || $this->slug !== $this->_slug)
			Yii::app()->getModule('staticpages')->updatePathsMap();
	}

	protected function afterDelete()
	{
		parent::afterDelete();

		Yii::app()->getModule('staticpages')->updatePathsMap();
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('slug', $this->slug, true);
		$criteria->compare('page_title_ru', $this->page_title_ru, true);
		$criteria->compare('page_title_en', $this->page_title_en, true);
		$criteria->compare('is_published', $this->is_published);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getBreadcrumbs()
	{
		$ancestors = $this->ancestors()->findAll();
		$output = array();
		foreach ($ancestors as $ancestor)
			$output[$ancestor->page_title] = Yii::app()->urlManager->createUrl('/pages/default/view', array('id' => $ancestor->id));
		array_push($output, $this->page_title);
		return $output;
	}

	/**
	 * Формирует массив из страниц для использования в выпадающем меню, например, при выборе родителя узла.
	 */
	public function selectList()
	{
		$output = array();
		$nodes = $this->findAll();
		foreach ($nodes as $node)
			$output[$node->id] = str_repeat('  ', $node->level - 1) . $node->page_title;
		return $output;
	}

}