<?php

  class someProducts extends CWidget {
    public $layout = 'index';
    /* @var StoreProduct */
    public $model;

    function run(){

      $search_for = $this->model->name;

      $table = 'ri_related';
      $s = Yii::app()->sphinx;
      $s->setMatchMode(SPH_MATCH_EXTENDED2);
      $s->setMaxQueryTime(300);
//      $s->SetRankingMode(SPH_RANK_SPH04);
      $s->setRankingMode(SPH_SORT_EXPR, 'sum((4*lcs+2*(min_hit_pos==1)+exact_hit*100)*user_weight)*1000+bm25');
      $s->SetSortMode(SPH_SORT_RELEVANCE);
      $s->SetLimits(0, 20, 20);
      $relevance = array ('name' => 100, 'category_id' => 100, 'product_id' => 0);
      $s->SetFieldWeights($relevance);

      $query = Helper::GetSphinxKeyword($search_for);
      $query .= " & (@category_id {$this->model->mainCategory->id})";
      $result = $s->Query($query, $table);
      $res = array();

      if (isset($result['matches']) && is_array($result['matches'])) {
        foreach($result['matches'] as $one) {
          $id = $one['attrs']['product_id'];
          if (($this->model->id != $id) && !isset($res[$id])) {
            $product = StoreProduct::model()->active()->findByPk($id);
            if ($product) {
              $res[$id] = $product;
            }
          }
        }
      }
//      Helper::print_r($res, false, true);

      // $res = array_slice($res, 0, 10);

      $this->render('someProducts/'.$this->layout, array(
        'products' => $res,
      ));
    }

  }