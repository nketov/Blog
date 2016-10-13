<?php
use yii\bootstrap\Alert;

$this->title = 'Главная';
?>

<div class="post-view">
    <?php
    echo $this->render('_view', [
            'model' => $model]
    ); ?>

</div>

<div id="comments row">

    <div class="col-lg-4">

        <h4>Оставьте комментарий к посту:</h4>

        <?php
        if (Yii::$app->session->hasFlash('commentSubmitted')) {
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-success'
                ],
                'body' => Yii::$app->session->getFlash('commentSubmitted')
            ]);
        } else {
            echo $this->render('/comment/_form', array(
                'model' => $comment,
            ));
        } ?>
    </div>

    <div class="col-lg-1">
    </div>

    <div class="col-lg-7">

        <?php if ($model->commentCount >= 1): ?>
            <h4>
                <?php echo $model->commentCount > 1 ? $model->commentCount . ' комментария:' : 'Один комментарий:'; ?>
            </h4>

            <?= $this->render('_comments', array(
                'post' => $model,
                'comments' => $model->comments,
            )); ?>
        <?php endif; ?>
    </div>


</div>