<?php

namespace app\controllers;

use app\models\Comment;
use app\models\Tag;
use HttpException;
use Yii;
use app\models\Post;
use app\models\PostSearch;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;


class PostController extends Controller
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
                        'actions' => ['index', 'view'],
                        'roles' => ['?'],
                    ],
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->where(['status' => Post::STATUS_PUBLISHED]);

        if (isset($_GET['tag'])) {
            $dataProvider->query->andFilterWhere(['like', 'tags', $_GET]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionEdit()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['author_id' => Yii::$app->user->id]);

        if (isset($_GET['Post']))
            $searchModel->attributes = $_GET['Post'];

        return $this->render('edit', array(
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ));
    }


    public function actionSuggestTags()
    {
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $tags = Tag::suggestTags($keyword);
            if ($tags !== array())
                echo implode("\n", $tags);
        }
    }

    public function actionCreate()
    {
        $model = new Post();
        $model->author_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->post()) {
            $this->findModel($id)->delete();

            if (!isset($_GET['ajax'])) {
                $this->redirect(array('index'));
            }
        } else
            throw new HttpException(400, 'Ошибочный запрос. Пожайлуйста, не повторяйте его снова.');

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрошенная страница не существует.');
        }
    }


    public function loadModel()
    {
        if ($this->_model === null) {

            if (isset($_GET['id'])) {

                $this->_model = Post::findOne($_GET['id']);
                $status = $this->_model->status;

                if ((Yii::$app->user->isGuest) && !(in_array($status, [Post::STATUS_PUBLISHED, Post::STATUS_ARCHIVED]))) {
                    throw new HttpException(404, 'Страница недоступна!!!.');
                }
            }
            if ($this->_model == null)
                throw new HttpException(404, 'Страница недоступна!!!');
        }
        return $this->_model;
    }

    public function actionView($id)
    {
        $post = $this->loadModel();
        $comment = $this->newComment($post);

        return $this->render('view', [
            'model' => $post,
            'comment' => $comment
        ]);

    }


    public function actionValidation()
    {
        $model = new Comment();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }


    protected function newComment($post)
    {

        $comment = new Comment;

        if (isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];

            if ($post->addComment($comment)) {

                if ($comment->status == Comment::STATUS_PENDING)
                    Yii::$app->session->setFlash('commentSubmitted', 'Спасибо за комментарий! Он будет опубликован после одобрения.');

            }
        }
        return $comment;
    }

}