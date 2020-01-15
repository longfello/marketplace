<?php
  /* @var Controller $this */
  $this->pageHeader = Yii::t('StoreModule', 'Импорт товаров - сопоставление данных:').' '.
    CHtml::link(Yii::t('StoreModule','Старт'), $this->createUrl('/admin/store/import/assign/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Категории'), $this->createUrl('/admin/store/import/assignCategories/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Производители')).' > '.
    Yii::t('StoreModule','Атрибуты').' > '.
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
            <select class="category-solution form-control" name="vendor-assign[solution]">
              <?php foreach(StoreImportAssignCategory::model()->getSolutions() as $key => $value) { ?>
                <option value="<?=$key?>"><?=$value?></option>
              <?php } ?>
            </select>

            <br>
            <div class="solution-hint solution-hint-none">При выборе данного решения источник импорта не будет направлен на проверку</div>
            <div class="solution-hint solution-hint-assign">Сопоставить производителя уже существующему.</div>
            <div class="solution-hint solution-hint-skip">Пропустить производителя. Информация о данном производителе будет проигнорирована.</div>

            <div class="solution-hint solution-hint-create">
              <input type="text" name='vendor-assign[elaboration]' class="form-control" id="input-create" placeholder="Название производителя">
              <br>
              Создать нового производителя. При выборе данного решения администртор приймет решение о целесообразности создания нового производителя.
            </div>
            <br>

            <div class="treeWrapper">
              <?php
                $this->widget('ext.jstree3.SJsTree', array(
                  'id'=>'StoreVendorsTree',
                  'data'=>$vendors,
                  'options'=>array(
                    'core'=>array(
                      'initially_open'=>'StoreVendorsTreeNode_1',
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


          <textarea name="vendor-assign[comment]" class='solution-comment form-control' placeholder="Комментарий к решению"></textarea>

          <input type="hidden" class="selected-category" name="vendor-assign[spid]" value="">
          <input type="hidden" class="solution-cid" name="vendor-assign-cid" value="">
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
    $solutions = StoreImportAssignVendors::model()->getSolutions();
    $this->widget('zii.widgets.grid.CGridView', array(
      'id'=>'StoreImportVendorsTree',
      'dataProvider'=> $dataProvider,
      'filter'  => $filter,
      'ajaxUpdate' => false,
      'ajaxVar' => false,
      'columns' => array(
        array(
          'name'  => 'Название',
          'value' => '$data["name"]'
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
          'value'  => 'StoreImportAssignVendors::model()->getIsConfirmed($data["isNew"])',
          'filter' => StoreImportAssignVendors::model()->getIsConfirmed()
        ),
      ),
    ));
    function getCellName($data){
      $text = "<span class='alert-danger'>Новый производитель - пропустить </span>";
      if (isset($data["solution"])){
        $titles = StoreImportAssignVendors::model()->getSolutions();
        $text = $titles[$data["solution"]];
        switch($data["solution"]){
          case StoreImportAssignVendors::SOLUTION_CREATE:
            $text .= " производителя '{$data['elaboration']}' ";
            break;
          case StoreImportAssignVendors::SOLUTION_ASSIGN:
            $spid = $data['spid'];
            $model = StoreManufacturer::model()->findByPk($spid);
            if ($model) {
              $text .= " производителю ".$model->name;
            } else {
              $spid = -$spid;
              $model_correct = StoreImportAssignVendors::model()->findByAttributes(array(
                'sid' => $data['sid'],
                'cid' => $spid,
                'solution' => StoreImportAssignVendors::SOLUTION_CREATE,
              ));
              if ($model_correct) {
                $text .= "'".$model_correct->elaboration."'";
              } else {
                $model_correct = StoreImportAssignVendors::model()->findByAttributes(array(
                  'sid' => $data['sid'],
                  'cid' => $data['cid']
                ));
                $model_correct->solution = StoreImportAssignVendors::SOLUTION_NONE;
                $model_correct->save();
                $text = '<span class="alert-danger">Удален базовый производитель</span> - '.$titles[StoreImportAssignVendors::SOLUTION_NONE];
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
        <p>Все решения относительно ассоциации производителей приняты, теперь вы можете перейти к ассоциации атрибутов.</p>
        <br>
        <a href="/admin/store/import/assignOptions/id/<?=$sid?>" class="btn btn-primary">Далее</a>
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
    $(document).on('click', '#StoreImportVendorsTree .show_solutions', function(e){
      e.preventDefault();

      var data=$(this).data();
      var solution = data.solution?data.solution:'<?=StoreImportAssignCategory::SOLUTION_SKIP?>';
      $('.category-solution').val(solution).change();
      $('.solution-comment').val(data.comment);
      $('.solution-cid').val(data.id);

      $('#StoreVendorsTree').jstree('close_all');
      $('#StoreVendorsTree').jstree('deselect_all');
      expandNode('StoreVendorsTreeNode_1');
      if (data.spid) {
        expandNode('StoreVendorsTreeNode_'+data.spid);
        $('#StoreVendorsTree').jstree('select_node', 'StoreVendorsTreeNode_'+data.spid);
      }

      var name = data.elaboration?data.elaboration:data.real_name;
      $('#input-create').val(name);
      $('.category_name').html(data.real_name);
      $('#solution_modal').modal();
    });

    $("#StoreVendorsTree").bind("select_node.jstree", function(evt, data){
      if ($('#'+data.selected[0])) {
        $('.selected-category').val($('#'+data.selected[0]).data('id'));
      }
    });

    $(document).on('click', '.btn-save', function(e){
      e.preventDefault();
      var data = $('.popup-form-solution').serializeArray();
      $.post('',data, function(){
        $.fn.yiiGridView.update("StoreImportVendorsTree");
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
      $("#StoreVendorsTree").jstree("open_node", nodeID)
      // Get the jstree object for this node
      var thisNode = $("#StoreVendorsTree").jstree("get_node", nodeID);
      // Get the id of the parent of this node
      nodeID = $("#StoreVendorsTree").jstree("get_parent", thisNode);
    }
  }
</script>