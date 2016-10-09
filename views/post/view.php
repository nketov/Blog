<?php
$this->title = 'Главная';
?>


<div class="post-view">
    <?php
    echo $this->render('_view', [
            'model' => $model]
    ); ?>

</div>

<div id="comments">
    <?php if ($model->commentCount >= 1): ?>
        <h3>
            <?php echo $model->commentCount > 1 ? $model->commentCount . ' комментария:' : 'Один комментарий:'; ?>
        </h3>

        <?= $this->render('_comments', array(
            'post' => $model,
            'comments' => $model->comments,
        )); ?>
    <?php endif; ?>

    <h3>Оставьте сообщение</h3>



    <?php
    if (Yii::$app->session->hasFlash('commentSubmitted')): ?>
        <div class="flash-success">
            <?php echo Yii::$app->session->getFlash('commentSubmitted'); ?>
        </div>
    <?php else: ?>
        <?php
        echo $this->render('/comment/_form', array(
            'model' => $comment,
        ));
        ?>
    <?php endif; ?>

</div>
