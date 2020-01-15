<?php

class DefaultController extends SAdminController {

    public function actionIndex() {
        $params = array(
            'module' => $this->getModule(),
        );
        $this->render('index', $params);
    }

    public function actionEdit() {
        if (isset($_POST['LanguagesNames'])) {
            $this->redirect(array(
                'edit',
                'languageid' => $_POST['LanguagesNames'],
            ));
        } elseif (isset($_GET['languageid'])) {
            $params = array(
                'module' => $this->getModule(),
                'languageid' => $_GET['languageid'],
            );
            $this->render('edit', $params);
        } else {
            $params = array(
                'module' => $this->getModule(),
                'languageid' => $_GET['languageid'],
            );
            $this->render('index', $params);
        }
    }

    public function actionSearch() {
      $search = $_POST['search'];
      $languageid = $_GET['languageid'];
      $params = array(
        'module' => $this->getModule(),
        'languageid' => $languageid,
        'search' => $search
      );
      $this->render('search', $params);
    }

    public function actionInitFile() {

        if (!isset($_POST))
            Yii::app()->end();
        else
            $fileid = $this->getModule()->fileslist[$_POST['fileid']];
        $languageid = $_POST['languageid'];
        $params = array(
            'module' => $this->getModule(),
            'languageid' => $languageid,
        );

        $data = $this->getModule()->composeMessageFile(array());
        $this->getModule()->saveMessageFile($languageid, $fileid, $data);
        echo $this->renderPartial('_translationfiles', $params);
        Yii::app()->end();
    }

    public function actionSaveFile() {

        if (!isset($_POST))
            $this->render('edit');
        else
            $filename = $_POST['file']['filename'];
        $languageid = $_POST['file']['languageid'];

        $data = $this->getModule()->composeMessageFile($_POST['translate']);
        $this->getModule()->saveMessageFile($languageid, $filename, $data);

        $this->redirect(array(
            'translate',
            'filename' => $filename,
            'languageid' => $languageid,
        ));
    }

    public function actionTranslate() {
        $params = array(
            'module' => $this->getModule(),
        );

        $this->render('translate', $params);
    }

    public function actionInsertLine() {

        if (!isset($_POST))
            Yii::app()->end();
        else
            $filename = $_POST['newline']['filename'];
        $newkey = $_POST['newline']['key'];
        $newvalue = $_POST['newline']['value'];
        $languageid = $_POST['newline']['languageid'];
        $language = $this->getModule()->language;

        $sourcelanguage = include($this->getModule()->messagepath . '/' . $language . '/' . $filename);
        if ((!array_key_exists($newkey, $sourcelanguage)) AND (!empty($newkey))) {
            $sourcelanguage[$newkey] = $newvalue;
        } else if (empty($newkey)) {
            Yii::app()->user->setFlash('error', "Index Key was blank  - Line not inserted");
            $this->redirect(array(
                'translate',
                'filename' => $filename,
                'languageid' => $languageid,
            ));
        } else {
            Yii::app()->user->setFlash('error', "Index Key '" . $newkey . "' already exists - Line not inserted");
            $this->redirect(array(
                'translate',
                'filename' => $filename,
                'languageid' => $languageid,
            ));
        }


        $data = $this->getModule()->composeMessageFile($sourcelanguage);
        $this->getModule()->saveMessageFile($language, $filename, $data);

        $this->redirect(array(
            'translate',
            'filename' => $filename,
            'languageid' => $languageid,
        ));
    }

}

