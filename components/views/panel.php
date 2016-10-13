<?php
use app\models\Comment;
use app\models\Tag;
use yii\helpers\Html;
use yii\helpers\Url;

$JS = <<< JS
$('.panel-body').on('click','a.logout',function() {
	var th=$(this);				
	$.post(th.attr('href'));		
	return false;
	});
JS;

$this->registerJs($JS, yii\web\View::POS_READY);

if (!Yii::$app->user->isGuest): ?>
    <div class="panel panel-primary">
        <div class="panel-heading"><?= Html::encode(Yii::$app->user->getIdentity()->username) ?></div>
        <div class="panel-body">
            <a type="button" class="btn  btn-lg" href="<?= Url::toRoute("post/edit") ?>">Мои посты</a>
            <a type="button" class="btn  btn-lg" href="<?= Url::toRoute("post/create") ?>">Создать пост</a>
            <a type="button" class="btn  btn-lg  approve" href="<?= Url::toRoute("comment/index") ?>">Одобрить
                комментарии
                <?= ' (' . Comment::getPendingCommentCount() . ')' ?>
            </a>
            <a type="button" class="btn  btn-lg  logout" href="<?= Url::toRoute("site/logout") ?>">Выход</a>
        </div>
    </div>
<?php endif; ?>

<div class="panel panel-primary">
    <div class="panel-heading"> Теги</div>
    <div class="panel-body">
        <?php
        $tags = Tag::findTagWeights(10);

        foreach ($tags as $tag => $weight) {

            $link = Html::a(Html::encode($tag), array('post/index', 'tag' => $tag));

            echo Html::tag('span', $link, array(
                    'class' => 'tag',
                    'style' => "font-size:{$weight}pt !important",
                )) . "\n";
        }
        ?>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Недавние комментарии</div>
    <div class="panel-body">
        <ul><?

            $recent = Comment::findRecentComments(3);

            foreach ($recent as $comment):?>
                <li><?php echo $comment->authorLink; ?> на
                    <?php echo Html::a(Html::encode($comment->post->title), $comment->getCommentUrl()); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
