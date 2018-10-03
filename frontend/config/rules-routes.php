<?php

return [
    ''                          => 'site/index',
    '/login'                    => 'site/login',
    '/logout'                   => 'site/logout',
    '/contact'                  => 'site/contact',
    '/about'                    => 'site/about',
    '/request-password-reset'   => 'site/request-password-reset',
    '/reset-password'           => 'site/reset-password',
    '/signup'                   => 'site/signup',

    '/confirm-registration/<token:\d+>' => 'site/confirm-registration',
    '/confirm-registration-successful'  => 'site/confirm-registration-successful',
    '/confirm-registration-reject'      => 'site/confirm-registration-reject',
    '/successful-registration'          => 'site/successful-registration',

    '/library'                  =>  'library/index',

    '/friends'                  =>  'friends/index',

    '/messages'                 =>  'messages/index',
    '/messages-send-to'         =>  'messages/show-send-to',
    '/messages-all'             =>  'messages/show-all',

    '/notifications'            =>  'notifications/index',

    '/tasks'                    =>  'tasks/index',

    '/friends'                  =>  'friends/index',

    '/wholesaler'               =>  'wholesaler/index',
    '/dealer'                   =>  'dealer/index',

    '/profile'                  =>  'profile/index',
    '/profile/update'           =>  'profile/update',
    '/profile/password-change'  =>  'profile/password-change',

];