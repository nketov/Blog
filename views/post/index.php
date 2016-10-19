<?php

use app\components\ControlPanelWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

if (!empty($_GET['tag'])): ?>
    <h1>Посты с тагом <i><?php echo Html::encode($_GET['tag']); ?></i></h1>
<?php endif; ?>

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
           
