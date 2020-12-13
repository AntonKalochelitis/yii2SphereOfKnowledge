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