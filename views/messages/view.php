<?php

$view = null;
$parmas = ['model' => $model];

switch ($model->type) {
    case 'sms':
        {
            $view = '_view_sms';
        }
        break;
    case 'email':
        {
            $view = '_view_email';
        }
        break;
    default:
        {
            $view = '_view_generic';
        }
        break;
}

if ($view) {
    echo Yii::$app->controller->renderPartial($view, $parmas);
}
