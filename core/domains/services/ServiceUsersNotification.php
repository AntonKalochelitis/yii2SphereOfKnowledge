<?php
namespace core\services;

use core\repositories\UsersNotification;

class ServiceUsersNotification
{
    private $user_notification;

    public function __construct(UsersNotification $user_notification)
    {
        $this->user_notification = $user_notification;
    }

    public function getListNotificationByUserId(int $user_id, int $limit = 20):array
    {
        return $this->user_notification::find()->where(['user_id' => $user_id])->orderBy(['notification_id' => SORT_DESC])->asArray()->limit($limit)->all();
    }

    public function getCountUnreadNotification($user_id):int
    {
        return $this->user_notification::find()->where(['user_id' => $user_id, 'status' => 'delivered'])->asArray()->count();
    }
}