<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Коментарии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h4><?= Html::encode($this->title) ?></h4>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Comment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'content:text',
            'status',
            'create_time:datetime',
            'author',
            // 'email:email',
            // 'url:url',
            // 'post_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
