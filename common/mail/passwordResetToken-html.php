<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user core\models\Users */

?>
<div class="password-reset">
    <p>Confirm <?= Html::encode($user->email) ?>,</p>
</div>
