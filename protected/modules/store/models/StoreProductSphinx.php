<?php

class StoreProductSphinx
{
	public static function findBy(array $attrs){
    $clause = array();
    foreach($attrs as $key => $attr) {
      $attr = addslashes($attr);
      $clause[] = "@$key \"$attr\"";
    }
    $clause = implode(' AND ', $clause);
    $clause = $clause?" WHERE MATCH('$clause')":"";
    $model_ids = Yii::app()->sphinxDb->createCommand("SELECT id FROM rt_products $clause")->queryRow();
    // SELECT id FROM rt_products WHERE MATCH('@url "nokia"');
    return $model_ids?$model_ids['id']:false;
	}
}