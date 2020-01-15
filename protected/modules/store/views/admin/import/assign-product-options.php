<?php
  /* @var Controller $this */

  if ($sid) {
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
  } else {
    $this->pageHeader = Yii::t('StoreModule', 'Подстановка значений атрибутов при импорте:');
    $this->breadcrumbs = array(
      'Home'=>$this->createUrl('/admin'),
      Yii::t('StoreModule', 'Атрибуты') => $this->createUrl('/admin/store/attribute/index'),
      Yii::t('StoreModule', 'Подстановка значений атрибутов при импорте'),
    );
  }
?>

<div class="modal fade" id="solution_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Выберите решение для значения атрибута:</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary btn-save">Сохранить</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="form wide padding-all">
  <?php
    $solutions = StoreImportAssignProductOptions::model()->getSolutions();
    $this->widget('zii.widgets.grid.CGridView', array(
      'id'=>'StoreImportOptionsProductTree',
      'dataProvider'=> $dataProvider,
      'filter'  => $filter,
      'ajaxUpdate' => false,
      'ajaxVar' => false,
      'columns' => array(
        "title",
        'value',
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
      $text = "<span class='alert-danger'>Новое значение атрибута - пропустить </span>";
      if (isset($data["solution"])){
        $titles = StoreImportAssignProductOptions::model()->getSolutions();
        $text = $titles[$data["solution"]];
        switch($data["solution"]){
          case StoreImportAssignProductOptions::SOLUTION_CREATE:
            $text .= " значение \"{$data['elaboration']}\" ";
            break;
          case StoreImportAssignProductOptions::SOLUTION_ASSIGN:
            $attr = StoreAttribute::model()->findByPk($data['id']);
            switch($attr->type){
              case StoreAttribute::TYPE_YESNO:
                $text .= ' значению "';
                $text .= ($data['spid'] == 1)?"+":"-";
                $text .= '"';
                break;
              case StoreAttribute::TYPE_DROPDOWN:
                $spid = $data['spid'];
                $model = StoreAttributeOption::model()->findByPk($spid);
                if ($model) {
                  $text .= ' значению "'.$model->value.'"';
                }
                break;
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
    </ul>
    <br>
    <div class="bg-info padding-all">
      <br>
      <p>Все решения относительно ассоциации атрибутов приняты. Помните, что все значения атрибутов будут проверятся администраторами и могут появится на сайте позже, чем будет проверен и утвержден источник импорта.</p>
      <br>
      <a href="/admin/store/import/assignComplete/id/<?=$sid?>" class="btn btn-primary">Далее</a>
      <br>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('#solution_modal').on('change','.category-solution' ,function(){
      $('.solution-hint').hide();
      $('.solution-hint-'+$(this).val()).show();
      switch($(this).val()){
        case '<?=StoreImportAssignProductOptions::SOLUTION_ASSIGN?>':
          $('.treeWrapper').show();
          break;
        default:
          $('.treeWrapper').hide();
      }
    });
    $(document).on('click', '#StoreImportOptionsProductTree .show_solutions', function(e){
      e.preventDefault();

      var el = this;
      var data=$(el).data();

      $('#solution_modal').modal();
      $('#solution_modal .modal-body').html('Loading...').load('/admin/store/import/assignProductOptionsVariants/id/'+data.id, function(){
        var data=$(el).data();
        var solution = data.solution?data.solution:'<?=StoreImportAssignProductOptions::SOLUTION_SKIP?>';
        $('.category-solution').val(solution).change();
        $('.solution-comment').val(data.comment);
        $('.option-assign-value').val(data.value);
        $('.selected-category').val(data.spid);


        $('#StoreOptionsTree').jstree({
          core: {
            initially_open: 'StoreOptionsTreeNode_1',
            multiple: false
          },
          plugins: ['themes','html_data','checkbox'],
          checkbox: {
            two_state: true,
            three_state: false
          }
        });
        expandNode('StoreOptionsTreeNode_1');
        if (data.spid) {
          expandNode('StoreOptionsTreeNode_'+data.spid);
          $('#StoreOptionsTree').jstree('select_node', 'StoreOptionsTreeNode_'+data.spid);
          $('#select-spid-create option').removeAttr('selected');
          $('#select-spid-create option[value='+data.spid+']').attr('selected','selected');
        }

        var name = data.elaboration?data.elaboration:data.real_name;
        $('#input-create').val(name);

        $("#StoreOptionsTree").unbind("select_node.jstree").bind("select_node.jstree", function(evt, data){
          if ($('#'+data.selected[0])) {
            $('.selected-category').val($('#'+data.selected[0]).data('id'));
          }
        });
      });
    });

    $(document).on('click', '.btn-save', function(e){
      e.preventDefault();
      var data = $('.popup-form-solution').serializeArray();
      $.post('',data, function(){
        $.fn.yiiGridView.update("StoreImportOptionsProductTree");
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

<?php

  $assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.jstree3.assets'), false, -1, YII_DEBUG);
  Yii::app()->getClientScript()->registerPackage('cookie');
  Yii::app()->getClientScript()->registerCssFile($assetsUrl.'/themes/default/style.css');
  Yii::app()->getClientScript()->registerScriptFile($assetsUrl.'/jquery.jstree.js');
