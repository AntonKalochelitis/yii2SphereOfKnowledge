<?php

namespace core\domains\services;

use Carbon\Carbon;
use core\auth\AuthUsers;
use core\domains\repositories\Users;
use core\domains\repositories\UsersMail;
use core\helpers\DebugHelper;
use frontend\forms\SignUpForm;

/**
 * Class ServiceUsers
 * @package core\services
 */
class ServiceUsers
{
    /**
     * @param string $identifier
     * @return AuthUsers|null
     */
    public static function getUsersByIdentifier(string $identifier): ?AuthUsers
    {

    }

    /**
     * @param $id
     * @param string $status
     * @return Users|null
     */
    public static function getUserByUserIdAndStatus($id, $status = Users::STATUS_ACTIVE): ?Users
    {
        /** @var Users $authUser */
        $authUser = Users::find()->where([
            'userId' => $id,
            'status' => $status,
        ])->one();

        return $authUser;
    }

    /**
     * Signs user up.
     *
     * @param SignUpForm $form
     * @return Users
     * @throws \yii\base\Exception
     */
    public static function createUser(SignUpForm $form): Users
    {
        $user = new Users();

        $user->status = (string)Users::STATUS_WAIT;
        $user->setPassword($form->password);
        $user->generateAuthKey();
        $user->createdAt = time();
        $user->updatedAt = time();

        if (!$user->validate()) {
            throw new \Exception('Bad validate Users fields');
        }

        if (!$user->save(0)) {
            throw new \Exception('Bad validate Users save');
        }

        $usersMail = new UsersMail();
        $usersMail->userId = $user->userId;
        $usersMail->identifier = $form->identifier;
        $usersMail->token = ServiceUsers::generateResetToken();
        $usersMail->verification = UsersMail::VERIFICATION_NO_ACTIVE;
        $usersMail->status = UsersMail::STATUS_ACTIVE;

        if (!$usersMail->validate()) {
            throw new \Exception('Bad validate UsersMail fields');
        }

        if (!$usersMail->save(0)) {
            throw new \Exception('Bad validate UsersMail save');
        }

        return $user;
    }

    /**
     * @param UsersMail $userMail
     * @return bool
     */
    public static function confirmRegistrationUser(UsersMail $userMail): bool
    {
        if ($userMail->authUser) {
            $userMail->authUser->status = (string)Users::STATUS_ACTIVE;

            $userMail->removeResetToken();
            $userMail->verification = UsersMail::VERIFICATION_ACTIVE;

            if (
                $userMail->authUser->save() && $userMail->save()
                && \Yii::$app->getUser()->login($userMail->authUser)
                && static::sendMailByConfirmUser($userMail->authUser)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $list_users_id
     *
     * @return array
     */
    public function getListUsersByListArrayId(array $list_users_id): array
    {
        $list_return = [];
        $list_users = $this->user::find()->where(['id' => $list_users_id])->asArray()->all();

        foreach ($list_users as $user) {
            $list_return[$user['id']] = $user;
        }
        return $list_return;
    }

    /**
     * @param Users $user
     * @return bool
     */
    public static function sendMailByCreateUser(Users $user): bool
    {
        if (!$user) {
            return false;
        }

        try {
            foreach ($user->userMails as $userMail) {
                if (!empty($userMail->token)) {
                    \Yii::$app
                        ->mailer
                        ->compose(
                            ['html' => 'registrationUserToken-html', 'text' => 'registrationUserToken-text'],
                            ['user' => $user, 'userMail' => $userMail]
                        )
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' information center'])
                        ->setTo($userMail->identifier)
                        ->setSubject('Confirm registration on the site ' . \Yii::$app->name)
                        ->send();
                }
            }
        } catch (\Exception $e) {
            \Yii::error(print_r($e, 1));

            return false;
        }

        return true;
    }

    /**
     * @param Users $user
     *
     * @return bool
     */
    public static function sendMailByConfirmUser(Users $user): bool
    {
        if (!$user) {
            return false;
        }

        try {
            foreach ($user->userMails as $userMail) {
                \Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => 'confirmUserToken-html', 'text' => 'confirmUserToken-text'],
                        ['user' => $user, 'userMail' => $userMail]
                    )
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' information center'])
                    ->setTo($userMail->identifier)
                    ->setSubject('Registration confirmed on the site ' . \Yii::$app->name)// TODO: Написать коректное письмо
                    ->send();
            }
        } catch (\Exception $e) {
            \Yii::error(print_r($e, 1));

            return false;
        }


        return true;
    }

    /**
     * Generates new password reset token
     *
     * @return string
     * @throws \yii\base\Exception
     */
    public static function generateResetToken()
    {
        return \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param null|string $token
     * @return bool
     */
    public static function isResetTokenValid(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }
}