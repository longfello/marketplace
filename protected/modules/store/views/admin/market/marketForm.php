<?php
if (Yii::app()->user->getIsManager())
{
  return array(
    'id'=>'marketUpdateForm',
    'showErrorSummary'=>true,
    'enctype'=>'multipart/form-data',
    'elements'=>array(
      'content'=>array(
        'type'=>'form',
        'title'=>Yii::t('StoreModule.admin', 'Общая информация'),
        'elements'=>array(
          'name'=>array(
            'type'=>'text',
          ),
          'description'=>array(
            'type'=>'SRichTextarea',
          ),
          'url'=>array(
            'type'=>'text'
          ),
          'delivery_desc'=>array(
            'type'=>'SRichTextarea',
          ),
        ),
      )
    ),
  );
} else {
  return array(
    'id'=>'marketUpdateForm',
    'showErrorSummary'=>true,
    'enctype'=>'multipart/form-data',
    'elements'=>array(
      'content'=>array(
        'type'=>'form',
        'title'=>Yii::t('StoreModule.admin', 'Общая информация'),
        'elements'=>array(
          'name'=>array(
            'type'=>'text',
          ),
          'description'=>array(
            'type'=>'SRichTextarea',
          ),
          'user_id'=>array(
            'type'=>'text'
          ),
          'url'=>array(
            'type'=>'text'
          ),
          'is_active'=>array(
            'type'=>'dropdownlist',
            'items'=>array(
              1=>Yii::t('StoreModule.admin', 'Включен'),
              0=>Yii::t('StoreModule.admin', 'Выключен'),
              2=>Yii::t('StoreModule.admin', 'Заблокирован')
            ),
            'hint'=>Yii::t('StoreModule.admin', 'Статус магазина на сайте')
          ),
          'status_comment'=>array(
            'type'=>'text'
          ),
          'delivery_desc'=>array(
            'type'=>'SRichTextarea',
          ),
        ),
      )
    ),
  );
}

