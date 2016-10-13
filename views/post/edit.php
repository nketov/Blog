<?php
use app\models\Lookup;
use yii\grid\GridView;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = 'Управление постами';

?>
<h3>Управление своими постами</h3>

<?php

echo GridView::widget([

    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => "{items}\n{pager}",
    'columns' => [

        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function ($data) {
                return Html::a(Html::encode($data->title), $data->url);
            }
        ],

        [
            'filter' => Lookup::items('PostStatus'),
            'attribute' => 'status',
            'value' => function ($data) {
                return Lookup::item("PostStatus", $data->status);
            }
        ],
        [
            'attribute' => 'create_time',
            'format' => ['datetime', 'short'],
            'filter' => false,
        ],

        [
            'class' => yii\grid\ActionColumn::className(),
        ]
    ]
]);
?>
