<?php

namespace core\forms;

use core\models\Users;
use Yii;
use yii\base\Model;
use core\models\AuthUsers;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $password_confirm;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // TODO: Реализовать через Yii::t('app', 'This field is required')]
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\core\models\AuthUsers', 'message' => 'This email address has already been taken.'],

            // TODO: Реализовать через Yii::t('app', 'This field is required')]
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password', 'string', 'max' => 80, 'message' => 'Максимальное количество 80 символов'],

            // TODO: Реализовать через Yii::t('app', 'This field is required')]
            ['password_confirm', 'required'],
            ['password_confirm', 'string', 'min' => 6],
            ['password_confirm', 'string', 'max' => 80, 'message' => 'Максимальное количество 80 символов'],

            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function getUserByValidateSignup():?AuthUsers
    {
        $this->load(Yii::$app->request->post());

        if (!$this->validate()) {
            return null;
        }
        
        $user = new AuthUsers();
        $user->email = $this->email;
        $user->status = (string)Users::STATUS_WAIT;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateResetToken();
        
        return $user->save() ? $user : null;
    }
}
