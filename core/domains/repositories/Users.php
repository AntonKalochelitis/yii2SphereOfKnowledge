<?php

namespace core\domains\repositories;

use core\domains\services\ServiceUsersMail;
use core\helpers\ArrayHelper;

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
 * @property UsersMail[] userMails
 * @property UsersPhone[] userPhones
 *
 * @package core\repositories
 */
class Users extends \core\abstracts\db\AbstractRepository
{
    protected const TABLE_NAME = 'users';

    public const STATUS_BLOCK = 'block';
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DELETE = 'delete';

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName', 'patronymicName'], 'trim'],
            [['firstName', 'lastName', 'patronymicName', 'authKey', 'passwordHash', 'birthday', 'status'], 'string'],

            [['userId', 'createdAt', 'updatedAt', 'deletedAt'], 'integer'],
        ];
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword(string $password): void
    {
        $this->passwordHash = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     *
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->authKey = \Yii::$app->security->generateRandomString();
    }

    /**
     * @return array|null
     */
    public function getUserMails(): ?array
    {
        /** @var UsersMail $usersMail */
        $usersMail = ServiceUsersMail::getUsersMailsByUserId($this->userId);
        $usersMail =
            ArrayHelper::index($usersMail, 'userMailId')
            +
            ArrayHelper::index($usersMail, 'identifier');

        return $usersMail;
    }

    public function getUserPhones()
    {

    }
}