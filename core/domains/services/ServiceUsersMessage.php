<?php

namespace core\domains\services;

use core\repositories\UsersMessage;

class ServiceUsersMessage
{
    private $user_message;

    public function __construct(UsersMessage $user_message)
    {
        $this->user_message = $user_message;
    }

    public function getListMessagesByUserId(int $user_id, int $limit = 20): array
    {
        return $this->user_message::find()->where(['user_id' => $user_id])->orderBy(['message_id' => SORT_DESC])->asArray()->limit($limit)->all();
    }

    public function getCountUnreadMessage(int $user_id): int
    {
        return $this->user_message::find()->where(['user_id' => $user_id, 'status' => 'delivered'])->asArray()->count();
    }

    public function getListUsersIdsByListMessages(array $list_messages): array
    {
        $list = [];
        foreach ($list_messages as $message) {
            $list[] = $message['friend_id'];
        }
        return array_unique($list);
    }
}