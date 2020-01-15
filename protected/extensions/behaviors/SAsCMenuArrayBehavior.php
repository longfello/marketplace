<?php

/**
 * Represent model as array needed to create CMenu.
 * Usage:
 * 	'SAsCMenuArrayBehavior'=>array(
 * 		'class'=>'ext.behaviors.SAsCMenuArrayBehavior',
 * 		'labelAttr'=>'name',
 * 		'urlExpression'=>'array("/store/category", "id"=>$model->id)',
 * TODO: Cache queries
 * 	)
 */
class SAsCMenuArrayBehavior extends CActiveRecordBehavior
{

	/**
	 * @var string Owner attribute to be placed in `label` key
	 */
	public $labelAttr;

	/**
	 * @var string Expression will be evaluated to create url.
	 * Example: 'urlExpression'=>'array("/store/category", "id"=>$model->id)',
	 */
	public $urlExpression;

	public function asCMenuArray()
	{
    return $this->walkArray($this->owner);
	}

	public function asCMenuArrayFirst()
	{
    return $this->walkArray3($this->owner);
	}

  public function asWithChilds(){
    $hash = 'asWithChilds-'.$this->owner->id;
    $val = Yii::app()->cache->get($hash);
    if (!$val) {
      $val = $this->walkArray2($this->owner);
      Yii::app()->cache->set($hash, $val, 30);
    }

    return $val;
  }

  protected function walkArray2($model)
  {
    $data = array($model->id);

    // TODO: Cache result
    $children = $model->cache(1)->children()->findAll();
    if(!empty($children))
    {
      foreach($children as $c)
        $data = array_merge($data, $this->walkArray2($c));
    }

    return $data;
  }

  protected function walkArray3($model)
  {
    $data = array(
      'label'=>$model->{$this->labelAttr},
      'url'=>$this->evaluateUrlExpression($this->urlExpression, array('model'=>$model)),
      'id'=>$model->id,
      'count'=>0
    );

    // TODO: Cache result
    $children = $model->cache(1)->children()->findAll();
    if(!empty($children))
    {
      foreach($children as $c)
        $data['items'][] = array(
          'label'=>$c->{$this->labelAttr},
          'url'=>$this->evaluateUrlExpression($this->urlExpression, array('model'=>$c)),
          'id'=>$c->id,
          'count'=>0
        );
    }

    return $data;
  }

	/**
	 * Recursively build menu array
	 * @param $model CActiveRecord model with NestedSet behavior
	 * @return array
	 */
	protected function walkArray($model)
	{
		$data = array(
			'label'=>$model->{$this->labelAttr},
			'url'=>$this->evaluateUrlExpression($this->urlExpression, array('model'=>$model)),
			'id'=>$model->id,
      'count'=>$model->calcProductsCount()
		);

		// TODO: Cache result
		$children = $model->cache(1)->children()->findAll();
		if(!empty($children))
		{
			foreach($children as $c)
				$data['items'][] = $this->walkArray($c);
		}

		return $data;
	}

	/**
	 * @param $expression
	 * @param array $data
	 * @return mixed
	 */
	public function evaluateUrlExpression($expression,$data=array())
	{
		extract($data);
		return eval('return '.$expression.';');
	}
}
