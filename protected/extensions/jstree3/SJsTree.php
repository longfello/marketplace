<?php

class SJsTree extends CWidget
{
	/**
	 * @var string Id of elements
	 */
	public $id;

	/**
	 * @var array of nodes. Each node must contain next attributes:
	 *  id - If of node
	 *  name - Name of none
	 *  hasChildren - boolean has node children
	 *  children - get children array
	 */
	public $data = array();

	/**
	 * @var array jstree options
	 */
	public $options = array();

  public $autoInit = true;

	/**
	 * @var CClientScript
	 */
	protected $cs;

	/**
	 * Initialize widget
	 */
	public function init()
	{
		$assetsUrl = Yii::app()->getAssetManager()->publish(
			Yii::getPathOfAlias('ext.jstree3.assets'),
			false,
			-1,
			YII_DEBUG
		);

		Yii::app()->getClientScript()->registerPackage('cookie');

		$this->cs = Yii::app()->getClientScript();
		$this->cs->registerCssFile($assetsUrl.'/themes/default/style.css');
		$this->cs->registerScriptFile($assetsUrl.'/jquery.jstree.js');
	}

	public function run()
	{
		echo CHtml::openTag('div', array(
			'id'=>$this->id,
		));
		echo CHtml::openTag('ul');
		$this->createHtmlTree($this->data);
		echo CHtml::closeTag('ul');
		echo CHtml::closeTag('div');

		$options = CJavaScript::encode($this->options);

    if ($this->autoInit) {
      $this->cs->registerScript('JsTreeScript', "$(document).ready(function(){ $('#{$this->id}').jstree({$options});});");
    } else {
      $this->cs->registerScript('JsTreeScriptWoInit_'.$this->id, "function initTree{$this->id}(){ $('#{$this->id}').jstree({$options});}", CClientScript::POS_END);
    }

	}

	/**
	 * Create ul html tree from data array
	 * @param string $data
	 */
	private function createHtmlTree($data)
	{
		foreach($data as $node)
		{
			echo CHtml::openTag('li', array(
				'id'      => $this->id.'Node_'.$node['id'],
        'data-id' => $node['id']
			));
      $class = isset($node['class'])?$node['class']:'';
			echo CHtml::link($node['name'], '#', array('class'=>$class));
			if ($node['hasChildren'] === true)
			{
				echo CHtml::openTag('ul');
				$this->createHtmlTree($node['children']);
				echo CHtml::closeTag('ul');
			}
			echo CHtml::closeTag('li');
		}
	}

}
