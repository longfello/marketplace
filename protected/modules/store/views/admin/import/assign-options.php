<?php
  /* @var Controller $this */
  $this->pageHeader = Yii::t('StoreModule', 'Импорт товаров - сопоставление данных:').' '.
    CHtml::link(Yii::t('StoreModule','Старт'), $this->createUrl('/admin/store/import/assign/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Категории'), $this->createUrl('/admin/store/import/assignCategories/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Производители'), $this->createUrl('/admin/store/import/assignVendors/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Атрибуты')).' > '.
    Yii::t('StoreModule','Финиш');

  $this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('StoreModule', 'Импорт товаров') => $this->createUrl('/admin/store/import'),
    Yii::t('StoreModule', 'Сопоставление данных'),
  );
?>

<div class="modal fade" id="solution_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Выберите решение для производителя '<span class="category_name"></span>':</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal popup-form-solution" role="form">
          <div class="well">
            <select class="category-solution form-control" name="option-assign[solution]">
              <?php foreach(StoreImportAssignCategory::model()->getSolutions() as $key => $value) { ?>
                <option value="<?=$key?>"><?=$value?></option>
              <?php } ?>
            </select>

            <br>
            <div class="solution-hint solution-hint-none">При выборе данного решения источник импорта не будет направлен на проверку</div>
            <div class="solution-hint solution-hint-assign">Сопоставить атрибут уже существующему.</div>
            <div class="solution-hint solution-hint-skip">Пропустить атрибут. Информация о данном аттрибуте будет проигнорирована.</div>

            <div class="solution-hint solution-hint-create">
              <select name="option-type" class="form-control" id="select-spid-create">
                <option value="<?=StoreAttribute::TYPE_DROPDOWN?>">Выпадающий список</option>
                <option value="<?=StoreAttribute::TYPE_YESNO?>">Логическое значение (Да/Нет)</option>
                <option value="<?=StoreAttribute::TYPE_TEXT?>">Произвольное значение (не отображается в фильтре)</option>
              </select>
              <input type="text" name='option-assign[elaboration]' class="form-control" id="input-create" placeholder="Название атрибута">
              <br>
              Создать новый атрибут. При выборе данного решения администртор приймет решение о целесообразности создания нового атрибута.
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


          <textarea name="option-assign[comment]" class='solution-comment form-control' placeholder="Комментарий к решению"></textarea>

          <input type="hidden" class="selected-category" name="option-assign[spid]" value="">
          <input type="hidden" class="solution-cid" name="option-assign-cid" value="">
          <input type="hidden" name="YII_CSRF_TOKEN" value="<?=Yii::app()->request->csrfToken?>">
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary btn-save">Сохранить</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="form wide padding-all">
  <?php
    $solutions = StoreImportAssignOptions::model()->getSolutions();
    $this->widget('zii.widgets.grid.CGridView', array(
      'id'=>'StoreImportOptionsTree',
      'dataProvider'=> $dataProvider,
      'filter'  => $filter,
      'ajaxUpdate' => false,
      'ajaxVar' => false,
      'columns' => array(
        array(
          'name'  => 'Название',
          'type'  => 'raw',
          'value' => '$data["name"].$data["list"]'
        ),
        array(
          'name'  => 'solution',
          'type'  => 'raw',
          'value' => 'getCellName($data)',
          'filter' => $solutions
        ),
        array(
          'name'  => 'Комментарий',
          'value' => 'isset($data["comment"])?$data["comment"]:""'
        ),
        array(
          'name'   => 'isNew',
          'type'  => 'raw',
          'value'  => 'StoreImportAssignOptions::model()->getIsConfirmed($data["isNew"])',
          'filter' => StoreImportAssignOptions::model()->getIsConfirmed()
        ),
      ),
    ));
    function getCellName($data){
      $text = "<span class='alert-danger'>Новый атрибут - пропустить </span>";
      if (isset($data["solution"])){
        $titles = StoreImportAssignOptions::model()->getSolutions();
        $text = $titles[$data["solution"]];
        switch($data["solution"]){
          case StoreImportAssignOptions::SOLUTION_CREATE:
            $text .= " атрибут \"{$data['elaboration']}\" ";
            switch ($data['spid']) {
              case 1:
                $text .= ' типа "произвольное значение"';
                break;
              case 3:
                $text .= ' типа "выпадающий список"';
                break;
              case 7:
                $text .= ' типа "логическое значение"';
                break;
            }
            break;
          case StoreImportAssignOptions::SOLUTION_ASSIGN:
            $spid = $data['spid'];
            $model = StoreAttribute::model()->findByPk($spid);
            if ($model) {
              $text .= ' атрибуту "'.$model->title.'"';
            } else {
              $spid = -$spid;
              $model_correct = StoreImportAssignOptions::model()->findByAttributes(array(
                'sid' => $data['sid'],
                'cid' => $spid,
                'solution' => StoreImportAssignOptions::SOLUTION_CREATE,
              ));
              if ($model_correct) {
                $text .= "'".$model_correct->elaboration."'";
              } else {
                $model_correct = StoreImportAssignOptions::model()->findByAttributes(array(
                  'sid' => $data['sid'],
                  'cid' => $data['cid']
                ));
                $model_correct->solution = StoreImportAssignOptions::SOLUTION_NONE;
                $model_correct->save();
                $text = '<span class="alert-danger">Удален базовый атрибут</span> - '.$titles[StoreImportAssignOptions::SOLUTION_NONE];
              }
            }
            break;
          default:
        }
      } else {
        $data["solution"] = 'none';
      }
      $attrs = '';
      foreach($data as $key => $value){
        $value = CHtml::encode($value);
        $attrs .= "data-$key='$value' ";
      }
      $text = "<a href='#' class='show_solutions solution-{$data["solution"]}' $attrs>$text</a>";
      return $text;
    }
  ?>

  <div class="well">
    <ul>
      <?php foreach($stat as $one) { ?>
        <li><?=$one['name']?>: <?=$one['count']?></li>
      <?php } ?>
      <li>Осталось принять решений: <?=$stat['overall']['count']-$stat['total']['count']?></li>
    </ul>
    <?php if ($stat['overall']['count']-$stat['total']['count'] === 0) { ?>
      <br>
      <div class="bg-info padding-all">
        <br>
        <p>Все решения относительно ассоциации атрибутов приняты. Помните, что все значения атрибутов будут проверятся администраторами и могут появится на сайте позже, чем будет проверен и утвержден источник импорта.</p>
        <br>
        <a href="/admin/store/import/assignProductOptions/id/<?=$sid?>" class="btn btn-primary">Далее</a>
        <br>
      </div>
    <?php } ?>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.category-solution').on('change', function(){
      $('.solution-hint').hide();
      $('.solution-hint-'+$(this).val()).show();
      switch($(this).val()){
        case 'assign':
          $('.treeWrapper').show();
          break;
        default:
          $('.treeWrapper').hide();
      }
    });
    $(document).on('click', '#StoreImportOptionsTree .show_solutions', function(e){
      e.preventDefault();

      var data=$(this).data();
      var solution = data.solution?data.solution:'<?=StoreImportAssignCategory::SOLUTION_SKIP?>';
      $('.category-solution').val(solution).change();
      $('.solution-comment').val(data.comment);
      $('.solution-cid').val(data.id);

      $('#StoreOptionsTree').jstree('close_all');
      $('#StoreOptionsTree').jstree('deselect_all');
      expandNode('StoreOptionsTreeNode_1');
      if (data.spid) {
        expandNode('StoreOptionsTreeNode_'+data.spid);
        $('#StoreOptionsTree').jstree('select_node', 'StoreOptionsTreeNode_'+data.spid);
        $('#select-spid-create option').removeAttr('selected');
        $('#select-spid-create option[value='+data.spid+']').attr('selected','selected');
      }

      var name = data.elaboration?data.elaboration:data.real_name;
      $('#input-create').val(name);
      $('.category_name').html(data.real_name);
      $('#solution_modal').modal();
    });

    $("#StoreOptionsTree").bind("select_node.jstree", function(evt, data){
      if ($('#'+data.selected[0])) {
        $('.selected-category').val($('#'+data.selected[0]).data('id'));
      }
    });

    $(document).on('click', '.btn-save', function(e){
      e.preventDefault();
      var data = $('.popup-form-solution').serializeArray();
      $.post('',data, function(){
        $.fn.yiiGridView.update("StoreImportOptionsTree");
        $('#solution_modal').modal('hide');
      });
    })
  });

  function expandNode(nodeID) {
    // Expand all nodes up to the root (the id of the root returns as '#')
    var level = 10;
    while (nodeID != '#' && level > 0) {
      level--;
      // Open this node
      $("#StoreOptionsTree").jstree("open_node", nodeID)
      // Get the jstree object for this node
      var thisNode = $("#StoreOptionsTree").jstree("get_node", nodeID);
      // Get the id of the parent of this node
      nodeID = $("#StoreOptionsTree").jstree("get_parent", thisNode);
    }
  }
</script>