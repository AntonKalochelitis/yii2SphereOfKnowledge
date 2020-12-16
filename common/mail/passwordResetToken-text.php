<?php

/* @var $this yii\web\View */
/* @var $authUsers \core\domains\repositories\Users */
/* @var $userMail \core\domains\repositories\UsersMail */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $userMail->token]);
?>
Hello <?= $userMail->token ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
