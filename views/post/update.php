<?php

use yii\helpers\Html;

$this->title = 'Изменение поста: ' . $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
