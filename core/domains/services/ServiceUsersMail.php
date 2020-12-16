<?php

namespace core\domains\services;

use core\auth\AuthUsers;
use core\domains\repositories\UsersMail;

/**
 * Class ServiceUsersMail
 * @package core\services
 */
class ServiceUsersMail
{
    /**
     * @param string $identifier
     * @return AuthUsers|null
     */
    public static function getUsersByIdentifier(string $identifier): ?AuthUsers
    {
        /** @var UsersMail $usersMail */
        $usersMail = UsersMail::find()->where([
            'identifier' => $identifier
        ])->one();

        return (!empty($usersMail->authUser))?$usersMail->authUser:null;
    }

    /**
     * @param int $userId
     * @return AuthUsers|null
     */
    public static function getAuthUserByUserId(int $userId): ?AuthUsers
    {
        /** @var AuthUsers $authUsers */
        $authUsers = AuthUsers::find()->where([
            'userId' => $userId
        ])->one();

        return $authUsers;
    }

    /**
     * @param int $userId
     * @return UsersMail[]|null
     */
    public static function getUsersMailsByUserId(int $userId): ?array
    {
        /** @var UsersMail[] $usersMail */
        $usersMail = UsersMail::find()->where([
            'userId' => $userId
        ])->all();

        return $usersMail;
    }

    /**
     * @param string $token
     * @return UsersMail|null
     * @throws \Exception
     */
    public static function findByResetMailToken(string $token): ?UsersMail
    {
        /* @var UsersMail $userMail */
        $userMail = UsersMail::find()->where(['token' => $token])->one();

        return $userMail;
    }
}