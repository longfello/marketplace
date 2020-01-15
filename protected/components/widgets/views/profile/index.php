<div class="profile-nav">
  <?php if (Yii::app()->user->checkAccess('AdminEntrance')) { ?>
    <div class="ul-title"><span><?=Yii::t('widget-profile','Управление магазином')?></span></div>
    <ul>
      <li><a href="<?=Yii::app()->createUrl('admin')?>"><?=Yii::t('widget-profile','Административная часть')?></a></li>
    </ul>

    <div class="ul-title"><span><?=Yii::t('widget-profile','Каталог')?></span></div>
    <ul>
      <li><a href="/admin/store/products.html"><?=Yii::t('widget-profile','Продукты')?></a></li>
      <li><a href="/admin/discounts.html"><?=Yii::t('widget-profile','Скидки')?></a></li>
      <li><a href="/admin/store/delivery.html"><?=Yii::t('widget-profile','Доставка')?></a></li>
      <li><a href="/admin/store/import.html"><?=Yii::t('widget-profile','Импорт')?></a></li>
      <li><a href="/admin/store/market.html"><?=Yii::t('widget-profile','Магазин')?></a></li>
      <li><a href="/admin/store/import.html"><?=Yii::t('widget-profile','Загрузить товары')?></a></li>
    </ul>

  <?php } ?>

  <div class="ul-title"><span><?=Yii::t('widget-profile','Мой счет')?></span></div>
  <ul>
    <li><a ><?=Yii::t('widget-profile','Мои платежи')?></a></li>
  </ul>

  <div class="ul-title"><span><?=Yii::t('widget-profile','Заказы')?></span></div>
  <ul>
    <li><a href="<?=Yii::app()->createUrl('cart')?>"><?=Yii::t('widget-profile','Моя корзина')?></a></li>
    <li><a href="<?=Yii::app()->createUrl('users/profile/orders')?>"><?=Yii::t('widget-profile','Мои заказы')?></a></li>
    <?php if (Yii::app()->user->checkAccess('AdminEntrance')) { ?>
      <li><a href="/admin/orders/orders.html"><?=Yii::t('widget-profile','Все заказы')?></a></li>
      <li><a href="/admin/orders/orders/create.html"><?=Yii::t('widget-profile','Создать заказ')?></a></li>
    <?php } ?>
  </ul>

  <div class="ul-title"><span><?=Yii::t('widget-profile','Рассылки и оповещения')?></span></div>
  <ul>
    <li><a href="<?=Yii::app()->createUrl('users/profile/messages')?>"><?=Yii::t('widget-profile','Сообщения')?></a></li>
    <?php if (Yii::app()->user->checkAccess('AdminEntrance')) { ?>
      <li><a href="/admin/comments/index.html"><?=Yii::t('widget-profile','Комментарии')?></a></li>
    <?php } ?>
  </ul>

  <div class="ul-title"><span><?=Yii::t('widget-profile','Персональная страница')?></span></div>
  <ul>
    <li><a href="<?=Yii::app()->createUrl('users/profile/reviews')?>"><?=Yii::t('widget-profile','Мои отзывы')?></a></li>
    <li><a href="<?=Yii::app()->createUrl('users/profile/wishlist')?>"><?=Yii::t('widget-profile','Wish-лист')?></a></li>
    <li><a ><?=Yii::t('widget-profile','Закладки')?></a></li>
  </ul>

  <div class="ul-title"><span><?=Yii::t('widget-profile','Личная информация')?></span></div>
  <ul>
    <li><a href="<?=Yii::app()->createUrl('users/profile/edit')?>"><?=Yii::t('widget-profile','Изменить регистрационные данные')?></a></li>
    <li><a href="<?=Yii::app()->createUrl('users/profile/editPassword')?>"><?=Yii::t('widget-profile','Изменить пароль')?></a></li>
    <li><a href="<?=Yii::app()->createUrl('users/profile/remind')?>"><?=Yii::t('widget-profile','Забыли пароль?')?></a></li>
  </ul>

</div>