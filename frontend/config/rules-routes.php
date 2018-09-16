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

    '/profile'                  => 'profile/index',
];