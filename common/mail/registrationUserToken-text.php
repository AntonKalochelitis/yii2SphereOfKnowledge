<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user core\models\Users */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-registration', 'token' => $user->reset_token]);
?>
Hello <?= Html::encode($user->email) ?>,

Follow the link below to confirm your registration:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>
