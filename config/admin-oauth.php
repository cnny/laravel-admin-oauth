<?php

return [

    'controller' => Cann\Admin\OAuth\Controllers\AuthController::class,

    // 是否允许账号密码登录
    'allowed_password_login' => true,

    // 当第三方登录未匹配到本地账号时，是否允许自动创建本地账号
    'allowed_auto_create_account_by_third' => false,

    // 启用的第三方登录
    'enabled_thirds' => [
        'WorkWechat',
        'DingDing',
    ],

    // 第三方登录秘钥
    'services' => [
        'work_wechat' => [
            'corp_id'  => env('WECHAT_WORK_CORP_ID', ''),
            'agent_id' => env('WECHAT_WORK_AGENT_ID', ''),
            'secret'   => env('WECHAT_WORK_AGENT_SECRET', ''),
        ],
        'ding_ding' => [
            'app_id'     => env('DINGDING_APP_ID', ''),
            'app_secret' => env('DINGDING_APP_SECRET', ''),
        ],
    ],
];
