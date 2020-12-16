<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $authUsers \core\domains\repositories\Users */
/* @var $userMail \core\domains\repositories\UsersMail */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $userMail->token]);
?>
<div class="password-reset">
    <p>Confirm <?= Html::encode($userMail->token) ?>,</p>
</div>

<div class="password-url"><?= $resetLink ?></div>