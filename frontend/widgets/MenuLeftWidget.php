<?php

namespace frontend\widgets;

use dmstr\widgets\Menu;

/**
 * Class MenuLeftWidget
 * @package frontend\widgets
 */
class MenuLeftWidget extends \yii\bootstrap\Widget
{
    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return string|void
     * @throws \Exception
     */
    public function run()
    {
        echo Menu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => [
                ['label' => 'Menu User', 'options' => ['class' => 'header'], 'visible' => \Yii::$app->user->isGuest],
                ['label' => 'Sign up', 'url' => ['site/sign-up'], 'visible' => \Yii::$app->user->isGuest],
                ['label' => 'Sign in', 'url' => ['site/sign-in'], 'visible' => \Yii::$app->user->isGuest],
                ['label' => 'Menu Site', 'options' => ['class' => 'header']],
//                ['label' => 'Library', 'url' => ['library/index']],
                ['label' => 'About', 'url' => ['site/about']],
                ['label' => 'Contact', 'url' => ['site/contact']],
            ],
        ]);

        parent::run();
    }
}