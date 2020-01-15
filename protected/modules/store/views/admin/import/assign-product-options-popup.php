<form class="form-horizontal popup-form-solution" role="form">
  <div class="well">
    <select class="category-solution form-control" name="option-assign-solution">
      <?php foreach(StoreImportAssignProductOptions::model()->getSolutions() as $key => $value) { ?>
        <?php if (($attr->type != StoreAttribute::TYPE_YESNO) || ($key != StoreImportAssignProductOptions::SOLUTION_CREATE)) { ?>
          <option value="<?=$key?>"><?=$value?></option>
        <?php } ?>
      <?php } ?>
    </select>

    <br>
    <div class="solution-hint solution-hint-none">При выборе данного решения источник импорта не будет направлен на проверку</div>
    <div class="solution-hint solution-hint-assign">Сопоставить значение атрибута уже существующему.</div>
    <div class="solution-hint solution-hint-skip">Пропустить значение атрибута. Информация о значении данного аттрибута будет проигнорирована.</div>

    <div class="solution-hint solution-hint-create">
      <input type="text" name='option-assign-elaboration' class="form-control" id="input-create" placeholder="Название значения атрибута">
      <br>
      Создать новое значение атрибута.
    </div>
    <br>

    <div class="treeWrapper">
      <?php
        $this->widget('ext.jstree3.SJsTree', array(
          'id'=>'StoreOptionsTree',
          'data'=>$options,
          'options'=>array(
            'core'=>array(
              'initially_open'=>'StoreOptionsTreeNode_1',
              'multiple' => false,
            ),
            'plugins'=>array('themes','html_data','checkbox'),
            'checkbox'=>array(
              'two_state'=>true,
              'three_state'=>false,
            ),
          ),
        ));
      ?>
    </div>
  </div>


  <textarea name="option-assign-comment" class='solution-comment form-control' placeholder="Комментарий к решению"></textarea>

  <input type="hidden" name="option-assign-oid" value="<?=$id?>">
  <input type="hidden" class='option-assign-value' name="option-assign-value" value="">

  <input type="hidden" class="selected-category" name="option-assign-spid" value="">
  <input type="hidden" name="YII_CSRF_TOKEN" value="<?=Yii::app()->request->csrfToken?>">
</form>
