<?php
  /* @var Controller $this */
  $this->pageHeader = Yii::t('StoreModule', 'Импорт товаров - сопоставление данных:').' '.
    CHtml::link(Yii::t('StoreModule','Старт'), $this->createUrl('/admin/store/import/assign/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Категории')).' > '.
    Yii::t('StoreModule','Производители').' > '.
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
        <h4 class="modal-title">Выберите решение для категории '<span class="category_name"></span>':</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal popup-form-solution" role="form">
          <div class="well">
            <select class="category-solution form-control" name="category-assign[solution]">
              <?php foreach(StoreImportAssignCategory::model()->getSolutions() as $key => $value) { ?>
                <option value="<?=$key?>"><?=$value?></option>
              <?php } ?>
            </select>

            <br>
            <div class="solution-hint solution-hint-none">При выборе данного решения источник импорта не будет направлен на проверку</div>
            <div class="solution-hint solution-hint-assign">Сопоставить выбранную категорию уже существующей. Все товары данной категории будут расположены в выбранной категории.</div>
            <div class="solution-hint solution-hint-skip">Пропустить товары данной категории. При выборе данного решения все товары данной категории будут проигнорированы.</div>

            <div class="solution-hint solution-hint-create">
              <input type="text" name='category-assign[elaboration]' class="form-control" id="input-create" placeholder="Название категории">
              <br>
              Создать новую категорию в выбранной. При выборе данного решения администртор приймет решение о целесообразности создания новой категории.
            </div>
            <br>

            <div class="treeWrapper">
              <?php
                $this->widget('ext.jstree3.SJsTree', array(
                  'id'=>'StoreCategoryTree',
                  'data'=>$categories,
                  // 'data'=>array(),
                  // 'autoInit' => false,
                  'options'=>array(
                    'core'=>array(
                      'initially_open'=>'StoreCategoryTreeNode_1',
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


          <textarea name="category-assign[comment]" class='solution-comment form-control' placeholder="Комментарий к решению"></textarea>

          <input type="hidden" class="selected-category" name="category-assign[spid]" value="">
          <input type="hidden" class="solution-cid" name="category-assign-cid" value="">
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
    $solutions = StoreImportAssignCategory::model()->getSolutions();
    $this->widget('zii.widgets.grid.CGridView', array(
      'id'=>'StoreImportCategoryTree',
      'dataProvider'=> $dataProvider,
      'filter'  => $filter,
      'ajaxUpdate' => false,
      'ajaxVar' => false,
      'columns' => array(
        array(
          'name'  => 'id',
          'value' => '$data["id"]'
        ),
        array(
          'name'  => 'Название',
          'value' => '$data["name"] . " (".$data["count"].")"'
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
          'value'  => 'StoreImportAssignCategory::model()->getIsConfirmed($data["isNew"])',
          'filter' => StoreImportAssignCategory::model()->getIsConfirmed()
        ),
      ),
    ));

    function getCellName($data){
      $text = "<span class='alert-danger'>Новая категория - пропустить </span>";
      if (isset($data["solution"])){
        $titles = StoreImportAssignCategory::model()->getSolutions();
        $text = $titles[$data["solution"]];
        switch($data["solution"]){
          case StoreImportAssignCategory::SOLUTION_CREATE:
            $spid = $data['spid'];
            $model = StoreCategory::model()->findByPk($spid);
            $text .= " категорию '{$data['elaboration']}' в категории ";
            if ($model) {
              $text .= "'".$model->name."'";
            } else {
              $spid = -$spid;
              $model_correct = StoreImportAssignCategory::model()->findByAttributes(array(
                'sid' => $data['sid'],
                'cid' => $spid,
                'solution' => StoreImportAssignCategory::SOLUTION_CREATE,
              ));
              if ($model_correct) {
                $text .= "'".$model_correct->elaboration."'";
              } else {
                $model_correct = StoreImportAssignCategory::model()->findByAttributes(array(
                  'sid' => $data['sid'],
                  'cid' => $data['cid']
                ));
                $model_correct->solution = StoreImportAssignCategory::SOLUTION_NONE;
                $model_correct->save();
                $text = '<span class="alert-danger">Удалена базовая категория</span> - '.$titles[StoreImportAssignCategory::SOLUTION_NONE];
              }
            }
            break;
          case StoreImportAssignCategory::SOLUTION_ASSIGN:
            $spid = $data['spid'];
            $model = StoreCategory::model()->findByPk($spid);
            if ($model) {
              $text .= " категории ".$model->name;
            } else {
              $spid = -$spid;
              $model_correct = StoreImportAssignCategory::model()->findByAttributes(array(
                'sid' => $data['sid'],
                'cid' => $spid,
                'solution' => StoreImportAssignCategory::SOLUTION_CREATE,
              ));
              if ($model_correct) {
                $text .= "'".$model_correct->elaboration."'";
              } else {
                $model_correct = StoreImportAssignCategory::model()->findByAttributes(array(
                  'sid' => $data['sid'],
                  'cid' => $data['cid']
                ));
                $model_correct->solution = StoreImportAssignCategory::SOLUTION_NONE;
                $model_correct->save();
                $text = '<span class="alert-danger">Удалена базовая категория</span> - '.$titles[StoreImportAssignCategory::SOLUTION_NONE];
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
        <p>Все решения относительно ассоциации категорий приняты, теперь вы можете перейти к ассоциации производителей.</p>
        <br>
        <a href="/admin/store/import/assignVendors/id/<?=$sid?>" class="btn btn-primary">Далее</a>
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
        case 'create':
          $('.treeWrapper').show();
          break;
        default:
          $('.treeWrapper').hide();
      }
    });
    $(document).on('click', '#StoreImportCategoryTree .show_solutions', function(e){
      e.preventDefault();

      var data=$(this).data();
      var solution = data.solution?data.solution:'<?=StoreImportAssignCategory::SOLUTION_SKIP?>';
      $('.category-solution').val(solution).change();
      $('.solution-comment').val(data.comment);
      $('.solution-cid').val(data.id);

      $('#StoreCategoryTree').jstree('close_all');
      $('#StoreCategoryTree').jstree('deselect_all');
      expandNode('StoreCategoryTreeNode_1');
      if (data.spid) {
        expandNode('StoreCategoryTreeNode_'+data.spid);
        $('#StoreCategoryTree').jstree('select_node', 'StoreCategoryTreeNode_'+data.spid);
      }

      var name = data.elaboration?data.elaboration:data.real_name;
      $('#input-create').val(name);
      $('.category_name').html(data.real_name);
      $('#solution_modal').modal();
    });

    $("#StoreCategoryTree").bind("select_node.jstree", function(evt, data){
      if ($('#'+data.selected[0])) {
        $('.selected-category').val($('#'+data.selected[0]).data('id'));
      }
    });

    $(document).on('click', '.btn-save', function(e){
      e.preventDefault();
      var data = $('.popup-form-solution').serializeArray();
      $.post('',data, function(){
        $.fn.yiiGridView.update("StoreImportCategoryTree");
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
      $("#StoreCategoryTree").jstree("open_node", nodeID)
      // Get the jstree object for this node
      var thisNode = $("#StoreCategoryTree").jstree("get_node", nodeID);
      // Get the id of the parent of this node
      nodeID = $("#StoreCategoryTree").jstree("get_parent", thisNode);
    }
  }
</script>