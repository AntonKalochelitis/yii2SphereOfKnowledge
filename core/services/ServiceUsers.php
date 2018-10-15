<?php

namespace core\services;

use Yii;
use core\repositories\Users;
use frontend\models\SignupForm;
use yii\helpers\Html;

class ServiceUsers
{

    private $user;

    public function __construct(Users $user)
    {
        $this->user = $user;
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function createUser():bool
    {
        if (!$this->user->validate()) {
            throw new \Exception('Bad validate fields');
        }

        return $this->user->save() ? true : false;
    }

    public function confirmRegistrationUser():bool
    {
        if ($this->user) {
            $this->user->status = (string)Users::STATUS_ACTIVE;
            $this->user->removeResetToken();

            if ($this->user->save()
                && Yii::$app->getUser()->login($this->user)
                && $this->user->getService()->sendMailByConfirmUser()
            ){
                return true;
            }
        }

        return false;
    }

    public function getListUsersByListArrayId(array $list_users_id):array
    {
        $list_return = [];
        $list_users = $this->user::find()->where(['id' => $list_users_id])->asArray()->all();

        foreach ($list_users as $user) {
            $list_return[$user['id']] = $user;
        }
        return $list_return;
    }

    public function sendMailByCreateUser():bool
    {
        if (!$this->user) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'registrationUserToken-html', 'text' => 'registrationUserToken-text'],
                ['user' => $this->user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' information center'])
            ->setTo($this->user->email)
            ->setSubject('Confirm registration on the site ' . Yii::$app->name ) // TODO: Написать коректное письмо
            ->send();
    }

    public function sendMailByConfirmUser():bool
    {
        if (!$this->user) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'confirmUserToken-html', 'text' => 'confirmUserToken-text'],
                ['user' => $this->user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' information center'])
            ->setTo($this->user->email)
            ->setSubject('Registration confirmed on the site ' . Yii::$app->name ) // TODO: Написать коректное письмо
            ->send();
    }

}