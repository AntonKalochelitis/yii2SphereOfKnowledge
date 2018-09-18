<?php

namespace core\forms;

use core\models\Users;
use Yii;
use yii\base\Model;

class ProfileUpdateForm extends Model
{
    public $email;
    public $first_name;
    public $last_name;
    public $patronymic_name;

    private $_user;

    public function __construct(Users $user, $config = [])
    {
        $this->_user = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->patronymic_name = $user->patronymic_name;
        $this->email = $user->email;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['first_name','last_name','patronymic_name', 'email'], 'trim'],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => Users::className(), 'filter' => ['<>', 'id', $this->_user->id], 'message' => Yii::t('app', 'ERROR_EMAIL_EXISTS')],
            ['email', 'string', 'max' => 80],

            ['first_name', 'match', 'pattern' => '/^[a-zA-Zа-яА-ЯёЁїЇіІЄєҐґуУшШщЩхХъэЭюЮрРтТьы\s]+$/is', 'message' => Yii::t('app', 'ERROR_FIRST_NAME_EXISTS')],
            ['last_name', 'match', 'pattern' => '/^[a-zA-Zа-яА-ЯёЁїЇіІЄєҐґуУшШщЩхХъэЭюЮрРтТьы\s]+$/is', 'message' => Yii::t('app', 'ERROR_LAST_NAME_EXISTS')],
            ['patronymic_name', 'match', 'pattern' => '/^[a-zA-Zа-яА-ЯёЁїЇіІЄєҐґуУшШщЩхХъэЭюЮрРтТьы\s]+$/is', 'message' => Yii::t('app', 'ERROR_PATRONYMIC_NAME_EXISTS')],
        ];
    }

    public function update():bool
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {

            $user                   = $this->_user;
            $user->first_name       = $this->first_name;
            $user->last_name        = $this->last_name;
            $user->patronymic_name  = $this->patronymic_name;
            $user->email            = $this->email;

            if (!$user->save()) {
                return false;
            }

            return true;
        }

        return false;
    }
}