<?php

namespace Common;

use Exception;

class Errors {

    const ERR_CODE_OK                                               = 0;

    /* protocal related error code */
    const ERR_CODE_PARAM_ERROR_INPUT_IS_NOT_JSON_ENCODED_ARRAY      = 10001;
    const ERR_CODE_PARAM_ERROR_INVALID_INTERFACE                    = 10002;
    const ERR_CODE_PARAM_ERROR_INVALID_DATA                         = 10003;
    const ERR_CODE_PARAM_ERROR_INTERFACE_NOT_EXISTED                = 10004;
    const ERR_CODE_PARAM_ERROR_INVALID_INTERFACE_PARAMS             = 10005; 

    /* common system error code */
    const ERR_CODE_DB_ERROR                                         = 10005;
    const ERR_CODE_INIT_LOG_ERROR                                   = 10006;

    /* business related error code */
    const ERR_CODE_COMMODITY_NOT_EXISTED                            = 10020;
    const ERR_CODE_MODITY_COMMODITY_STOCK_FAILED                    = 10021;
    const ERR_CODE_FETCh_COMMODITY_STOCK_FAILED                     = 10022;

    private static $msg = array(
        
        self::ERR_CODE_OK                                           => 'OK',
        self::ERR_CODE_PARAM_ERROR_INPUT_IS_NOT_JSON_ENCODED_ARRAY  => 'input is not json encoded array',
        self::ERR_CODE_PARAM_ERROR_INVALID_INTERFACE                => 'input array does not contains interface',
        self::ERR_CODE_PARAM_ERROR_INVALID_DATA                     => 'input array does not contains data',
        self::ERR_CODE_PARAM_ERROR_INTERFACE_NOT_EXISTED            => 'input interface does not exist',
        self::ERR_CODE_PARAM_ERROR_INVALID_INTERFACE_PARAMS         => 'interface params invalid',

        self::ERR_CODE_DB_ERROR                                     => 'db error',
        self::ERR_CODE_INIT_LOG_ERROR                               => 'log error',

        self::ERR_CODE_COMMODITY_NOT_EXISTED                        => 'commodity does not exist',
        self::ERR_CODE_MODITY_COMMODITY_STOCK_FAILED                => 'modify commodity stock failed',
        self::ERR_CODE_FETCh_COMMODITY_STOCK_FAILED                 => 'fetch commodity stock failed',

    );

    public static function throws($code){

        throw new Exception(self::$msg[$code], $code);

    }

    public static function getMessage($code){

        if (!isset(self::$msg[$code])) {
            return 'system error';
        }

        return self::$msg[$code];

    }

    public static function getErrorStructure($data = array(), $code = self::ERR_CODE_OK){

        return array(
            'returnCode' => $code,
            'returnMsg'  => self::getMessage($code),
            'data'       => $data,
        );

    }

}
