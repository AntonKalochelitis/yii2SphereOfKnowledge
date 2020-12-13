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
Hello <?= Html::encode($userMail->identifier) ?>,

Follow the link below to confirm your registration:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>
