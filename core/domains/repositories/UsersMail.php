<?php

namespace core\domains\repositories;

use core\auth\AuthUsers;

/**
 * Class UsersMail
 *
 * @property int $userMailId
 * @property int $userId
 * @property string $identifier
 * @property string $description
 * @property string $token
 * @property int $verification
 * @property int $status
 *
 * @property AuthUsers authUser
 *
 * @package core\repositories
 */
class UsersMail extends \core\abstracts\db\AbstractRepository
{
    protected const TABLE_NAME = 'usersMail';

    public const VERIFICATION_ACTIVE = 1;
    public const VERIFICATION_NO_ACTIVE = 0;

    public const STATUS_ACTIVE = 1;
    public const STATUS_NO_ACTIVE = 0;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['userMailId', 'userId'], 'integer'],
            [['identifier', 'description', 'token'], 'string'],
            [['verification', 'status'], 'boolean'],
        ];
    }

    /**
     * Removes password reset token
     */
    public function removeResetToken()
    {
        $this->token = null;
    }

    /**
     * @return AuthUsers|null
     */
    public function getAuthUser(): ?AuthUsers
    {
        /** @var AuthUsers $authUsers */
        $authUsers = AuthUsers::find()->where([
            'userId' => $this->userId
        ])->one();

        return $authUsers;
    }

}