<?php

namespace frontend\widgets;

use core\repositories\Users;
use Yii;
use core\repositories\UsersMessage;
use yii\helpers\Html;
use yii\helpers\Url;

class MenuTopMessagesWidget extends \yii\bootstrap\Widget
{
    public $directoryAsset;

    public function init():void
    {
//        $script = 'setInterval( function() {';
//        $script .= '$.ajax({';
//        $script .= "url:'" . Url::toRoute('messages/messages-ajax-list-to-user') . "',";
//        $script .= "dataType: 'html',";
//        $script .= "success: function(data) {
//        var old_data = $('li.messages-menu').find('span.label-success').text();
//        var new_data = $($.parseHTML( '<html>' + data + '</html>' )).find('span.label-success').text();
//        if (old_data != new_data) {
//            $('li.messages-menu').html(data);
//        }
//        }";
//        $script .= '})';
//        $script .= '}, 2000)';
//
//        $this->view->registerJs($script);

        parent::init();
    }

    /**
     *
     */
    public function run():void
    {
        $modelUsersMessage      = new UsersMessage();
        $count_unread_message   = $modelUsersMessage->getService()->getCountUnreadMessage(Yii::$app->user->identity->id);
        $list_messages          = $modelUsersMessage->getService()->getListMessagesByUserId(Yii::$app->user->identity->id);
        $list_users_ids         = $modelUsersMessage->getService()->getListUsersIdsByListMessages($list_messages);

        $modelUsers             = new Users();
        $list_users             = $modelUsers->getService()->getListUsersByListArrayId($list_users_ids);
        ?>
        <li class="dropdown messages-menu">
            <?= Html::a("<i class=\"fa fa-envelope-o\"></i><span class=\"label label-success\">" . $count_unread_message . "</span>",Url::toRoute('messages/show-all'), [
                'class' => "dropdown-toggle",
                'data-toggle' => "dropdown"
            ]) ?>
            <ul class="dropdown-menu">
                <li class="header">You have <?= $count_unread_message ?> new messages</li>
                <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        <?php
                        foreach ($list_messages as $message) {
                            ?>
                            <li><!-- start message -->
                                <a href="#">
                                    <div class="pull-left">
                                        <!--<img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>--> <?php // TODO: Реализовать изображение ?>
                                    </div>
                                    <h4>
                                        <?= $list_users[$message['friend_id']]['last_name'] ?> <?= $list_users[$message['friend_id']]['first_name'] ?>
                                        <small><i class="fa fa-clock-o"></i><?= $message['sent_at'] ?></small>
                                    </h4>
                                    <p><?= $message['message'] ?></p>
                                </a>
                            </li>
                            <!-- end message -->
                            <?php
                        }

                        ?>

                    </ul>
                </li>
                <li class="footer"><?= Html::a('See All Messages', \yii\helpers\Url::toRoute('messages/show-all')) ?><a href="#"></a></li>
            </ul>
        </li>
        <?php
        parent::run();
    }
}