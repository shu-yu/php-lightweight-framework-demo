<?php

/*
 * Author: fiendzhu
 */

namespace Libraries;

use Libraries\Log;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Libraries\Validator as Validator;

class Configuration{

    const DIRECTORY = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Configs';

    private static $configs = array();

    public static function get($confName){

        if (false === array_key_exists($confName, self::$configs)){
            $filePath = self::DIRECTORY . DIRECTORY_SEPARATOR . $confName;
            $fileRealPath = realpath($filePath);

            if (false === $fileRealPath){
                Log::alert('config file not found: ' . $fileRealPath);
                return false;
            }
            if (false === is_file($fileRealPath)){
                Log::alert('config file is not normal file: ' . $fileRealPath);
                return false;
            }
            if (false === is_readable($fileRealPath)){
                Log::alert('fail to read config file: ' . $fileRealPath);
                return false;
            }

            try {
                Log::debug('read config file from disk: ' . $fileRealPath);
                $configuration = Yaml::parse(file_get_contents($fileRealPath));
                if (false === isset($configuration)){
                    Log::alert('config file is empty: ' . $fileRealPath);
                    return false;
                }
            } catch (ParseException $e){
                Log::alert('fail to parse config file: ' . $fileRealPath);
                return false;
            } catch (Exception $e){
                Log::alert($e->getMessage());
                return false;
            }

            self::$configs[$confName] = $configuration;

        } else {
            Log::debug('config file already in cache: ' . $fileRealPath);
        }

        return self::$configs[$confName];

    }

}
