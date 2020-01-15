<?php

/**
 * Admin product category controller
 */
class ImportController extends SAdminController {
  public function init(){
    parent::init();
    Helper::registerJS('bootstrap.min.js', CClientScript::POS_HEAD);
    Helper::registerCSS('bootstrap.css');
    Helper::registerCSS('bootstrap-theme.min.css');
    return true;
  }

  public function beforeAction($action)
  {
    foreach (Yii::app()->log->routes as $route)
    {
      if ($route instanceof CWebLogRoute)
      {
        $route->enabled = false;
      }
    }
    return true;
  }

	public function actionIndex(){
    $model = new StoreImportSources('search');
    if (!empty($_GET['StoreImportSources'])) {
      $model->attributes = $_GET['StoreImportSources'];
    } else {
      /*
      if (Yii::app()->user->getIsSuperuser()) {
        $model->status = StoreImportSources::STATUS_CONFIRMATION;
      }
      */
    }

    // Pass additional params to search method.
    $params = array(
      'user_id' => Yii::app()->getUser()->getModel()->id
    );

    $dataProvider = $model->search($params);
    $dataProvider->pagination->pageSize = 10;


//    $sources = Yii::app()->getUser()->getModel()->sources();
//    print_r($sources);
    $this->render('index',array(
      'dataProvider' => $dataProvider,
      'model'        => $model,
    ));
	}
  public function actionUpdate($new = false){
    if (!$id = Helper::getGet('id', false, type_int))
      $model = new StoreImportSources();
    else
      $model = StoreImportSources::model()->findByPk($id);

    if (!$model)
      throw new CHttpException(404, Yii::t('StoreModule', 'Источник импорта не найден.'));

    if (Yii::app()->request->isPostRequest)
    {
      $model->attributes = $_POST['StoreImportSources'];
      if ($model->validate())
      {
        if ($model->save()) {
          $this->redirect(array('index'));
        }
      }
    }

    $this->render('update', array(
      'model'=>$model
    ));
  }
  public function actionTest(){
    if (!$id = Helper::getGet('id', false, type_int)) {
      $this->redirect(array('index'));
    }
    $model = StoreImportSources::model()->findByPk($id);
    if (!$model)
      throw new CHttpException(404, Yii::t('StoreModule', 'Источник импорта не найден.'));

    $action = Helper::getGet('action', 'index');

    $response = '';

    switch($action) {
      case 'load':
        $tid = Yii::app()->gearman->doBackground("ImportLoad", $id, 'ImportLoad-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'check':
        $tid = Yii::app()->gearman->doBackground("ImportCheck", $id, 'ImportCheck-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'wait':
        $tid = Helper::getGet('for', 0, type_string);
        $client = Yii::app()->gearman;
        $response = $client->getTaskStatus($tid);
        break;
      default:
        Helper::registerJS('import.js');
        $this->render('test', array(
          'model' => $model,
          'actions' => json_encode(array(
            array('action'=>'load', 'title'=>Yii::t('StoreModule', 'Загрузка документа')),
            array('action'=>'check', 'title'=>Yii::t('StoreModule', 'Проверка структуры документа')),
          ))
        ));
    }
    if($response) {
      echo(json_encode($response));
    }
  }
  public function actionAssign(){
    if (!$id = Helper::getGet('id', false, type_int)) {
      $this->redirect(array('index'));
    }
    $model = StoreImportSources::model()->findByPk($id);
    if (!$model)
      throw new CHttpException(404, Yii::t('StoreModule', 'Источник импорта не найден.'));

    $action = Helper::getGet('action', 'index');
    // $import = new Importer($model);

    $response = '';
    switch($action) {
      case 'wait':
        $tid = Helper::getGet('for', 0, type_string);
        $client = Yii::app()->gearman;
        $response = $client->getTaskStatus($tid);
        break;
      case 'load':
        $tid = Yii::app()->gearman->doBackground("ImportLoad", $id, 'ImportLoad-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-init':
        $tid = Yii::app()->gearman->doBackground("preProcessInit", $id, 'preProcessInit-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-category':
        $tid = Yii::app()->gearman->doBackground("preProcessCategory", $id, 'preProcessCategory-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-params':
        $tid = Yii::app()->gearman->doBackground("preProcessParams", $id, 'preProcessParams-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-vendors':
        $tid = Yii::app()->gearman->doBackground("preProcessVendors", $id, 'preProcessVendors-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-products':
        $tid = Yii::app()->gearman->doBackground("preProcessProducts", $id, 'preProcessProducts-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      default:
        //        Helper::registerJS('import-assign.js');
        Helper::registerJS('import.js');
        $this->render('assign', array(
          'model' => $model,
          'actions' => json_encode(array(
            array('action'=>'preprocess-init', 'title'=>Yii::t('StoreModule', 'Иницализация')),
            array('action'=>'preprocess-category', 'title'=>Yii::t('StoreModule', 'Предварительная обработка категорий')),
            array('action'=>'preprocess-params', 'title'=>Yii::t('StoreModule', 'Предварительная обработка дополнительных характеристик')),
            array('action'=>'preprocess-vendors', 'title'=>Yii::t('StoreModule', 'Предварительная обработка производителей / брендов')),
            array('action'=>'preprocess-products', 'title'=>Yii::t('StoreModule', 'Предварительная обработка продуктов'))
          ))
        ));
    }
    if($response) {
      echo(json_encode($response));
    }
  }
  public function actionAssignCategories(){
    if (!$id = Helper::getGet('id', false, type_int)) {
      $this->redirect(array('index'));
    }
    $model = StoreImportSources::model()->findByPk($id);
    if (!$model)
      throw new CHttpException(404, Yii::t('StoreModule', 'Источник импорта не найден.'));

    if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
      $cid = Helper::getPost('category-assign-cid', 0);
//      echo($id.'-'.$cid); die();
      $model_assign = StoreImportAssignCategory::model()->findByAttributes(array(
        'sid' => $id,
        'cid' => $cid
      ));
      if (!$model_assign) {
        $model_assign = new StoreImportAssignCategory();
        $model_assign->sid = $id;
        $model_assign->cid = $cid;
      }
      $model_assign->attributes = $_POST['category-assign'];
      $model_assign->isNew = 1;
      if (!$model_assign->save()) {
        print_r($model_assign->getErrors());
      }

      Helper::updateTree($id, $model_assign);
      Yii::app()->cache->delete(StoreImportDataCategories::model()->tableName().'_plainTree_'.$id);
    } else {
      $StoreImportDataCategoriesArray=new StoreImportDataCategoriesArray();
      if (isset($_GET['StoreImportDataCategoriesArray'])) {
        $StoreImportDataCategoriesArray->filters=$_GET['StoreImportDataCategoriesArray'];
      } else {
        if (Yii::app()->user->getIsSuperuser()) {
          $StoreImportDataCategoriesArray->filters['isNew'] = '1';
        }
      }

      /* @var $model StoreImportSources */
      $categories = StoreImportDataCategories::getPlainTree($id);
      $filteredData=$StoreImportDataCategoriesArray->filter($categories);
      $dataProvider=new CArrayDataProvider($filteredData, array(
        'keyField' => 'id',
        'pagination'=>array(
          'pageSize'=>35,
        ),
      ));

      $base_categories = Helper::getTree($id);

      $stat = array();
      $stat['overall'] = array(
        'name'   => Yii::t('StoreModule', 'Всего категорий'),
        'count'  => StoreImportDataCategories::model()->countByAttributes(array('sid' => $id))
      );

      foreach(StoreImportAssignCategory::model()->getSolutions() as $key => $solution) {
        $stat[$key] = array(
          'name'   => $solution,
          'count'  => StoreImportAssignCategory::model()->countByAttributes(array(
              'sid' => $id,
              'solution' => $key
            ))
        );
      }

      $stat['total'] = array(
        'name'   => Yii::t('StoreModule', 'Всего принято решений'),
        'count'  => StoreImportAssignCategory::model()->countByAttributes(array('sid' => $id))
      );

      $this->render('assign-categories', array(
        'dataProvider' => $dataProvider,
        'filter'       => $StoreImportDataCategoriesArray,
        'categories'   => $base_categories,
        'stat'         => $stat,
        'sid'          => $id
      ));
    }
  }
  public function actionAssignVendors(){
    if (!$id = Helper::getGet('id', false, type_int)) {
      $this->redirect(array('index'));
    }
    $model = StoreImportSources::model()->findByPk($id);
    if (!$model)
      throw new CHttpException(404, Yii::t('StoreModule', 'Источник импорта не найден.'));

    if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
      $cid = Helper::getPost('vendor-assign-cid', 0);
      //      echo($id.'-'.$cid); die();
      $model_assign = StoreImportAssignVendors::model()->findByAttributes(array(
        'sid' => $id,
        'cid' => $cid
      ));
      if (!$model_assign) {
        $model_assign = new StoreImportAssignVendors();
        $model_assign->sid = $id;
        $model_assign->cid = $cid;
      }
      $model_assign->attributes = $_POST['vendor-assign'];
      $model_assign->isNew = 1;
      if (!$model_assign->save()) {
        print_r($model_assign->getErrors());
      }
    } else {
      $StoreImportDataVendorsArray=new StoreImportDataVendorsArray();
      if (isset($_GET['StoreImportDataVendorsArray'])) {
        $StoreImportDataVendorsArray->filters=$_GET['StoreImportDataVendorsArray'];
      } else {
        if (Yii::app()->user->getIsSuperuser()) {
          $StoreImportDataVendorsArray->filters['isNew'] = '1';
        }
      }

      /* @var $model StoreImportSources */
      $vendors = StoreImportDataVendors::getPlainTree($id);
      $filteredData=$StoreImportDataVendorsArray->filter($vendors);
      $dataProvider=new CArrayDataProvider($filteredData, array(
        'keyField' => 'id',
        'pagination'=>array(
          'pageSize'=>35,
        ),
      ));

      $base_vendors = array();
      foreach(StoreManufacturer::model()->orderByName()->findAll() as $one) {
        $base_vendors[] = array(
          'id'   => $one->id,
          'name' => $one->name,
          'hasChildren' => false,
        );
      }

      $stat = array();
      $stat['overall'] = array(
        'name'   => Yii::t('StoreModule', 'Всего производителей'),
        'count'  => StoreImportDataVendors::model()->countByAttributes(array('sid' => $id))
      );

      foreach(StoreImportAssignVendors::model()->getSolutions() as $key => $solution) {
        $stat[$key] = array(
          'name'   => $solution,
          'count'  => StoreImportAssignVendors::model()->countByAttributes(array(
              'sid' => $id,
              'solution' => $key
            ))
        );
      }

      $stat['total'] = array(
        'name'   => Yii::t('StoreModule', 'Всего принято решений'),
        'count'  => StoreImportAssignVendors::model()->countByAttributes(array('sid' => $id))
      );

      $this->render('assign-vendors', array(
        'dataProvider' => $dataProvider,
        'filter'       => $StoreImportDataVendorsArray,
        'vendors'   => $base_vendors,
        'stat'         => $stat,
        'sid'          => $id
      ));
    }
  }
  public function actionAssignOptions(){
    if (!$id = Helper::getGet('id', false, type_int)) {
      $this->redirect(array('index'));
    }
    $model = StoreImportSources::model()->findByPk($id);
    if (!$model)
      throw new CHttpException(404, Yii::t('StoreModule', 'Источник импорта не найден.'));

    if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
      $cid = Helper::getPost('option-assign-cid', 0);
      //      echo($id.'-'.$cid); die();
      $model_assign = StoreImportAssignOptions::model()->findByAttributes(array(
        'sid' => $id,
        'cid' => $cid
      ));
      if (!$model_assign) {
        $model_assign = new StoreImportAssignOptions();
        $model_assign->sid = $id;
        $model_assign->cid = $cid;
      }
      $model_assign->attributes = $_POST['option-assign'];

      if($model_assign->solution == StoreImportAssignOptions::SOLUTION_CREATE) {
        $model_assign->spid = $_POST['option-type'];
      }

      $model_assign->isNew = 1;
      if (!$model_assign->save()) {
        print_r($model_assign->getErrors());
      }
    } else {
      $StoreImportDataOptionsArray=new StoreImportDataOptionsArray();
      if (isset($_GET['StoreImportDataOptionsArray'])) {
        $StoreImportDataOptionsArray->filters=$_GET['StoreImportDataOptionsArray'];
      } else {
        if (Yii::app()->user->getIsSuperuser()) {
          $StoreImportDataOptionsArray->filters['isNew'] = '1';
        }
      }

      /* @var $model StoreImportSources */
      $options = StoreImportDataOptions::getPlainTree($id);
      $filteredData=$StoreImportDataOptionsArray->filter($options);
      $dataProvider=new CArrayDataProvider($filteredData, array(
        'keyField' => 'id',
        'pagination'=>array(
          'pageSize'=>35,
        ),
      ));

      $base_options = array();
      $condition = new CDbCriteria();
      $condition->addInCondition('type', array(1,3,7));
      foreach(StoreAttribute::model()->orderByName()->findAll($condition) as $one) {
        switch ($one->type) {
          case StoreAttribute::TYPE_TEXT:
            $type = ' <span class="text-muted">'.Yii::t('StoreModule', 'типа "произвольное значение"').'</span>';
            break;
          case StoreAttribute::TYPE_DROPDOWN:
            $type = ' <span class="text-muted">'.Yii::t('StoreModule', 'типа "выпадающий список"').'</span>';
            break;
          case StoreAttribute::TYPE_YESNO:
            $type = ' <span class="text-muted">'.Yii::t('StoreModule', 'типа "логическое значение"').'</span>';
            break;
          default:
            $type = '';
        }

        $base_options[] = array(
          'id'   => $one->id,
          'name' => $one->title.$type,
          'hasChildren' => false,
        );
      }

      $stat = array();
      $stat['overall'] = array(
        'name'   => Yii::t('StoreModule', 'Всего характеристик'),
        'count'  => StoreImportDataOptions::model()->countByAttributes(array('sid' => $id))
      );

      foreach(StoreImportAssignOptions::model()->getSolutions() as $key => $solution) {
        $stat[$key] = array(
          'name'   => $solution,
          'count'  => StoreImportAssignOptions::model()->countByAttributes(array(
              'sid' => $id,
              'solution' => $key
            ))
        );
      }

      $stat['total'] = array(
        'name'   => Yii::t('StoreModule', 'Всего принято решений'),
        'count'  => StoreImportAssignOptions::model()->countByAttributes(array('sid' => $id))
      );

      $this->render('assign-options', array(
        'dataProvider' => $dataProvider,
        'filter'       => $StoreImportDataOptionsArray,
        'options'   => $base_options,
        'stat'         => $stat,
        'sid'          => $id
      ));
    }
  }
  public function actionAssignProductOptions(){
    $id = Helper::getGet('id', false, type_int);
    if (Yii::app()->user->getIsSuperuser()) {
      if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
        $oid   = Helper::getPost('option-assign-oid', 0);
        $value = Helper::getPost('option-assign-value', '');
        //      echo($id.'-'.$cid); die();
        $model_assign = StoreImportAssignProductOptions::model()->findByAttributes(array(
          'oid' => $oid,
          'value' => $value
        ));
        if (!$model_assign) {
          $model_assign = new StoreImportAssignProductOptions();
          $model_assign->oid   = $oid;
          $model_assign->value = $value;
        }
        $model_assign->elaboration  = Helper::getPost('option-assign-elaboration');
        $model_assign->solution     = Helper::getPost('option-assign-solution');
        $model_assign->comment      = Helper::getPost('option-assign-comment');
        $model_assign->spid         = Helper::getPost('option-assign-spid');
        $model_assign->isNew        = 0;

        if ($model_assign->solution == StoreImportAssignProductOptions::SOLUTION_CREATE) {
          $option = new StoreAttributeOption;
          $option->attribute_id = $oid;
          $option->value        = $model_assign->elaboration;
          $option->save();

          $model_assign->solution = StoreImportAssignProductOptions::SOLUTION_ASSIGN;
          $model_assign->spid     = $option->id;
        }

        if (!$model_assign->save()) {
          print_r($model_assign->getErrors());
        }
      } else {
        $StoreImportAssignProductOptionsArray=new StoreImportAssignProductOptionsArray();
        if (isset($_GET['StoreImportAssignProductOptionsArray'])) {
          $StoreImportAssignProductOptionsArray->filters=$_GET['StoreImportAssignProductOptionsArray'];
        } else {
          if (Yii::app()->user->getIsSuperuser()) {
            $StoreImportAssignProductOptionsArray->filters['isNew'] = '1';
          }
        }

        /* @var $model StoreImportSources */
        $options = StoreImportAssignProductOptions::getPlainTree($id);
        $filteredData=$StoreImportAssignProductOptionsArray->filter($options);
        $dataProvider=new CArrayDataProvider($filteredData, array(
          'keyField' => 'id',
          'pagination'=>array(
            'pageSize'=>35,
          ),
        ));

        $stat = array();
        $stat['overall'] = array(
          'name'   => Yii::t('StoreModule', 'Всего значений'),
          'count'  => StoreImportAssignProductOptions::model()->cache(60)->count()
        );

        foreach(StoreImportAssignProductOptions::model()->getSolutions() as $key => $solution) {
          $stat[$key] = array(
            'name'   => $solution,
            'count'  => StoreImportAssignProductOptions::model()->countByAttributes(array(
                'solution' => $key
              ))
          );
        }

        /*
        $stat['total'] = array(
          'name'   => Yii::t('StoreModule', 'Всего принято решений'),
          'count'  => StoreImportAssignOptions::model()->countByAttributes(array('sid' => $id))
        );
        */

        $this->render('assign-product-options', array(
          'dataProvider' => $dataProvider,
          'filter'       => $StoreImportAssignProductOptionsArray,
          'stat'         => $stat,
          'sid'          => $id
        ));
      }
    } else {
      $this->redirect($this->createUrl('/admin/store/import/assignComplete/id/'.$id));
    }
  }
  public function actionAssignProductOptionsVariants(){
    if (!$id = Helper::getGet('id', false, type_int)) {
      $this->redirect(array('index'));
    }

    $attr = StoreAttribute::model()->findByPk($id);
    if (!$attr) {
      trigger_error('unknown attr #'.$attr);
    }

    $base_options = array();
    switch ($attr->type) {
      case StoreAttribute::TYPE_YESNO:
        $base_options = array(
          array(
            'id'   => 1,
            'name' => '+',
            'hasChildren' => false,
          ),
          array(
            'id'   => 2,
            'name' => '-',
            'hasChildren' => false,
          ),
        );
        break;
      case StoreAttribute::TYPE_DROPDOWN:
        $criteria = New CDbCriteria();
        $criteria->order = "position ASC, value ASC";
        $criteria->addCondition('attribute_id = '.(int)$id);
        $options = StoreAttributeOption::model()->findAll($criteria);
        foreach($options as $one) {
          $base_options[] = array(
            'id'   => $one->id,
            'name' => $one->value,
            'hasChildren' => false,
          );
        }
        break;
    }


    $this->renderPartial('assign-product-options-popup', array(
      'id' => $id,
      'options' => $base_options,
      'attr' => $attr
    ));
  }

  public function actionAssignComplete(){
    if (!$id = Helper::getGet('id', false, type_int)) {
      $this->redirect(array('index'));
    }
    $model = StoreImportSources::model()->findByPk($id);
    if (!$model)
      throw new CHttpException(404, Yii::t('StoreModule', 'Источник импорта не найден.'));

    if (Helper::getGet('approve', false)) {
      $model->comment = Yii::app()->user->getIsSuperuser()?Helper::getPost('comment')  : $model->comment;
      $model->status  = Yii::app()->user->getIsSuperuser()?Helper::getPost('solution') : StoreImportSources::STATUS_CONFIRMATION;
      $model->save();
      $this->setFlashMessage(
        Yii::app()->user->getIsSuperuser()
          ?Yii::t('StoreModule', 'Решение сохранено')
          :Yii::t('StoreModule', 'Источник импорта отправлен на проверку'));
      $this->redirect($this->createUrl('/admin/store/import'));
    }

    $this->render('assign-complete', array(
      'model' => $model,
      'sid' => $id,
      'totals' => array(
        Yii::t('StoreModule', 'Не утвержденных категорий:') => StoreImportAssignCategory::model()->countByAttributes(array('sid'=>$id, 'isNew'=>1)),
        Yii::t('StoreModule', 'Не утвержденных производителей:') => StoreImportAssignVendors::model()->countByAttributes(array('sid'=>$id, 'isNew'=>1)),
        Yii::t('StoreModule', 'Не утвержденных атрибутов:') => StoreImportAssignOptions::model()->countByAttributes(array('sid'=>$id, 'isNew'=>1)),
      )
    ));
  }
  public function actionRun(){
    if (!$id = Helper::getGet('id', false, type_int)) {
      $this->redirect(array('index'));
    }
    $model = StoreImportSources::model()->findByPk($id);
    if (!$model)
      throw new CHttpException(404, Yii::t('StoreModule', 'Источник импорта не найден.'));

    $action = Helper::getGet('action', 'index');

    $response = '';

    switch($action) {
      case 'load':
        $tid = Yii::app()->gearman->doBackground("ImportLoad", $id, 'ImportLoad-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-init':
        $tid = Yii::app()->gearman->doBackground("preProcessInit", $id, 'preProcessInit-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-category':
        $tid = Yii::app()->gearman->doBackground("preProcessCategory", $id, 'preProcessCategory-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-params':
        $tid = Yii::app()->gearman->doBackground("preProcessParams", $id, 'preProcessParams-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-vendors':
        $tid = Yii::app()->gearman->doBackground("preProcessVendors", $id, 'preProcessVendors-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'preprocess-products':
        $tid = Yii::app()->gearman->doBackground("preProcessProducts2", $id, 'preProcessProducts2-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'run-categories':
        $tid = Yii::app()->gearman->doBackground("ImportRunCategories", $id, 'ImportRunCategories-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'run-vendors':
        $tid = Yii::app()->gearman->doBackground("ImportRunVendors", $id, 'ImportRunVendors-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'run-options':
        $tid = Yii::app()->gearman->doBackground("ImportRunOptions", $id, 'ImportRunOptions-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'run-images':
        $tid = Yii::app()->gearman->doBackground("ImportRunImages", $id, 'ImportRunImages-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'run-products':
        $tid = Yii::app()->gearman->doBackground("ImportRunProducts", $id, 'ImportRunProducts-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'run-products-options':
        $tid = Yii::app()->gearman->doBackground("ImportRunProductsOptions", $id, 'ImportRunProductsOptions-'.$id, true, Gearmand::PRIORITY_HIGH);
        $response = array('status' => 'wait', 'for' => $tid);
        break;
      case 'wait':
        $tid = Helper::getGet('for', 0, type_string);
        $client = Yii::app()->gearman;
        $response = $client->getTaskStatus($tid);
        break;
      default:
        Helper::registerJS('import.js');
        $this->render('run', array(
          'model' => $model,
          'actions' => json_encode(array(
            array('action'=>'load', 'title'=>Yii::t('StoreModule', 'Загрузка документа')),
            array('action'=>'preprocess-init', 'title'=>Yii::t('StoreModule', 'Иницализация')),
            array('action'=>'preprocess-category', 'title'=>Yii::t('StoreModule', 'Предварительная обработка категорий')),
            array('action'=>'preprocess-params', 'title'=>Yii::t('StoreModule', 'Предварительная обработка дополнительных характеристик')),
            array('action'=>'preprocess-vendors', 'title'=>Yii::t('StoreModule', 'Предварительная обработка производителей / брендов')),
            array('action'=>'preprocess-products', 'title'=>Yii::t('StoreModule', 'Предварительная обработка продуктов')),
//            array('action'=>'run-categories', 'title'=>Yii::t('StoreModule', 'Импорт категорий')),
//            array('action'=>'run-vendors', 'title'=>'Yii::t('StoreModule', Импорт производителей')),
//            array('action'=>'run-options', 'title'=>Yii::t('StoreModule', 'Импорт атрибутов')),
//            array('action'=>'run-images', 'title'=>Yii::t('StoreModule', 'Импорт изображений')),
            array('action'=>'run-products', 'title'=>Yii::t('StoreModule', 'Импорт продуктов')),
//            array('action'=>'run-products-options', Yii::t('StoreModule', 'title'=>'Импорт значений атрибутов')),
          ))
        ));
    }
    if($response) {
      echo(json_encode($response));
    }
  }

  public function actionUpload(){
    // HTTP headers for no cache etc
    header('Content-type: text/plain; charset=UTF-8');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Settings
    $targetDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "plupload";
    $cleanupTargetDir = false; // Remove old files
    $maxFileAge = 60 * 60; // Temp file age in seconds

    // 5 minutes execution time
    @set_time_limit(5 * 60);
    // usleep(5000);

    // Get parameters
    $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
    $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
    $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

    // Clean the fileName for security reasons
    $fileName = preg_replace('/[^\w\._\s]+/', '', $fileName);

    // Create target dir
    if (!file_exists($targetDir))
      @mkdir($targetDir);

    // Remove old temp files
    if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
      while (($file = readdir($dir)) !== false) {
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

        // Remove temp files if they are older than the max age
        if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
          @unlink($filePath);
      }

      closedir($dir);
    } else
      throw new CHttpException (500, Yii::t('app', "Can't open temporary directory."));

    // Look for the content type header
    if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
      $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

    if (isset($_SERVER["CONTENT_TYPE"]))
      $contentType = $_SERVER["CONTENT_TYPE"];

    if (strpos($contentType, "multipart") !== false) {
      if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
        // Open temp file
        $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
        if ($out) {
          // Read binary input stream and append it to temp file
          $in = fopen($_FILES['file']['tmp_name'], "rb");

          if ($in) {
            while ($buff = fread($in, 4096))
              fwrite($out, $buff);
          } else
            throw new CHttpException (500, Yii::t('app', "Can't open input stream."));

          fclose($out);
          unlink($_FILES['file']['tmp_name']);
        } else
          throw new CHttpException (500, Yii::t('app', "Can't open output stream."));
      } else
        throw new CHttpException (500, Yii::t('app', "Can't move uploaded file."));
    } else {
      // Open temp file
      $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
      if ($out) {
        // Read binary input stream and append it to temp file
        $in = fopen("php://input", "rb");

        if ($in) {
          while ($buff = fread($in, 4096))
            fwrite($out, $buff);
        } else
          throw new CHttpException (500, Yii::t('app', "Can't open input stream."));

        fclose($out);
      } else
        throw new CHttpException (500, Yii::t('app', "Can't open output stream."));
    }

    // After last chunk is received, process the file
    $ret = array('result' => '1');
    if (intval($chunk) + 1 >= intval($chunks)) {

      $originalname = $fileName;
      if (isset($_SERVER['HTTP_CONTENT_DISPOSITION'])) {
        $arr = array();
        preg_match('@^attachment; filename="([^"]+)"@',$_SERVER['HTTP_CONTENT_DISPOSITION'],$arr);
        if (isset($arr[1]))
          $originalname = $arr[1];
      }

      // **********************************************************************************************
      // Do whatever you need with the uploaded file, which has $originalname as the original file name
      // and is located at $targetDir . DIRECTORY_SEPARATOR . $fileName
      // **********************************************************************************************
      $filename_  = Yii::app()->user->id . '_' .sha1($originalname).'.xml';
      $file_path = Yii::app()->getBasePath().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'import'.DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR.$filename_;
      $file_url  = Yii::app()->getBaseUrl(true).'/uploads/import/temp/'. $filename_;
      $ret['url'] = $file_url;
      copy($targetDir . DIRECTORY_SEPARATOR . $fileName, $file_path);
    }

    // Return response
    die(json_encode($ret));
  }

}
