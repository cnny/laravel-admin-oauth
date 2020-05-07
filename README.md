<p align="center">Laravel-Admin-OAuth</p>

<p align="center">
  laravel-admin-oauth 是 <a href="https://laravel-admin.org/">laravel-admin</a> 的 OAuth2.0 登录扩展， 目前支持企业微信OAuth登录，支持扩展其他授权方
  <img src="https://blog-1252314417.cos.ap-shanghai.myqcloud.com/%E5%B1%8F%E5%B9%95%E5%BF%AB%E7%85%A7%202020-05-07%20%E4%B8%8B%E5%8D%8812.16.42.png">
  <img src="https://blog-1252314417.cos.ap-shanghai.myqcloud.com/%E5%B1%8F%E5%B9%95%E5%BF%AB%E7%85%A7%202020-05-07%20%E4%B8%8B%E5%8D%8812.16.51.png">
</p>


### 依赖
------------
 - laravel-admin >= 1.7
 
 
### 安装
------------
```
composer require cann/laravel-admin-oauth
```

### 发布资源

```
php artisan vendor:publish --provider="Cann\Admin\OAuth\ServiceProvider"
```

### 配置

path: `config/admin-oauth.php`

```
    'controller' => Cann\Admin\OAuth\Controllers\AuthController::class,

    // 是否允许账号密码登录
    'allowed_password_login' => true,

    // 当第三方登录未匹配到本地账号时，是否允许自动创建本地账号
    'allowed_auto_create_account_by_third' => false,

    // 启用的第三方登录
    'enabled_thirds' => [
        'WorkWechat',
    ],

    // 第三方登录秘钥
    'services' => [
        'work_wechat' => [
            'corp_id'  => env('WECHAT_WORK_CORP_ID', ''),
            'agent_id' => env('WECHAT_WORK_AGENT_ID', ''),
            'secret'   => env('WECHAT_WORK_AGENT_SECRET', ''),
        ]
    ]
```

### 扩展授权方

继承 `Cann\Admin\OAuth\ThirdAccount\Thirds\ThirdAbstract`, 实现 `getAuthorizeUrl` & `getThirdUser`。 然后在 `AppServerProvider` 中注册：

```
    \Cann\Admin\OAuth\ServiceProvider::extend(\App\Extensions\OAuth\WechatMp::class, 'WechatMp', '微信公众号');
```

修改 `config/admin-oauth.php` 即可:

```
    // 启用的第三方登录
    'enabled_thirds' => [
        'WechatMp',
        'WorkWechat',
    ],

    // 第三方登录秘钥
    'services' => [
        'work_wechat' => [
            'corp_id'  => env('WECHAT_WORK_CORP_ID'),
            'agent_id' => env('WECHAT_WORK_AGENT_ID'),
            'secret'   => env('WECHAT_WORK_AGENT_SECRET'),
        ],
        'wechat_mp' => [
            'app_id'  => env('WECHAT_OFFICIAL_ACCOUNT_APPID', ''),   // AppID
            'secret'  => env('WECHAT_OFFICIAL_ACCOUNT_SECRET', ''),   // AppSecret
            'token'   => env('WECHAT_OFFICIAL_ACCOUNT_TOKEN', ''),    // Token
            'aes_key' => env('WECHAT_OFFICIAL_ACCOUNT_AES_KEY', ''),  // EncodingAESKey
        ],
    ],
```
