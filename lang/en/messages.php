<?php

return [
    'account' => [
        'login' => 'Login',
        'step' => 'Step',
        'action' => 'Actions',
        'platform' => 'Platform',
        'platform_select' => 'Select platform',
        'confirm_delete' => 'You confirm deletion of this account',
        'empty' => 'There are no accounts',

        'actions' => [
            'settings' => 'Settings',
            'screenshot' => 'Screenshot',
            'setState' => 'Enable',
            'forceStop' => 'Disable',
            'getNewProxy' => 'Change proxy',
            'Reset' => 'Reset',
            'disablePhoneAuth' => 'Link via QR',
            'enablePhoneAuth' => 'Link via code',
            'getAuthCode' => 'Check code',
            'delete' => 'Delete account'
        ],

        'validation' => [
            'add' => [
                'platform' => 'You must select a platform',
                'login' => 'You must specify a login or phone number'
            ],
        ],

        'placeholders' => [
            'login_or_phone' => 'Your login or phone number',
        ],

        'success' => [
            'action' => 'Operation completed successfully'
        ],

        'errors' => [
            'action' => 'Operation failed',

            'wrong_type' => 'Incorrect data type',
            'required_platform' => 'You must pass the platform'
        ],

        'other' => [
            'webhookTitle' => 'specify on a new line'
        ]
    ],

    'payment' => [
        'inaccessible' => 'Replenishment using this payment system is temporarily unavailable',
        'not_found' => 'Payment system not found',
        'empty_key_or_id' => 'Payment system is temporarily unavailable',

        'description' => 'Balance replenishment №',

        'validation' => [
            'amount' => 'Minimum replenishment amount from 10 rubles and maximum 50,000 rubles',
            'payment_system' => 'The selected payment system was not found or not selected'
        ]
    ],

    'auth' => [
        'wrong_login_or_pass' => 'Wrong login or password',
        'user_not_found' => 'These credentials do not match our records',

        'not_created' => 'Unable to register, please try again later',

        'validation' => [
            'email' => 'email',
            'password' => 'password'
        ]
    ]
];
