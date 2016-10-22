<?php

namespace Libraries;

use Common\Constants;
use Libraries\Log;
use Libraries\Configuration;
use Common\Errors;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as DB;

class HttpRequest {

    public static function send($url, $requestParams) {

        try {
            $httpClient = new \GuzzleHttp\Client();
            $resp = $httpClient->request('POST', $url, array('json' => $requestParams));
            Log::info('http response status code: ' . strval($resp->getStatusCode()));
            Log::info('http response body: ' . strval($resp->getBody()));

        } catch (GuzzleHttp\Exception\ConnectException $e) {
            Log::alert('fail to send request: ' . $e->getMessage());
            return false;

        } catch (\Exception $e) {
            Log::alert('fail to send request: ' . $e->getMessage());
            return false;
        }

        $statusCode = $resp->getStatusCode();
        if (200 !== $statusCode) {
            Log::alert('http status code is ' . $statusCode . ', not 200');
            return false;
        }

        return json_decode(strval($resp->getBody()), true);

    }

}
