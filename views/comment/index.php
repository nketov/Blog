<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

$this->title = 'Коментарии к моим постам:';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comment-index">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
        'layout' => "{items}\n{pager}",

        'pager' => [
            'nextPageLabel' => 'Раньше',
            'prevPageLabel' => 'Позже',
            'maxButtonCount' => 10,
        ]
    ]); ?>

</div>

