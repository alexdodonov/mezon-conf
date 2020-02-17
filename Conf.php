<?php
namespace Mezon\Conf;

/**
 * Configuration routines
 *
 * @package Mezon
 * @subpackage Conf
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/07)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Config data
 *
 * @author Dodonov A.A.
 */
class Conf
{

    /**
     * Built-in constants
     */
    public const APP_HTTP_PATH_STRING = '@app-http-path';

    /**
     * Built-in constants
     */
    public const MEZON_HTTP_PATH_STRING = '@mezon-http-path';

    /**
     * Config data
     *
     * @var array
     */
    public static $appConfig = [];

    /**
     * Function returns specified config key
     * If the key does not exists then $defaultValue will be returned
     *
     * @param string $route
     *            Key route in config
     * @param mixed $defaultValue
     *            Default value if the key was not found
     * @return mixed Key value
     */
    public static function getConfigValue($route, $defaultValue = false)
    {
        return \Mezon\Conf\getConfigValue($route, $defaultValue);
    }

    /**
     * Function sets specified config key with value $value
     *
     * @param array $route
     *            Route to key
     * @param mixed $value
     *            Value to be set
     */
    public static function setConfigValue($route, $value)
    {
        \Mezon\Conf\setConfigValue($route, $value);
    }

    /**
     * Function adds specified value $value into array with path $route in the config
     *
     * @param string $route
     *            Route to key
     * @param mixed $value
     *            Value to be set
     */
    public static function addConfigValue(string $route, $value)
    {
        \Mezon\Conf\addConfigValue($route, $value);
    }

    /**
     * Validating key existance
     *
     * @param mixed $route
     *            Route to key
     * @return bool True if the key exists, false otherwise
     */
    public static function configKeyExists($route): bool
    {
        return \Mezon\Conf\configKeyExists($route);
    }

    /**
     * Deleting config value
     *
     * @param mixed $route
     *            Route to key
     * @return bool True if the key was deleted, false otherwise
     */
    public static function deleteConfigValue($route): bool
    {
        return \Mezon\Conf\deleteConfigValue($route);
    }

    /**
     * Method sets connection details to config
     *
     * @param string $name
     *            Config key
     * @param string $dSN
     *            DSN
     * @param string $user
     *            DB User login
     * @param string $password
     *            DB User password
     */
    public static function addConnectionToConfig(string $name, string $dSN, string $user, string $password)
    {
        \Mezon\Conf\addConnectionToConfig($name, $dSN, $user, $password);
    }

    /**
     * Method expands string
     *
     * @param string $value
     *            value to be expanded;
     * @return mixed Expanded value.
     */
    public static function expandString($value)
    {
        return \Mezon\Conf\expandString($value);
    }
}

/**
 * Method expands string
 *
 * @param string $value
 *            value to be expanded;
 * @return mixed Expanded value.
 */
function expandString($value)
{
    if (is_string($value)) {
        $value = str_replace([
            \Mezon\Conf\Conf::APP_HTTP_PATH_STRING,
            \Mezon\Conf\Conf::MEZON_HTTP_PATH_STRING
        ], [
            @Conf::$appConfig[\Mezon\Conf\Conf::APP_HTTP_PATH_STRING],
            @Conf::$appConfig[\Mezon\Conf\Conf::MEZON_HTTP_PATH_STRING]
        ], $value);
    } elseif (is_array($value)) {
        foreach ($value as $fieldName => $fieldValue) {
            $value[$fieldName] = expandString($fieldValue);
        }
    } elseif (is_object($value)) {
        foreach ($value as $fieldName => $fieldValue) {
            $value->$fieldName = expandString($fieldValue);
        }
    }

    return $value;
}

/**
 * Function returns specified config key
 * If the key does not exists then $defaultValue will be returned
 *
 * @param string $route
 *            Key route in config
 * @param mixed $defaultValue
 *            Default value if the key was not found
 * @return mixed Key value
 */
