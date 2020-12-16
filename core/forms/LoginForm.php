<?php

namespace core\forms;

use core\auth\AuthUsers;
use core\domains\services\ServiceUsers;
use core\domains\services\ServiceUsersMail;

/**
 * Login form
 */
class LoginForm extends \yii\base\Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $authUsers = ServiceUsers::getAuthUsersByIdentifier($this->username);

            if (!$authUsers || !ServiceUsers::validatePassword($this->password, $authUsers)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->load(\Yii::$app->request->post()) && $this->validate()) {
            $authUsers = ServiceUsers::getAuthUsersByIdentifier($this->username);

            return \Yii::$app->getUser()->login($authUsers, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }
}
