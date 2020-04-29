<?php

namespace Cann\Admin\OAuth\ThirdAccount\Thirds;

interface ThirdInterface
{
    public function getThirdUser(array $params);

    public function bindUserByThird($user, array $thirdUser);
}
