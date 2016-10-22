<?php

namespace Services;

use Common\Constants;
use Libraries\Log;
use Libraries\HttpRequest;
use Libraries\DbConnection;
use Common\Errors;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Capsule\Manager as DB;
use Models\Commodity;

class CommodityManagement {

    private static $maxNum = 10000;

    public static function fetchStock($name) {
    
        DbConnection::connectDb();
    
        try {
            $commodity = Commodity::where('name', strval($name))->firstOrFail();

        } catch (ModelNotFoundException $e) {   // thrown by firstOrFail
            $commodity = false;
            Log::error('model not found: ' . $e->getMessage());
            Errors::throws(Errors::ERR_CODE_COMMODITY_NOT_EXISTED);

        } catch (\Exception $e) {
            Log::error('fail to query db: ' . $e->getMessage());
            Errors::throws(Errors::ERR_CODE_DB_ERROR);
        }

        return $commodity->stock;

    }

    public static function increaseStock($name, $num) {

        if ($num <= 0) {
            return false;
        }

        DbConnection::connectDb();
        DbConnection::beginTransaction();

        try {
            $commodity = Commodity::where('name', strval($name))->firstOrFail();

        } catch (ModelNotFoundException $e) {   // thrown by firstOrFail
            $commodity = false;
            Log::error($e->getMessage());
            DbConnection::rollback();
            Errors::throws(Errors::ERR_CODE_COMMODITY_NOT_EXISTED);

        } catch (\Exception $e) {
            Log::error('fail to query db: ' . $e->getMessage());
            Errors::throws(Errors::ERR_CODE_DB_ERROR);
        }

        if ($commodity->stock >= self::$maxNum - $num) {
            Log::warning('stock already up to limit');
            DbConnection::rollback();
            return false;
        }

        $commodity->stock += $num;
        $commodity->save();

        DbConnection::commit();

        return true;
    
    }

    public static function decreaseStock($name, $num) {

        if ($num <= 0) {
            return false;
        }

        DbConnection::connectDb();
        DbConnection::beginTransaction();

        try {
            $commodity = Commodity::where('name', strval($name))->firstOrFail();

        } catch (ModelNotFoundException $e) {   // thrown by firstOrFail
            $commodity = false;
            Log::error($e->getMessage());
            DbConnection::rollback();
            Errors::throws(Errors::ERR_CODE_COMMODITY_NOT_EXISTED);

        } catch (\Exception $e) {
            Log::error('fail to query db: ' . $e->getMessage());
            Errors::throws(Errors::ERR_CODE_DB_ERROR);
        }

        if ($commodity->stock <= $num) {
            Log::warning('stock no enough');
            DbConnection::rollback();
            return false;
        }

        $commodity->stock -= $num;
        $commodity->save();

        DbConnection::commit();

        return true;
    
    }

}
