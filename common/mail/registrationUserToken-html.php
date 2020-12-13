<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\domains\repositories\Users */
/* @var $userMail \core\domains\repositories\UsersMail */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl([
    'site/confirm-registration',
    'token' => $userMail->token
]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($userMail->identifier) ?>,</p>

    <p>Follow the link below to confirm your registration:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
