<?php

namespace core\domains\repositories;

/**
 * Class UsersPhone
 *
 * @property int $userPhoneId
 * @property int $userId
 * @property string $identifier
 * @property string $token
 * @property string $description
 * @property int $verification
 * @property int $status
 *
 * @package core\repositories
 */
class UsersPhone extends \core\abstracts\db\AbstractRepository
{
    protected const TABLE_NAME = 'usersPhone';

    public const VERIFICATION_ACTIVE = 1;
    public const VERIFICATION_NO_ACTIVE = 0;

    public const STATUS_ACTIVE = 1;
    public const STATUS_NO_ACTIVE = 0;

    /**
     * Removes password reset token
     */
    public function removeResetToken()
    {
        $this->token = null;
    }
}