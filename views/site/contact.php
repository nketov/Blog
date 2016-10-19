<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Спасибо за сообщение! Мы ответим вам как только появится возможность.
        </div>

        <p>
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                Because the application is in development mode, the email is not sent but saved as
                a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                                                                                                    Please configure the
                <code>useFileTransport</code> property of the <code>mail</code>
                application component to be false to enable email sending.
            <?php endif; ?>
        </p>

    <?php else: ?>


        <div class="row">
            <div class="col-lg-9">

                <h5>
                    Если вы имеете предложения или вопросы, пожалуйста заполните форму для связи с администрацией.
                    Спасибо!
                </h5>

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->label('Имя')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email')->label('Электронная почта') ?>

                <?= $form->field($model, 'subject')->label('Тема') ?>

                <?= $form->field($model, 'body')->label('Содержание')->textArea(['rows' => 6]) ?>

                <?= $form->field($model, 'verifyCode')->label('Проверочный код')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
