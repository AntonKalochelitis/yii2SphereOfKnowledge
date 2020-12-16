<?php

namespace frontend\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use core\auth\AuthUsers;

/**
 * Class MenuTopUserWidget
 * @package frontend\widgets
 */
class MenuTopUserWidget extends \yii\bootstrap\Widget
{
    public $directoryAsset;

    /**
     *
     */
    public function init():void
    {
        parent::init();
    }

    /**
     *
     */
    public function run():void
    {
        /** @var AuthUsers $identity */
        $identity = \Yii::$app->user->identity;
        ?>
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= $this->directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                <span class="hidden-xs"><?= $identity->firstName . ' ' . $identity->lastName ?></span>
            </a>
            <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                    <img src="<?= $this->directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                         alt="User Image"/>

                    <p><?= $identity->firstName . ' ' . $identity->lastName ?></p>
                    <p>Web Developer</p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                    <div class="col-xs-4 text-center">
                        <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                        <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                        <a href="#"><?= Html::a('Friends', Url::toRoute('friends/index') );  ?></a>
                    </div>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <div class="pull-left">
                        <?= $this->MenuProfile() ?>
                    </div>
                    <div class="pull-right">
                        <?= $this->MenuLogout() ?>
                    </div>
                </li>
            </ul>
        </li>
        <?php
        parent::run();
    }

    /**
     * @return string
     */
    private function MenuProfile():string
    {
        return Html::a(
            'Profile',
            ['/profile/index'],
            ['class' => 'btn btn-default btn-flat']
        );
    }

    /**
     * @return string
     */
    private function MenuLogout():string
    {
        return Html::a(
            'Sign out',
            ['/sign-out'],
            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
        );
    }
}