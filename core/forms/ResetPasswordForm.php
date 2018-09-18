<?php

namespace core\forms;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use core\models\AuthUsers;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $password_confirm;

    /**
     * @var \core\models\AuthUsers
     */
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
        $this->_user = AuthUsers::findByResetToken($token, (string)AuthUsers::STATUS_ACTIVE);
        if (!$this->_user) {
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
            [['password', 'password_confirm'], 'string', 'min' => 6],
            [['password', 'password_confirm'], 'string', 'max' => 80],
            // TODO: Реализовать через Yii::t('app', 'This field is required')]
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
            [['password', 'password_confirm'], 'match', 'pattern' => '#\d.*\d#s', 'message' => 'Пароль должен содержать минимум 2 буквы и 2 цифры.'],
            [['password', 'password_confirm'], 'match', 'pattern' => '#[a-z].*[a-z]#is', 'message' => 'Пароль должен содержать минимум 2 буквы и 2 цифры.'],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removeResetToken();

        return $user->save(false);
    }
}
