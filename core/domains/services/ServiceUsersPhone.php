<?php

namespace core\domains\services;

use core\auth\AuthUsers;
use core\domains\repositories\UsersPhone;

/**
 * Class ServiceUsersPhone
 * @package core\services
 */
class ServiceUsersPhone
{
    /**
     * @param string $identifier
     * @return AuthUsers|null
     */
    public static function getUsersByIdentifier(string $identifier): ?AuthUsers
    {
        /** @var UsersPhone $usersPhone */
        $usersPhone = UsersPhone::find()->where([
            'identifier' => $identifier
        ])->one();

        return (!empty($usersPhone->authUser))?$usersPhone->authUser:null;
    }
}