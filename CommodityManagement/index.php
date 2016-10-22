<?php

require_once(dirname(__FILE__) . '/vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Libraries\Log;
use Libraries\HttpRequest;
use Common\Errors;

class Index {

    private static $requestInterface = 'unknown';
    private static $requestData = array();

    private static function fetchInput() {

        $request  = new Request();
        $rawInput = $request->getContent();
        $input    = json_decode($rawInput, true);

        $errCode = Errors::ERR_CODE_OK;
        if (!is_array($input)) {
            $errCode = Errors::ERR_CODE_PARAM_ERROR_INPUT_IS_NOT_JSON_ENCODED_ARRAY;
        } else if (!array_key_exists('interface', $input) || !is_string($input['interface'])) {
            $errCode = Errors::ERR_CODE_PARAM_ERROR_INVALID_INTERFACE;
        } else if (!array_key_exists('data', $input) || !is_array($input['data'])) {
            $errCode = Errors::ERR_CODE_PARAM_ERROR_INVALID_DATA;
        }

        self::$requestInterface = strval($input['interface']);
        self::$requestData = $input['data'];

        $seqId = rand(0, 99999999);

        try {
            Log::init($seqId, self::$requestInterface);
        } catch (\Exception $e) {
            Errors::throws($e->getCode());
        }
        Log::notice('Request: ' . $rawInput);

        if (Errors::ERR_CODE_OK !== $errCode){
            Errors::throws($errCode);
        }

    }

    private static function sendOutput($data, $code = Errors::ERR_CODE_OK) {

        $output = Errors::getErrorStructure($data, $code);

        Log::notice('Response: ' . json_encode($output));
        $response = new JsonResponse();
        $response->setData($output)->send();

    }

    public static function todo() {

        date_default_timezone_set('Asia/Shanghai');

        try {
            self::fetchInput();

            $className = 'Controllers\\' . ucfirst(self::$requestInterface);
            if (false === class_exists($className)){
                Errors::throws(Errors::ERR_CODE_PARAM_ERROR_INTERFACE_NOT_EXISTED);
            }
            $rspData = $className::todo(self::$requestData);

            self::sendOutput($rspData);

        } catch (\Exception $e) {
            if ($e->getCode() !== Errors::ERR_CODE_INIT_LOG_ERROR) {
                Log::error($e);
            }
            self::sendOutput(array(), $e->getCode());
        }

    }

}

Index::todo();
