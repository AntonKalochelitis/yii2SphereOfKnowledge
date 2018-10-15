<?php

namespace core\forms;

use Yii;
use yii\base\Model;
use core\repositories\Users;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\core\repositories\Users',
                'filter' => ['status' => [(string)Users::STATUS_ACTIVE, (string)Users::STATUS_WAIT]],
                'message' => Yii::t('Users', 'ERROR_NO_EMAIL_EXISTS')
            ],
        ];
    }

    public function getLoadAndValidate():bool
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            return true;
        }

        return false;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user Users */
        $user = Users::findOne([
            'status' => [(string)Users::STATUS_ACTIVE, (string)Users::STATUS_WAIT],
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!Users::isResetTokenValid($user->reset_token)) {
            $user->generateResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
