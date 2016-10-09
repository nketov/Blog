<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';

?>

<h3>Комментарии</h3>
<div class="comment-index">

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
        'layout' => "{items}\n{pager}",
        'summary' => 'Показано {count} из {totalCount}',
        'summaryOptions' => [
            'tag' => 'span',
            'class' => 'my-summary'
        ],

        'itemOptions' => [
            'tag' => 'div',
            'class' => 'post-item',
        ],

        'emptyText' => '<p>Список пуст</p>',
        'emptyTextOptions' => [
            'tag' => 'p'
        ],

//        'pager' => [
//            'firstPageLabel' => 'Первая',
//            'lastPageLabel' => 'Последняя',
//            'nextPageLabel' => 'Следующая',
//            'prevPageLabel' => 'Предыдущая',
//            'maxButtonCount' => 5,
//        ]
    ]); ?>
</div>
