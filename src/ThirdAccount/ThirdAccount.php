<?php

namespace Cann\Admin\OAuth\ThirdAccount;

use Cann\Admin\OAuth\Models\AdminUserThirdPfBind;

/**
 * 第三方通行证-模型工厂
 *
 * @author Cann
 */

class ThirdAccount
{
    // 所有第三方平台
    const PLATFORMS = [
        'WorkWechat',
    ];

    /**
     * 平台类型定义
     *
     * @var array
     */
    const SOURCES = [
        'WorkWechat' => '企业微信',
    ];

    /**
     * 实例工厂
     *
     * @param string $source
     */
    public static function factory(string $source)
    {
        $source = ucfirst($source);

        if (! $source || ! isset(self::SOURCES[$source])) {
            throw new \Exception('Invalid 3rdAccountSource');
        }

        $className = __NAMESPACE__ . '\\Thirds\\' . str_replace('_', '\\', $source);

        return new $className($source);
    }

    // 解绑绑定
    public static function unbind($user, string $platform)
    {
        if (! in_array($platform, self::PLATFORMS)) {
            throw new \Exception('Invalid 3rdAccountPlatform');
        }

        AdminUserThirdPfBind::where([
            'platform'  => $platform,
            'uid'       => $user->id,
        ])->delete();
    }

    // 解绑所有第三方绑定
    public static function unbindAll($user)
    {
        AdminUserThirdPfBind::where([
            'uid' => $user->id,
        ])->delete();
    }
}
