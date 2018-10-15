<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user core\repositories\Users */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-registration', 'token' => $user->reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->email) ?>,</p>

    <p>Follow the link below to confirm your registration:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
