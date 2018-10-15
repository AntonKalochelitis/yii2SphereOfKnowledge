<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'Sphere of Knowledge',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'core\repositories\AuthUsers',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'i18n' => [
            'translations' => [
                'Users' => [
                    'class'     => 'yii\i18n\PhpMessageSource',
                    'basePath'  => '@frontend/translations',
                ],
                'Profile' => [
                    'class'     => 'yii\i18n\PhpMessageSource',
                    'basePath'  => '@frontend/translations',
                ],
                'PasswordChange' => [
                    'class'     => 'yii\i18n\PhpMessageSource',
                    'basePath'  => '@frontend/translations',
                ],
                'ProfileUpdate' => [
                    'class'     => 'yii\i18n\PhpMessageSource',
                    'basePath'  => '@frontend/translations',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . DIRECTORY_SEPARATOR . 'rules-routes.php'),
        ],
    ],
    'params' => $params,
];
