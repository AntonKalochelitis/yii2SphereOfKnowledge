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
        return [
            ['status', 'default', 'value' => (string)self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [(string)self::STATUS_ACTIVE, (string)self::STATUS_WAIT, (string)self::STATUS_DELETED]],
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
}
