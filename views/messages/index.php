<?php

use app\helpers\MessengerPortalConfig;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->registerMetaTag(
    [
        'http-equiv' => 'refresh',
        'content' => MessengerPortalConfig::get()->getYii2MessengerPortalMessagesRefreshPeriod()
    ]
);

$this->title = 'Messages';
?>
<div class="messages-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php if (!empty($dataProvider)) : ?>
        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'columns' => [
                    'createtime',
                    [
                        'label' => 'Data',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $this->render(
                                'view',
                                [
                                    'model' => $model
                                ]
                            );
                        }
                    ],
                ],
            ]
        ); ?>
    <?php endif; ?>
    <?php Pjax::end(); ?>
</div>
