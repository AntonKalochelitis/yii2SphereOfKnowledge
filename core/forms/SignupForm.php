<?php

namespace core\forms;

use Yii;
use yii\base\Model;
use core\repositories\Users;

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
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\core\repositories\Users', 'message' => Yii::t('Users', 'ERROR_EMAIL_EXISTS')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'message' => Yii::t('Users', 'ERROR_MIN_CHAR_PASSWORD_EXISTS')],
            ['password', 'string', 'max' => 80, 'message' => Yii::t('Users', 'ERROR_MAX_CHAR_PASSWORD_EXISTS')],

            ['password_confirm', 'required'],
            ['password_confirm', 'string', 'min' => 6, 'message' => 'ERROR_MIN_CHAR_PASSWORD_CONFIRM_EXISTS'],
            ['password_confirm', 'string', 'max' => 80, 'message' => 'ERROR_MAX_CHAR_PASSWORD_CONFIRM_EXISTS'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
            [['password', 'password_confirm'], 'match', 'pattern' => '#\d.*\d#s', 'message' => Yii::t('Users', 'ERROR_MIN_MAX_2CHAR_2NUM')],
            [['password', 'password_confirm'], 'match', 'pattern' => '#[a-z].*[a-z]#is', 'message' => Yii::t('Users', 'ERROR_MIN_MAX_2CHAR_2NUM')],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function getUserByValidateSignup():?Users
    {
        $this->load(Yii::$app->request->post());

        if (!$this->validate()) {
            return null;
        }
        
        $user = new Users();
        $user->email = $this->email;
        $user->status = (string)Users::STATUS_WAIT;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateResetToken();
        
        return $user->save() ? $user : null;
    }
}
