<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>

<?php if (Yii::app()->user->isGuest) {?>
    <div class="onpage">
        <?php $this->widget('application.components.widgets.loginbox',array('layout'=>"full")); ?>
    </div>
<?php } ?>