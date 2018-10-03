<?php

namespace core\forms;

use Yii;
use core\models\Users;
use yii\base\Model;

class PasswordChangeForm extends Model
{
    public $password_current;
    public $password_new;
    public $password_new_confirm;

    /**
     * @var Users
     */
    private $_user;

    /**
     * @param User $user
     * @param array $config
     */
    public function __construct(Users $user, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['password_current', 'password_new', 'password_new_confirm'], 'trim'],

            [['password_current', 'password_new', 'password_new_confirm'], 'required'],

            ['password_current', 'currentPassword'],

            ['password_new_confirm', 'compare', 'compareAttribute' => 'password_new'],

            [['password_new', 'password_new_confirm'], 'string', 'min' => 6],
            [['password_new', 'password_new_confirm'], 'string', 'max' => 80],
            [['password_new', 'password_new_confirm'], 'match', 'pattern' => '#\d.*\d#s', 'message' => 'Пароль должен содержать минимум 2 буквы и 2 цифры.'],
            [['password_new', 'password_new_confirm'], 'match', 'pattern' => '#[a-z].*[a-z]#is', 'message' => 'Пароль должен содержать минимум 2 буквы и 2 цифры.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password_new' => Yii::t('PasswordChange', 'USER_NEW_PASSWORD'),
            'password_new_confirm' => Yii::t('PasswordChange', 'USER_REPEAT_PASSWORD'),
            'password_current' => Yii::t('PasswordChange', 'USER_CURRENT_PASSWORD'),
        ];
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function currentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('app', 'ERROR_WRONG_CURRENT_PASSWORD'));
            }
        }
    }

    /**
     * @return boolean
     */
    public function changePassword()
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $user = $this->_user;
            $user->setPassword($this->password_new);
            return $user->save();
        } else {
            return false;
        }
    }
}