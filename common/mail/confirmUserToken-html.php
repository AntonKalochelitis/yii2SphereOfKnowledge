<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user core\repositories\Users */

?>
<div class="password-reset">
    <p>Confirm <?= Html::encode($user->email) ?>,</p>
</div>
