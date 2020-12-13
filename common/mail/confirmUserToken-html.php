<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\domains\repositories\Users */
/* @var $userMail \core\domains\repositories\UsersMail */

?>
<div class="password-reset">
    <p>Confirm <?= Html::encode($userMail->identifier) ?>,</p>
</div>
