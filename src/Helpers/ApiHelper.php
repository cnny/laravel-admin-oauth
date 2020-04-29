<?php

namespace Cann\Admin\OAuth\Helpers;

class ApiHelper
{
    // 发起 HTTP 请求
    public static function guzHttpRequest(
        string $url,
        array $params,
        string $method = 'POST',
        string $format = null,
        array $headers = []
    ) {
        // @see http://guzzle-cn.readthedocs.io/zh_CN/latest
        $http = new \GuzzleHttp\Client(['verify' => false, 'headers' => $headers]);

        $data = [$method == 'POST' ? 'form_params' : 'query' => $params];

        if ($format == 'JSON') {
            $data = ['json' => $params];
        }

        $response = $http->request($method, $url, $data);

        $body = $response->getBody(true);
        $body = json_decode($body, true);

        return $body;
    }
}
