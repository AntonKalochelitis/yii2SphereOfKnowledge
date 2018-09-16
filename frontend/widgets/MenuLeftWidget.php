<?php

namespace frontend\widgets;

use Yii;
use dmstr\widgets\Menu;

class MenuLeftWidget extends \yii\bootstrap\Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        echo Menu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => [
                ['label' => 'Menu User', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->isGuest],
                ['label' => 'Signup', 'url' => ['site/signup'], 'visible' => Yii::$app->user->isGuest],
                ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                ['label' => 'Menu Site', 'options' => ['class' => 'header']],
                ['label' => 'About', 'url' => ['site/about']],
                ['label' => 'Contact', 'url' => ['site/contact']],
            ],
        ]);

        parent::run();
    }
}