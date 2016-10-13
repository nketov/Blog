<div class="post row">
    <div class="title row">
        <hr>
        <?php
        use yii\helpers\Html;

        echo Html::a($model->title, $model->url); ?>
    </div>

    <div class="author row">
        Опубликовал <span
            class="author-name"><?php echo $model->author->username . '</span>   ' . date('F j, Y', $model->create_time); ?>
    </div>

    <div class="content row">
        <?php

        echo $model->content;
        ?>
    </div>
    <div class="row">
        <div class="nav col-lg-4">

            <b>Тэги:</b>
            <?php echo implode(', ', $model->tagLinks); ?>
            <br/>
            <?php echo Html::a("Комментарии ({$model->commentCount})", $model->url . '#comments'); ?> |
            Последние измененния <?php echo date('F j, Y', $model->update_time); ?>
        </div>
    </div>
    
</div>
