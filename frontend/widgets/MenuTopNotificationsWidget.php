<?php

namespace frontend\widgets;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use core\repositories\UsersNotification;

class MenuTopNotificationsWidget extends \yii\bootstrap\Widget
{
    public $directoryAsset;

    public function init():void
    {
        parent::init();
    }

    /**
     *
     */
    public function run():void
    {
        $modelUsersNotification = new UsersNotification();
        $count_unread_message   = $modelUsersNotification->getService()->getCountUnreadNotification(Yii::$app->user->identity->id);
        $list_notification      = $modelUsersNotification->getService()->getListNotificationByUserId(Yii::$app->user->identity->id);

//        print_r($list_notification);exit();
        ?>
        <li class="dropdown notifications-menu">
            <?= Html::a("<i class=\"fa fa-bell-o\"></i><span class=\"label label-warning\">" . $count_unread_message . "</span>", Url::toRoute('notifications/show-all'), [
                'class' => "dropdown-toggle",
                'data-toggle' => "dropdown",
            ]); ?>
            <ul class="dropdown-menu">
                <li class="header">You have <?= $count_unread_message ?> notifications</li>
                <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        <?php
                        foreach ($list_notification as $notification) {
                            ?>
                            <li>
                                <a href="#">
                                    <i class="fa <?= $notification['icon'] ?> <?= $notification['icon_color'] ?>"></i><?= $notification['message'] ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
                <li class="footer"><?= Html::a('View all', Url::toRoute('notifications/show-all')) ?></li>
            </ul>
        </li>
        <?php
        parent::run();
    }
}