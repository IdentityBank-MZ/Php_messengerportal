<?php

use yii\widgets\DetailView;

?>
<div class="messages-view">

    <?= DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'tag:ntext',
                'type',
                'status',
                'from:ntext',
                'to:ntext',
                'attributes:ntext',
                'data:ntext',
            ],
        ]
    ) ?>
</div>
