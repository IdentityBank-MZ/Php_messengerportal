<?php

use yii\widgets\DetailView;

?>
<div class="messages-view">

    <?= DetailView::widget(
        [
            'model' => $model,
            'attributes' =>
                [
                    'type',
                    'to:ntext',
                    [
                        'attribute' => 'data',
                        'format' => 'raw',
                        'value' => Yii::$app->controller->renderPartial('_view_sms_data', ['data' => $model->data])
                    ],
                ],
        ]
    ) ?>
</div>
