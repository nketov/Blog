<?php

namespace app\controllers;

use HttpException;
use Yii;
use app\models\Comment;
use app\models\CommentSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;


class CommentController extends Controller
{

    private $_model;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

//        if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
//        {
//            echo ActiveForm::validate($model);
//            Yii::$app->end();
//        }

        if (isset($_POST['Comment'])) {
            $model->attributes = $_POST['Comment'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('update', array(
            'model' => $model,
        ));


    }


    public function actionDelete($id)
    {
        if (Yii::$app->request->post()) {
            $this->loadModel()->delete();

            if (!isset($_POST['ajax']))
                $this->redirect(array('index'));
        } else
            throw new HttpException(400, 'Ошибочный запрос. Пожайлуйста, не повторяйте его снова.');
    }


    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Страница недоступна!!!');
        }
    }


    public function loadModel()
    {
        if ($this->_model === null) {
            if (isset($_GET['id']))
                $this->_model = Comment::findOne($_GET['id']);
            if ($this->_model === null)
                throw new HttpException(404, 'Страница недоступна!!!');
        }
        return $this->_model;
    }


    public function actionApprove()
    {
        if (Yii::$app->request->post()) {
            $comment = $this->loadModel();
            $comment->approve();
            $this->redirect(array('index'));
        } else
            throw new HttpException(400, 'Ошибочный запрос. Пожайлуйста, не повторяйте его снова.');
    }


}
