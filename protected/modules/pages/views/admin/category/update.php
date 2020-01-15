<?php
    // Category create/edit view

    $this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
        'form'=>$form,
        'langSwitcher'=>!$model->isNewRecord,
        'deleteAction'=>$this->createUrl('/pages/admin/category/delete', array('id'=>$model->id))
    ));

    $title = ($model->isNewRecord) ? Yii::t('PagesModule', 'Создание категории') :
        Yii::t('PagesModule', 'Редактирование категории');

    $this->breadcrumbs = array(
        'Home'=>$this->createUrl('/admin'),
        Yii::t('PagesModule', 'Категории')=>$this->createUrl('index'),
        ($model->isNewRecord) ? Yii::t('PagesModule', 'Создание категории') : CHtml::encode($model->name),
    );

    $this->pageHeader = $title;
?>

<div class="form wide padding-all">
    <?php echo $form->asTabs() ?>
</div>
