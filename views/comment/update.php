<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Comment */

$this->title = 'Update Comment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];

?>

<div class="container">
    <div class="row">
        <div class="post-index col-lg-10">

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
            ]); ?>
            <br>
        </div>
