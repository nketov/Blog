<?php
use app\models\Comment;
use yii\helpers\Html;
use yii\widgets\DetailView;

$deleteJS = <<<DEL
$('.container').on('click','.time a.delete',function() {
	var th=$(this),
		container=th.closest('div.comment'),
		id=container.attr('id').slice(1);
	if(confirm('Are you sure you want to delete comment #'+id+'?')) {
		$.ajax({
			url:th.attr('href'),
			type:'POST'
		}).done(function(){container.slideUp()});
	}
	return false;
});
DEL;

$this->registerJs('delete', $deleteJS);
?>

<div class="comment" id="c<?php echo $data->id; ?>">

	<?php echo Html::a("#{$data->id}", $data->url, array(
    'class'=>'cid',
    'title'=>'Permalink to this comment',
)); ?>

<div class="author">
    <?php echo $data->authorLink; ?> says on
    <?php echo Html::a(Html::encode($data->post->title), $data->post->url); ?>
</div>

<div class="time">
    <?php if($data->status==Comment::STATUS_PENDING): ?>
        <span class="pending">Pending approval</span> |
        <?php echo Html::a('Approve', array(
            'submit'=>array('comment/approve','id'=>$data->id),
        )); ?> |
    <?php endif; ?>
    <?php echo Html::a('Update',array('comment/update','id'=>$data->id)); ?> |
    <?php echo Html::a('Delete',array('comment/delete','id'=>$data->id),array('class'=>'delete')); ?> |
    <?php echo date('F j, Y \a\t h:i a',$data->create_time); ?>
</div>

<div class="content">
    <?php echo nl2br(Html::encode($data->content)); ?>
</div>

</div><!-- comment -->
