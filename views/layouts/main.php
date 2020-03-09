<?php

use app\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$userinfo = Yii::$app->user->identity->accountName . ' - [email: ' . Yii::$app->user->identity->email . '] - [mobile: '
    . Yii::$app->user->identity->phone . ']';
if (Yii::$app->user->identity->isAdministrator()) {
    $userinfo .= ' * ADMIN *';
}
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" style="position: relative;min-height: 100%;">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="margin-bottom: 60px;">
<?php $this->beginBody() ?>

<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand"><?= $userinfo ?></a>
        </div>
        <div class="navbar-header navbar-right">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?= $_SERVER["REQUEST_URI"] ?>"><span class="glyphicon glyphicon-refresh"
                                                                   aria-hidden="true"></span> &nbsp;Refresh</a></li>
                <li><a href="logout">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="wrap">
        <?= $content ?>
    </div>
</div>
</div>

<footer class="footer" style="position: absolute;bottom: 0;width: 100%;height: 60px;background-color: #f5f5f5;">
    <div class="container">
        <p class="text-muted" style="margin: 20px 0;">&copy; Messenger <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
