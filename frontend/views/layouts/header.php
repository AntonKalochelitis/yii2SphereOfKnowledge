<?php

use Yii;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">SOK</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <?= \frontend\widgets\MenuTopAuthorizedUser::widget(['is_authorized' => !Yii::$app->user->isGuest, 'directoryAsset' => $directoryAsset]) // Удалить 'directoryAsset' => $directoryAsset ?>
        </div>
    </nav>
</header>
