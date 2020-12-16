<?php

namespace core\forms;

use core\domains\repositories\Users;
use core\domains\repositories\UsersMail;
use core\domains\services\ServiceUsers;
use core\domains\services\ServiceUsersMail;
use core\helpers\ArrayHelper;

/**
 * Password reset request form
 *
 * Class PasswordResetRequestForm
 *
 * @property string $identifier
 *
 * @package core\forms
 */
class PasswordResetRequestForm extends \yii\base\Model
{
    public $identifier;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['identifier', 'trim'],
            ['identifier', 'required'],
            ['identifier', 'email'],
            ['identifier', 'checkEmail']
//                'targetClass' => '\core\domains\repositories\UsersMail',
//                'filter' => ['status' => [(string)Users::STATUS_ACTIVE, (string)Users::STATUS_WAIT]],
//                'message' => \Yii::t('Users', 'ERROR_NO_EMAIL_EXISTS')
//            ],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $authUsers = ServiceUsersMail::getAuthUsersByIdentifier($this->$attribute);

            if (
                !empty($authUsers)
                && ($authUsers->status == (string)Users::STATUS_ACTIVE || $authUsers->status == (string)Users::STATUS_WAIT)
            ) {
                //
            } else {
                $this->addError($attribute, \Yii::t('Users', 'ERROR_NO_EMAIL_EXISTS'));
            }
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'identifier' => 'Email',
        ];
    }

    /**
     * @return bool
     */
    public function getLoadAndValidate(): bool
    {
        if ($this->load(\Yii::$app->request->post()) && $this->validate()) {
            return true;
        }

        return false;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail():bool
    {
        $authUsers = ServiceUsersMail::getAuthUsersByIdentifier($this->identifier);

        if (!$authUsers) {
            return false;
        }

        if (
            !empty($authUsers)
            && ($authUsers->status == (string)Users::STATUS_ACTIVE || $authUsers->status == (string)Users::STATUS_WAIT)
        ) {
            //
        } else {
            return false;
        }

        /** @var UsersMail[] $userMails */
        $userMails = ArrayHelper::index($authUsers->userMails, 'userMailId'); // Отсекаем ассоциативный массив по emails
        foreach ($userMails as $userMail) {
            try {
                if (!ServiceUsers::isResetTokenValid($userMail->token)) {
                    $userMail->token = ServiceUsers::generateResetToken();
                    if (!$userMail->save()) {
                        return false;
                    }
                }
            } catch (\yii\base\Exception $e) {
                dd($e, 1);
            }

            return \Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                    ['authUsers' => $authUsers, 'userMail' => $userMail]
                )
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                ->setTo($this->identifier)
                ->setSubject('Password reset for ' . \Yii::$app->name)
                ->send();
        }
    }
}
