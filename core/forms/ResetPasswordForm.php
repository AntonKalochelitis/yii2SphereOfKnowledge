<?php

namespace core\forms;

use core\auth\AuthUsers;
use core\domains\repositories\UsersMail;
use core\domains\services\ServiceUsers;
use core\domains\services\ServiceUsersMail;
use core\helpers\ArrayHelper;
use yii\base\InvalidArgumentException;
use core\domains\repositories\Users;

/**
 * Password reset form
 *
 * Class ResetPasswordForm
 *
 * @property string $password
 * @property string $password_confirm
 * @property null|AuthUsers $_user
 *
 * @package core\forms
 */
class ResetPasswordForm extends \yii\base\Model
{
    public $password;
    public $password_confirm;

    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }

        try {
            $userMail =  ServiceUsersMail::findByResetMailToken($token);
        } catch (\Exception $e) {
            dd($e, 1);
        }

        if (
            !empty($userMail)
        && ($userMail->authUser->status == (string)Users::STATUS_ACTIVE || $userMail->authUser->status == (string)Users::STATUS_WAIT)
        ) {
            $this->_user = $userMail->authUser;
        } else {
            throw new InvalidArgumentException('Wrong password reset token.');
        }

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'password_confirm'], 'required'],
            [['password', 'password_confirm'], 'string', 'min' => 6, 'message' => \Yii::t('Users', 'ERROR_MIN_CHAR_PASSWORD_CONFIRM_EXISTS')],
            [['password', 'password_confirm'], 'string', 'max' => 80, 'message' => \Yii::t('Users', 'ERROR_MAX_CHAR_PASSWORD_CONFIRM_EXISTS')],

            ['password_confirm', 'compare', 'compareAttribute' => 'password'],

            [['password', 'password_confirm'], 'match', 'pattern' => '#\d.*\d#s', 'message' => \Yii::t('Users', 'ERROR_MIN_MAX_2CHAR_2NUM')],
            [['password', 'password_confirm'], 'match', 'pattern' => '#[a-z].*[a-z]#is', 'message' => \Yii::t('Users', 'ERROR_MIN_MAX_2CHAR_2NUM')],
        ];
    }

    /**
     * Resets password. true if password was reset.
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function resetPassword():bool
    {
        $authUsers = $this->_user;
        $authUsers->setPassword($this->password);

        /** @var UsersMail[] $userMails */
        $userMails = ArrayHelper::index($authUsers->userMails, 'userMailId');

        if (!$authUsers->save()) {
            return false;
        }

        foreach ($userMails as $userMail) {
            $userMail->removeResetToken();
            $userMail->verification = UsersMail::VERIFICATION_ACTIVE;
            if (!$userMail->save(false)) {
                return false;
            }

            // TODO: Отправить письмо о том, что изменен пароль
        }

        return true;
    }
}
