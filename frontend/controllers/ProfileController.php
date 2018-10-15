<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use core\repositories\Users;
use core\forms\ProfileUpdateForm;
use core\forms\PasswordChangeForm;

class ProfileController extends Controller
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
            'model' => Users::getModel(),
        ]);
    }

    public function actionUpdate()
    {
        $model  = Users::getModel();
        $form   = new ProfileUpdateForm($model);

        if (Yii::$app->request->isPost) {
            if ($form->update()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionPasswordChange()
    {
        $user   = Users::getModel();
        $model  = new PasswordChangeForm($user);

        if ($model->changePassword()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('passwordChange', [
                'model' => $model,
            ]);
        }
    }
}