function getConfigValue($route, $defaultValue = false)
{
    if (is_string($route)) {
        $route = explode('/', $route);
    }

    if (isset(Conf::$appConfig[$route[0]]) === false) {
        return $defaultValue;
    }

    $value = Conf::$appConfig[$route[0]];

    for ($i = 1; $i < count($route); $i ++) {
        if (isset($value[$route[$i]]) === false) {
            return $defaultValue;
        }

        $value = $value[$route[$i]];
    }

    return expandString($value);
}

/**
 * Setting config value
 *
 * @param array $config
 *            Config values
 * @param array $route
 *            Route to key
 * @param mixed $value
 *            Value to be set
 */
function _setConfigValueRec(array &$config, array $route, $value)
{
    if (isset($config[$route[0]]) === false) {
        $config[$route[0]] = [];
    }

    if (count($route) > 1) {
        _setConfigValueRec($config[$route[0]], array_slice($route, 1), $value);
    } elseif (count($route) == 1) {
        $config[$route[0]] = $value;
    }
}

/**
 * Function sets specified config key with value $value
 *
 * @param array $route
 *            Route to key
 * @param mixed $value
 *            Value to be set
 */
function setConfigValue($route, $value)
{
    $route = explode('/', $route);

    if (count($route) > 1) {
        _setConfigValueRec(Conf::$appConfig, $route, $value);
    } else {
        Conf::$appConfig[$route[0]] = $value;
    }
}

/**
 * Additing value
 *
 * @param array $config
 *            Config values
 * @param array $route
 *            Route to key
 * @param mixed $value
 *            Value to be set
 */
function _addConfigValueRec(array &$config, array $route, $value)
{
    if (isset($config[$route[0]]) === false) {
        $config[$route[0]] = [];
    }

    if (count($route) > 1) {
        _addConfigValueRec($config[$route[0]], array_slice($route, 1), $value);
    } elseif (count($route) == 1) {
        $config[$route[0]][] = $value;
    }
}

/**
 * Function adds specified value $value into array with path $route in the config
 *
 * @param string $route
 *            Route to key
 * @param mixed $value
 *            Value to be set
 */
function addConfigValue(string $route, $value)
{
    $route = explode('/', $route);

    if (count($route) > 1) {
        _addConfigValueRec(Conf::$appConfig, $route, $value);
    } else {
        Conf::$appConfig[$route[0]] = [
            $value
        ];
    }
}

/**
 * Validating key existance
 *
 * @param mixed $route
 *            Route to key
 * @return bool True if the key exists, false otherwise
 */
function configKeyExists($route): bool
{
    if (is_string($route)) {
        $route = explode('/', $route);
    }

    // validating route
    $value = Conf::$appConfig[$route[0]];

    for ($i = 1; $i < count($route); $i ++) {
        if (isset($value[$route[$i]]) === false) {
            return false;
        }

        $value = $value[$route[$i]];
    }

    return true;
}

/**
 * Deleting config element
 *
 * @param array $routeParts
 *            Route parts
 * @param array $configPart
 *            Config part
 */
function _deleteConfig(array $routeParts, array &$configPart)
{
    if (count($routeParts) == 1) {
        // don't go deeper and delete the found staff
        unset($configPart[$routeParts[0]]);
    } else {
        // go deeper
        _deleteConfig(array_splice($routeParts, 1), $configPart[$routeParts[0]]);

        if (count($configPart[$routeParts[0]]) == 0) {
            // remove empty parents
            unset($configPart[$routeParts[0]]);
        }
    }
}

/**
 * Deleting config value
 *
 * @param mixed $route
 *            Route to key
 * @return bool True if the key was deleted, false otherwise
 */
function deleteConfigValue($route): bool
{
    if (is_string($route)) {
        $route = explode('/', $route);
    }

    if (configKeyExists($route) === false) {
        return false;
    }

    // route exists, so delete it
    _deleteConfig($route, Conf::$appConfig);

    return true;
}

/**
 * Method sets connection details to config
 *
 * @param string $name
 *            Config key
 * @param string $dSN
 *            DSN
 * @param string $user
 *            DB User login
 * @param string $password
 *            DB User password
 */
function addConnectionToConfig(string $name, string $dSN, string $user, string $password)
{
    setConfigValue($name . '/dsn', $dSN);
    setConfigValue($name . '/user', $user);
    setConfigValue($name . '/password', $password);
}
