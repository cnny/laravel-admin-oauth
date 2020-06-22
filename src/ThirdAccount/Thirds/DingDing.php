<?php

namespace Cann\Admin\OAuth\ThirdAccount\Thirds;

use EasyWeChat\Factory;
use Cann\Admin\OAuth\Helpers\ApiHelper;

class DingDing extends ThirdAbstract
{
    const BASE_URL = 'https://oapi.dingtalk.com';

    protected $redirectUrl;

    public function getPlatform()
    {
        return 'DingDing';
    }

    public function getPlatformChn()
    {
        return '钉钉';
    }

    public function getAuthorizeUrl(array $params)
    {
        $paramsStr = http_build_query([
            'appid'         => $this->config['app_id'],
            'response_type' => 'code',
            'scope'         => 'snsapi_login',
            'state'         => $this->generateState(),
            'redirect_uri'  => $this->redirectUrl,
        ]);

        return self::BASE_URL . '/connect/qrconnect?' . $paramsStr;
    }

    public function getThirdUser(array $params)
    {
        \Validator::make($params, [
            'code' => 'required|string',
        ])->validate();

        $this->validateState($params['state']);

        $timestamp = time() . '000';

        $userInfo = self::request('/sns/getuserinfo_bycode?accessKey=' . $this->config['app_id'] . '&timestamp=' . $timestamp . '&signature=' . $this->buildSign($timestamp), [
            'tmp_auth_code' => $params['code'],
        ], 'POST', 'JSON');

        return [
            'id'   => $userInfo['user_info']['openid'],
            'name' => $userInfo['user_info']['nick'],
        ];
    }

    private function buildSign(string $timestamp)
    {
        $s = hash_hmac('sha256', $timestamp, $this->config['app_secret'], true);

        return base64_encode($s);
    }

    private static function request(string $url, array $params, string $method = 'GET', string $format = null)
    {
        $url = self::BASE_URL . $url;

        $response = ApiHelper::guzHttpRequest($url, $params, $method, $format);

        if (isset($response['errcode']) && $response['errcode']) {
            throw new \Exception($response['errmsg'] . '(errcode:' . $response['errcode'] . ')');
        }

        return $response;
    }
}
