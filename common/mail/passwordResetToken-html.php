<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user core\repositories\Users */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->reset_token]);
?>
<div class="password-reset">
    <p>Confirm <?= Html::encode($user->email) ?>,</p>
</div>

<div class="password-url"><?= $resetLink ?></div>