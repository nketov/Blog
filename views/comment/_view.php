<?php
use app\models\Comment;
use yii\helpers\Html;
use yii\widgets\DetailView;

$JS = <<< JS

$('.container').on('click','.time a.delete',function() {
	var th=$(this),
		container=th.closest('div.comment'),
		id=container.attr('id').slice(1);
		
	if (confirm('Вы действительно хотите удалить комментарий  #'+id+'?')) {	
		$.post(th.attr('href'),function(){container.slideUp()})		
		}				
	return false;
});

$('.container').on('click','.time a.approve',function() {
	var th=$(this);				
	$.post(th.attr('href'));		
	return false;	
});
JS;

$this->registerJs($JS, yii\web\View::POS_READY);

?>

<div class="comment" id="c<?php echo $model->id; ?>">

    <?php
    echo Html::a("#{$model->id}", $model->commentUrl, array(
        'class' => 'cid',
        'title' => 'Постоянная ссылка на комментарий',
    )); ?>

    <div class="author">
        <?php echo $model->authorLink; ?> прокомментировал
        <?php echo Html::a(Html::encode($model->post->title), $model->post->url); ?>
    </div>

    <div class="time">
        <?php if ($model->status == Comment::STATUS_PENDING): ?>
            <span class="pending">Ожидает одобрения</span> |

            <?php
            echo Html::a('Одобрить', array('comment/approve', 'id' => $model->id), array('class' => 'approve'));
            ?> |
        <?php endif; ?>
        <?php echo Html::a('Изменить', array('comment/update', 'id' => $model->id)); ?> |
        <?php echo Html::a('Удалить', array('/comment/delete', 'id' => $model->id), array('class' => 'delete')); ?> |
        <?php echo date('F j, Y \a\t h:i a', $model->create_time); ?>
    </div>

    <div class="content">
        <?php echo nl2br(Html::encode($model->content)); ?>
    </div>

</div>
