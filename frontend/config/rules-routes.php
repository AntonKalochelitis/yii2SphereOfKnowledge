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

    '/messages'                 =>  'messages/index',
    '/messages-send-to'         =>  'messages/show-send-to',
    '/messages-all'             =>  'messages/show-all',
    '/messages-show-all-to-me'  =>  'messages/messages-show-all-to-user',

    '/notifications'            =>  'notifications/index',

    '/tasks'                    =>  'tasks/index',

    '/friends'                  =>  'friends/index',

    '/library'                  =>  'library/index',

    '/profile'                  =>  'profile/index',
    '/profile/update'           =>  'profile/update',
    '/profile/password-change'  =>  'profile/password-change',

];