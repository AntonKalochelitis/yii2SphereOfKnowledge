<?php

namespace frontend\widgets;


class MenuTopAuthorizedUser extends \yii\bootstrap\Widget
{
    public $is_authorized = null;
    public $directoryAsset = '';

    public function init():void
    {
        parent::init();
    }

    public function run():void
    {
        if ($this->is_authorized) {
        ?>
            <ul class="nav navbar-nav">

                <?= \frontend\widgets\MenuTopMessagesWidget::widget(['directoryAsset' => $this->directoryAsset]) ?>

                <?= \frontend\widgets\MenuTopNotificationsWidget::widget(['directoryAsset' => $this->directoryAsset]) ?>

                <?= \frontend\widgets\MenuTopTasksWidget::widget(['directoryAsset' => $this->directoryAsset]) ?>

                <?= \frontend\widgets\MenuTopUserWidget::widget(['directoryAsset' => $this->directoryAsset]) ?>

                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        <?php
        }
        parent::run();
    }
}