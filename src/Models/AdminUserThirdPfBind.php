<?php

namespace Cann\Admin\OAuth\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserThirdPfBind extends Model
{
    protected $table = 'admin_users_third_pf_bind';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(config('admin.database.users_model'), 'user_id');
    }

    public static function getUserByThird(string $platform, string $thirdUid)
    {
        $bindRelation = self::where([
            'platform'      => $platform,
            'third_user_id' => $thirdUid,
        ])->first();

        return $bindRelation ? $bindRelation->user : null;
    }
}
