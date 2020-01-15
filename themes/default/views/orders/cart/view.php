<?php

/**
 * View order
 * @var Order $model
 */

//$title = Yii::t('OrdersModule', 'Просмотр заказа #{id}', array('{id}'=>$model->id));
$title = Yii::t('OrdersModule', 'Просмотр заказа');
$this->pageTitle = $title;

?>

<?php
$tabs = array();

foreach ($orders as $model) {

  $content = "
    <div class='order_products'>
      <table width='100%'>
        <thead>
        <tr>
          <td></td>
          <td>".Yii::t('OrdersModule', 'Количество')."</td>
          <td>".Yii::t('OrdersModule', 'Сумма')."</td>
        </tr>
        </thead>
        <tbody>";
        foreach($model->getOrderedProducts()->getData() as $product) {
        $content .= "<tr>
          <td>";
            $content .= CHtml::openTag('h3');
            $content .= $product->getRenderFullName(false);
            $content .= CHtml::closeTag('h3');

            $content .= CHtml::openTag('span', array('class'=>'price'));
            $content .= StoreProduct::formatPrice(Yii::app()->currency->convert($product->price));
            $content .= Yii::app()->currency->active->symbol;
            $content .= CHtml::closeTag('span');
            $content .="</td>
          <td>".$product->quantity."</td>
          <td>".StoreProduct::formatPrice(Yii::app()->currency->convert($product->price * $product->quantity)).Yii::app()->currency->active->symbol."</td>
        </tr>";
        }
      $content .= "</tbody>
      </table>

      <div class='order_data mt10'>
        <div class='user_data rc5'>
          <h2>".Yii::t('OrdersModule', 'Данные получателя')."</h2>

          <div class='form wide'>
            <div class='row'>
              ".Yii::t('OrdersModule', 'Доставка').":".CHtml::encode($model->delivery_name)."
            </div>
            <div class='row'>
              ".Yii::t('OrdersModule', 'Стоимость').":".StoreProduct::formatPrice(Yii::app()->currency->convert($model->delivery_price)).Yii::app()->currency->active->symbol."
            </div>
            <div class='row'>
              ".CHtml::encode($model->user_name)."
            </div>
            <div class='row'>
              ".CHtml::encode($model->user_email)."
            </div>
            <div class='row'>
              ".CHtml::encode($model->user_phone)."
            </div>
            <div class='row'>
              ".CHtml::encode($model->user_address)."
            </div>
            <div class='row'>
              ".CHtml::encode($model->user_comment)."
            </div>
          </div>
        </div>
      </div>";


      foreach($model->deliveryMethod->paymentMethods as $payment) {
        $content .= "<div class='order_data mt10 '>
                      <div class='user_data rc5 activeHover'>
                        <h3>".$payment->name."</h3>
                        <p>".$payment->description."</p>
                        <p>".$payment->renderPaymentForm($model)."</p>
                      </div>
                    </div>";
      }


  $content .= "<div class='recount_order'>
        <span class='total'>".Yii::t('OrdersModule', 'Всего к оплате:')."</span>
        <span id='total'>".StoreProduct::formatPrice(Yii::app()->currency->convert($model->full_price)).Yii::app()->currency->active->symbol."
        </span>
      </div>
      <div style='clear: both;'></div>
    </div>";

  $tabs[Yii::t('OrdersModule', 'Просмотр заказа #{id}', array('{id}'=>$model->id))] = array('content'=>$content);
}

$this->widget('zii.widgets.jui.CJuiTabs', array(
  'id'=>'tabs',
  'tabs'=>$tabs
));
?>
