<?php
$data = json_decode($data, true);

if (!empty($data['body']['smtpRaw'])) {
    $smtpView = $data['body']['smtpRaw'];
    $smtpView = "
<div class=\"container\">
  <div class=\"panel-group\">
    <div class=\"panel panel-default\">
      <div class=\"panel-heading\">
        <h4 class=\"panel-title\">
          <a data-toggle=\"collapse\" href=\"#smtp\">Click to view RAW SMTP body</a>
        </h4>
      </div>
      <div id=\"smtp\" class=\"panel-collapse collapse\">
        <div class=\"panel-body\">
        <pre>
            <code>" . PHP_EOL . $smtpView . PHP_EOL . "</code>
        </pre>
        </div>
      </div>
    </div>
  </div>
</div>
    ";
    $data['body'] = $smtpView;
}

if (is_array($data['body'])) {
    if (!empty($data['body']['html'])) {
        $data['body'] = $data['body']['html'];
    } else {
        $data['body'] = json_encode($data['body']);
    }
}

if (!empty($data['subject']) && !empty($data['body'])) {
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><?= $data['subject'] ?></div>
        <div class="panel-body">
            <?= $data['body'] ?>
        </div>
    </div>
    <?php
}
