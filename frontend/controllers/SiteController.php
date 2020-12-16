<?php

namespace frontend\controllers;

use core\domains\repositories\UsersMail;
use core\domains\services\ServiceUsersMail;
use core\helpers\DebugHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\InvalidArgumentException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use core\auth\AuthUsers;
use core\forms\LoginForm;
use core\forms\PasswordResetRequestForm;
use core\forms\ResetPasswordForm;
use core\forms\ContactForm;
use core\domains\services\ServiceUsers;
use frontend\forms\SignUpForm;

/**
 * Class SiteController
 * @package frontend\controllers
 */
class SiteController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        // Не авторизованные пользователи
                        'actions' => [
                            'index', 'sign-up', 'sign-in', 'contact', 'about', 'confirm-registration',
                            'confirm-registration-successful', 'confirm-registration-reject', 'successful-registration',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        // Авторизованные пользователи
                        'actions' => ['index', 'sign-out'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login in a user.
     *
     * @return mixed
     */
    public function actionSignIn()
    {
        $form = new LoginForm();

        if (\Yii::$app->request->isPost) {
            if ($form->login()) {
                return $this->redirect('/');
            }
        }

        $form->password = '';
        return $this->render('sign-in', [
            'model' => $form,
        ]);
    }

    /**
     * Logout the current user.
     *
     * @return mixed
     */
    public function actionSignOut()
    {
        if (!\Yii::$app->user->isGuest) {
            \Yii::$app->user->logout();
        }

        return $this->redirect('/');
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(\Yii::$app->params['adminEmail'])) {
                \Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                \Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return string
     * @throws \Exception
     */
    public function actionSignUp()
    {
        $form = new SignUpForm();

        if (\Yii::$app->request->isPost) {
            if ($form->getUserByValidateSignUp()) {
                $user = ServiceUsers::createUser($form);
                ServiceUsers::sendMailByCreateUser($user);

                $this->redirect(Url::toRoute('site/confirm-registration-successful'));
            }
        }

        return $this->render('sign-up', [
            'model' => $form,
        ]);
    }

    /**
     * @param string $token
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionConfirmRegistration(string $token = '')
    {
        if (!empty($token)) {
            /* @var $user UsersMail */
            $userMail = ServiceUsersMail::findByResetMailToken($token);

            if (
                !empty($userMail->authUser)
                && ($userMail->authUser->status == AuthUsers::STATUS_ACTIVE || $userMail->authUser->status == AuthUsers::STATUS_WAIT)
            ) {

                if (ServiceUsers::confirmRegistrationUser($userMail)) {
                    if (!ServiceUsers::isResetTokenValid($token)) {
                        // Чистим токен
                        if (!empty($userMail->token)) {
                            $userMail->token = '';

                            if (!$userMail->save()) {
                                throw new \Exception('No Save UsersMail token');
                            }
                        }
                    }

                    return $this->redirect('/site/confirm-registration-successful');
                }
            }
        }

        $this->redirect('/site/confirm-registration-reject');
    }

    /**
     * @return string
     */
    public function actionConfirmRegistrationSuccessful()
    {
        return $this->render('confirm-registration-successful');
    }

    /**
     * @return string
     */
    public function actionConfirmRegistrationReject()
    {
        return $this->render('confirm-registration-reject');
    }

    /**
     * @return string
     */
    public function actionRegistrationSuccessful()
    {
        return $this->render('registration-successful');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $form = new PasswordResetRequestForm();

        if ($form->getLoadAndValidate()) {
            if ($form->sendEmail()) {
                \Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                \Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $form,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $form = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($form->load(\Yii::$app->request->post()) && $form->validate() && $form->resetPassword()) {
            \Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $form,
        ]);
    }
}
