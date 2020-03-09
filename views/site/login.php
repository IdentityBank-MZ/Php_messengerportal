<?php

use app\assets\AppAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

AppAsset::register($this);

$this->title = 'Login Page';
$this->context->layout = 'clear';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="padding-top: 40px;padding-bottom: 40px;background-color: #eee;">
<?php $this->beginBody() ?>

<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr class="style" width="70%">
    <p><?= 'Please fill out the following fields to login to the Messenger' ?></p>
    <hr class="style" width="70%">

    <?php $form = ActiveForm::begin(
        [
            'id' => 'login-form',
            'options' => ['class' => 'form-signin'],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'sr-only'],
            ],
        ]
    ); ?>

    <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>
    <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
    <?= Html::submitButton('Login', ['class' => 'btn btn-lg btn-primary btn-block', 'name' => 'login-button']) ?>

    <?php ActiveForm::end(); ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
