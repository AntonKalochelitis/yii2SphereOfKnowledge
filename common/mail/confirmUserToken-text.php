<?php

/* @var $this yii\web\View */
/* @var $user core\models\AuthUsers */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->reset_token]);
?>
Hello <?= $user->email ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
