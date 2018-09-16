<?php

namespace frontend\widgets;

use Yii;
use dmstr\widgets\Menu;

class MenuLeftUserWidget extends \yii\bootstrap\Widget
{
    public $directoryAsset;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        ?>
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $this->directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <?php
        parent::run();
    }
}