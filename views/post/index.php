<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

if (!empty($_GET['tag'])): ?>
    <h1>Посты с тагом <i><?php echo Html::encode($_GET['tag']); ?></i></h1>
<?php endif; ?>


<div class="container">
    <div class="row">
        <div class="post-index col-lg-10">

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
            <br>
        </div>

        <div class="panel-success col-lg-2">
              
            <a type="button" class="btn btn-primary btn-lg btn-block" href="<?= Url::toRoute("edit")     ?>">Мои посты</a>
            <a type="button" class="btn btn-default btn-lg btn-block" href="<?= Url::toRoute("create") ?>">Создать пост</a>

        </div>
    </div>
</div>
