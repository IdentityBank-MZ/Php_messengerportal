<?php
$data = json_decode($data, true);

if (!empty($data['body'])) {
    ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $data['body'] ?>
        </div>
    </div>
    <?php
}
