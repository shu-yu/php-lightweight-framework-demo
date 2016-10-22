<?php

namespace Controllers;

use Libraries\Log;
use Common\Errors;
use Services\CommodityManagement;

class DecreaseStock {

    private static function checkParams(array $arrParams) {

        if (!array_key_exists('name', $arrParams) || !is_string($arrParams['name'])) {
            Log::error('invalid name');
            Errors::throws(Errors::ERR_CODE_PARAM_ERROR_INVALID_INTERFACE_PARAMS);
        }

        if (!array_key_exists('num', $arrParams) || !is_int($arrParams['num'])) {
            Log::error('invalid num');
            Errors::throws(Errors::ERR_CODE_PARAM_ERROR_INVALID_INTERFACE_PARAMS);
        }

    }

    public static function todo(array $arrParams) {

        Log::debug('Receive params: ' . json_encode($arrParams));

        self::checkParams($arrParams);

        $result = CommodityManagement::decreaseStock($arrParams['name'], $arrParams['num']);
        if ($result === false) {
            Log::error('increase stock failed');
            Errors::throws(Errors::ERR_CODE_MODITY_COMMODITY_STOCK_FAILED);
        }

        return array();

    }

}
