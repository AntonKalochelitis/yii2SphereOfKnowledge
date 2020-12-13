<?php

namespace frontend\forms;

/**
 * Signup form
 *
 * Class SignupForm
 *
 * @property string $identifier
 * @property string $password
 * @property string $password_confirm
 *
 * @package core\forms
 */
class SignUpForm extends \yii\base\Model
{
    public $identifier;
    public $password;
    public $password_confirm;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['identifier', 'trim'],
            ['identifier', 'required'],
            ['identifier', 'email'],
            ['identifier', 'string', 'max' => 255],
            ['identifier', 'unique', 'targetClass' => '\core\domains\repositories\UsersMail', 'message' => \Yii::t('Users', 'ERROR_EMAIL_EXISTS')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'message' => \Yii::t('Users', 'ERROR_MIN_CHAR_PASSWORD_EXISTS')],
            ['password', 'string', 'max' => 80, 'message' => \Yii::t('Users', 'ERROR_MAX_CHAR_PASSWORD_EXISTS')],

            ['password_confirm', 'required'],
            ['password_confirm', 'string', 'min' => 6, 'message' => 'ERROR_MIN_CHAR_PASSWORD_CONFIRM_EXISTS'],
            ['password_confirm', 'string', 'max' => 80, 'message' => 'ERROR_MAX_CHAR_PASSWORD_CONFIRM_EXISTS'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],

            [['password', 'password_confirm'], 'match', 'pattern' => '#\d.*\d#s', 'message' => \Yii::t('Users', 'ERROR_MIN_MAX_2CHAR_2NUM')],
            [['password', 'password_confirm'], 'match', 'pattern' => '#[a-z].*[a-z]#is', 'message' => \Yii::t('Users', 'ERROR_MIN_MAX_2CHAR_2NUM')],
        ];
    }

    /**
     * @return bool
     */
    public function getUserByValidateSignUp():bool
    {
        $this->load(\Yii::$app->request->post());

        if (!$this->validate()) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'identifier' => 'Email',
            'password' => 'Password',
            'password_confirm' => 'Password Confirm',
        ];
    }
}
