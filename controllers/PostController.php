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


    /**
     * Lists all Post models.
     */


    public function actionIndex()
    {



        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

//        if(isset($_GET['tag'])){
//            $dataProvider->query->andFilterWhere(['like','tags','погода']);
//        }

        $dataProvider->query->where(['status' => Post::STATUS_PUBLISHED]);

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


    /**
     * Displays a single Post model.
     * @param integer $id
     */


    

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

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

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
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

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        if (Yii::$app->request->post()) {
            $this->findModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        } else
            throw new HttpException(400, 'Ошибочный запрос. Пожайлуйста, не повторяйте его снова.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
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




    public function actionValidation(){
        $model=new Comment();

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