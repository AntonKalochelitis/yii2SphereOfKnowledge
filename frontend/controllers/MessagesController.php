<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use core\repositories\UsersMessage;

class MessagesController extends Controller
{
    public function behaviors():array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
//            'model' => AuthUsers::getModel(),
        ]);
    }

    public function actionMessagesAjaxListToUser()
    {
        $list_message = (new UsersMessage)->getService()->getListMessagesByUserId(Yii::$app->user->identity->getId());
//        print_r(json_encode($list_message, JSON_UNESCAPED_UNICODE));
        return $this->renderPartial('messages-ajax-list-to-user', [
            'list_message' => $list_message,
        ]);
    }

    public function actionMessagesShowAllToUser()
    {
        return $this->render('messages-show-all-to-user', [
//            'model' => AuthUsers::getModel(),
        ]);
    }
}