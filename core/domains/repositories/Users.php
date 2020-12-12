<?php

namespace core\repositories;

/**
 * Class Users
 *
 * @property int $userId
 * @property string $firstName
 * @property string $lastName
 * @property string $patronymicName
 * @property string $authKey
 * @property string $passwordHash
 * @property string $birthday
 * @property string $status
 * @property int $createdAt
 * @property int $updatedAt
 * @property int $deletedAt
 *
 * @package core\repositories
 */
class Users extends \core\abstracts\db\AbstractRepository
{
    protected const TABLE_NAME = 'users';
}