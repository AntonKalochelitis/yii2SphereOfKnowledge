<?php
namespace core\repositories;

use core\abstracts\AbstractRepository;
use core\services\ServiceUsersNotification;

class UsersNotification extends AbstractRepository
{
    protected const TABLE_NAME = 'users_notification';

    protected function initService() {
        return new ServiceUsersNotification($this);
    }
}