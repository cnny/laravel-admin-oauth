<?php

namespace Cann\Admin\OAuth\ThirdAccount\Thirds;

use EasyWeChat\Factory;
use App\Business\UserBusiness;
use Cann\Admin\OAuth\Helpers\ApiHelper;

class WorkWechat extends ThirdAbstract
{
    const AUTHORIZE_URL = 'https://open.work.weixin.qq.com/wwopen/sso/qrConnect';

    const BASE_URL = 'https://qyapi.weixin.qq.com';

    protected $redirectUrl;

    public function getPlatform()
    {
        return 'WorkWechat';
    }

    public function getPlatformChn()
    {
        return '企业微信';
    }

    public function getAuthorizeUrl(array $params)
    {
        $paramsStr = http_build_query([
            'appid'        => $this->config['corp_id'],
            'agentid'      => $this->config['agent_id'],
            'redirect_uri' => $this->redirectUrl,
            'state'        => \Str::random(16),
        ]);

        return self::AUTHORIZE_URL . '?' . $paramsStr;
    }

    public function getThirdUser(array $params)
    {
        \Validator::make($params, [
            'code' => 'required|string',
        ])->validate();

        $workService = Factory::work($this->config);

        $accessToken = $workService->access_token->getToken();

        $params += [
            'access_token' => $accessToken['access_token'],
        ];

        $session = self::getSession($params);

        return [
            'id'   => $session['UserId'],
            'name' => $session['UserId'],
        ];
    }

    private static function getSession(array $params)
    {
        $params = [
            'code'         => $params['code'],
            'access_token' => $params['access_token'],
        ];

        return self::request('/cgi-bin/user/getuserinfo', $params);
    }

    private static function request(string $url, array $params, string $method = 'GET')
    {
        $url = self::BASE_URL . $url;

        $response = ApiHelper::guzHttpRequest($url, $params, 'GET');

        if (isset($response['errcode']) && $response['errcode']) {
            throw new \Exception($response['errmsg'] . '(errcode:' . $response['errcode'] . ')');
        }

        return $response;
    }
}
