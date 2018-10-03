<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

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

    public function actionMessagesShowAllToUser()
    {
        return $this->render('messages-show-all-to-user', [
//            'model' => AuthUsers::getModel(),
        ]);
    }
}