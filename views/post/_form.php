<?php

use app\models\Lookup;
use app\models\Tag;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;

?>

<div class="form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-input-example',
        'options' => [
            'class' => 'form-horizontal col-lg-8',
            'enctype' => 'multipart/form-data'
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 5]) ?>

    <?= $form->field($model, 'tags')->textarea(['rows' => 1])->hint('Пожалуйста разделяйти теги запятыми')->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source' => Tag::find()
                ->select(['name as label'])
                ->asArray()
                ->all(),
        ],
        'options' => [
            'class' => 'form-control'
        ]
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList(Lookup::items('PostStatus')); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить изменения', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
