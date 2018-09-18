<?php

namespace core\models;

use Yii;
use core\services\ServiceUsers;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Users extends ActiveRecord
{
    const STATUS_DELETED    = '0';
    const STATUS_WAIT       = '5';
    const STATUS_ACTIVE     = '10';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    public function getService() {
        return new ServiceUsers($this);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        // TODO: СДелать мультиязычность
        return [
            [['first_name','last_name','patronymic_name', 'email'], 'trim'],

            ['email', 'email'],
            ['email', 'unique', 'targetClass' => Users::className(), 'filter' => ['<>', 'id', $this->getModel()->id], 'message' => Yii::t('app', 'ERROR_EMAIL_EXISTS')],
            ['email', 'string', 'max' => 80],

            ['status', 'default', 'value' => (string)self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [(string)self::STATUS_ACTIVE, (string)self::STATUS_WAIT, (string)self::STATUS_DELETED]],

            ['first_name', 'match', 'pattern' => '/^[a-zA-Zа-яА-ЯёЁїЇіІЄєҐґуУшШщЩхХъэЭюЮрРтТьы\s]+$/is', 'message' => 'ERROR_FIRST_NAME_EXISTS'],
            ['last_name', 'match', 'pattern' => '/^[a-zA-Zа-яА-ЯёЁїЇіІЄєҐґуУшШщЩхХъэЭюЮрРтТьы\s]+$/is', 'message' => 'ERROR_LAST_NAME_EXISTS'],
            ['patronymic_name', 'match', 'pattern' => '/^[a-zA-Zа-яА-ЯёЁїЇіІЄєҐґуУшШщЩхХъэЭюЮрРтТьы\s]+$/is', 'message' => 'ERROR_PATRONYMIC_NAME_EXISTS'],
        ];
    }

    public function attributeLabels():array
    {
        return [
            'first_name'        => Yii::t('app', 'FIRST_NAME_TITLE'),
            'last_name'         => Yii::t('app', 'LAST_NAME_TITLE'),
            'patronymic_name'   => Yii::t('app', 'PATRONYMIC_NAME_TITLE'),
            'email'             => Yii::t('app', 'EMAIL_TITLE'),
            'status'            => Yii::t('app', 'STATUS_TITLE'),
        ];
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($username):?Users
    {
        return static::findOne(['email' => $username, 'status' => (string)self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token
     * @param string $status
     * @return Users|null
     */
    public static function findByResetToken(string $token, string $status = ''):?Users
    {
        if (!static::isResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'reset_token' => $token,
            'status' => ((empty($status))?(string)self::STATUS_WAIT:$status)
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generateResetToken()
    {
        $this->reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removeResetToken()
    {
        $this->reset_token = null;
    }

    /**
     * @return User the loaded model
     */
    public static function getModel()
    {
        if (!empty(Yii::$app->user->identity->getId())) {
            return AuthUsers::findOne(Yii::$app->user->identity->getId());
        }
    }
}
