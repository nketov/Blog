<?php

use yii\helpers\Html;


$this->title = 'Создание поста';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">
    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
