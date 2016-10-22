<?php

namespace Controllers;

use Libraries\Log;
use Common\Errors;
use Services\CommodityManagement;

class FetchStock {

    private static function checkParams(array $arrParams) {

        if (!array_key_exists('name', $arrParams) || !is_string($arrParams['name'])) {
            Log::error('invalid name');
            Errors::throws(Errors::ERR_CODE_PARAM_ERROR_INVALID_INTERFACE_PARAMS);
        }

    }

    public static function todo(array $arrParams) {

        Log::debug('Receive params: ' . json_encode($arrParams));

        self::checkParams($arrParams);

        $result = CommodityManagement::fetchStock($arrParams['name']);
        if ($result === false) {
            Log::error('fetch stock failed');
            Errors::throws(Errors::ERR_CODE_FETCh_COMMODITY_STOCK_FAILED);
        }

        return $result;

    }

}
