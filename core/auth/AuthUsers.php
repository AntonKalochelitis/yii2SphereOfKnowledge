<?php

namespace core\auth;

use core\domains\repositories\Users;
use core\domains\services\ServiceUsers;
use core\helpers\DebugHelper;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * Class AuthUsers
 *
 * @package core\domains\entities
 */
class AuthUsers extends Users implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        try {
            $authUser = ServiceUsers::getUserByUserIdAndStatus($id);
        } catch (\Exception $e) {
            if (!\Yii::$app->user->logout()) {
                throw new \yii\base\Exception('Выход из системы завершился неудачей.');
            }
            $authUser = null;
        }

        return $authUser;
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return null|void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}