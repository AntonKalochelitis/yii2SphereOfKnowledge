<?php

return [
    '' => 'site/index',
    '/sign-in' => 'site/sign-in',
    '/sign-out' => 'site/sign-out',
    '/sign-up' => 'site/sign-up',

    '/contact' => 'site/contact',
    '/about' => 'site/about',
    '/request-password-reset' => 'site/request-password-reset',
    '/reset-password' => 'site/reset-password',

    '/confirm-registration/<token:\d+>' => 'site/confirm-registration',
    '/confirm-registration-successful' => 'site/confirm-registration-successful',
    '/confirm-registration-reject' => 'site/confirm-registration-reject',
    '/successful-registration' => 'site/successful-registration',

    '/messages' => 'messages/index',
    '/messages/with/friend/<id:\w+>' => 'messages/with-friend',
    '/messages-all' => 'messages/show-all',
//    '/messages-send-to'             => 'messages/show-send-to',
//    '/messages-show-all-to-me'      => 'messages/messages-show-all-to-user',
//    '/messages-ajax-list-to-user'   => 'messages/messages-ajax-list-to-user',

    '/notifications' => 'notifications/index',
    '/notifications-all' => 'notifications/show-all',

    '/tasks' => 'tasks/index',

    '/friends' => 'friends/index',

    '/library' => 'library/index',

    '/profile' => 'profile/index',
    '/profile/update' => 'profile/update',
    '/profile/password-change' => 'profile/password-change',

];