<?php
namespace Mezon\Conf;

use Mezon\TemplateEngine;

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
     * @param string|array $route
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
     * @param string $route
     *            Route to key
     * @param mixed $value
     *            Value to be set
     */
    public static function setConfigValue(string $route, $value): void
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
    public static function addConfigValue(string $route, $value): void
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
     *            config key
     * @param string $dsn
     *            DSN
     * @param string $user
     *            DB User login
     * @param string $password
     *            DB User password
     */
    public static function addConnectionToConfig(string $name, string $dsn, string $user, string $password): void
    {
        \Mezon\Conf\addConnectionToConfig($name, $dsn, $user, $password);
    }

    /**
     * Method expands string
     *
     * @param string $value
     *            value to be expanded
     * @return mixed Expanded value
     */
    public static function expandString($value)
    {
        return \Mezon\Conf\expandString($value);
    }

    /**
     * Method returns expanded config string
     *
     * @param mixed $route
     *            route to key
     * @param mixed $defaultValue
     *            default value
     * @return mixed expandend config value
     */
    public static function getExpandedConfigValue($route, $defaultValue = false)
    {
        return expandString(\Mezon\Conf\getConfigValue($route, $defaultValue));
    }
}

/**
 * Method expands string
 *
 * @param object|string|array|mixed $value
 *            value to be expanded
 * @return mixed expanded value
 */
function expandString($value)
{
    if (is_string($value)) {
        $value = str_replace(
            [
                \Mezon\Conf\Conf::APP_HTTP_PATH_STRING,
                \Mezon\Conf\Conf::MEZON_HTTP_PATH_STRING
            ],
            [
                @Conf::$appConfig[\Mezon\Conf\Conf::APP_HTTP_PATH_STRING],
                @Conf::$appConfig[\Mezon\Conf\Conf::MEZON_HTTP_PATH_STRING]
            ],
            $value);

        foreach (Conf::$appConfig as $key => $var) {
            if (is_string($var)) {
                $value = str_replace('{' . $key . '}', $var, $value);
            }
        }
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
 * @param string|array $route
 *            key route in config
 * @param mixed $defaultValue
 *            default value if the key was not found
 * @return mixed Key value
 */
function getConfigValue($route, $defaultValue = false)
{
    if (is_array($route)) {
        $route = implode('/', $route);
    }

    if (isset(Conf::$appConfig[$route]) === false) {
        return $defaultValue;
    }

    $value = Conf::$appConfig[$route];

    return expandString($value);
}

/**
 * Function sets specified config key with value $value
 *
 * @param string $route
 *            route to key
 * @param mixed $value
 *            value to be set
 */
function setConfigValue(string $route, $value): void
{
    Conf::$appConfig[$route] = $value;
}

/**
 * Function adds specified value $value into array with path $route in the config
 *
 * @param string $route
 *            route to key
 * @param mixed $value
 *            value to be set
 */
function addConfigValue(string $route, $value): void
{
    Conf::$appConfig[$route] = [
        $value
    ];
}

/**
 * Validating key existance
 *
 * @param mixed $route
 *            route to key
 * @return bool true if the key exists, false otherwise
 */
function configKeyExists($route): bool
{
    if (is_array($route)) {
        $route = implode('/', $route);
    }

    return isset(Conf::$appConfig[$route]);
}

/**
 * Deleting config value
 *
 * @param mixed $route
 *            route to key
 * @return bool true if the key was deleted, false otherwise
 */
function deleteConfigValue($route): bool
{
    if (is_array($route)) {
        $route = implode('/', $route);
    }

    if (configKeyExists($route) === false) {
        return false;
    }

    // route exists, so delete it
    unset(Conf::$appConfig[$route]);

    return true;
}

/**
 * Method sets connection details to config
 *
 * @param string $name
 *            config key
 * @param string $dsn
 *            DSN
 * @param string $user
 *            DB user login
 * @param string $password
 *            DB user password
 */
function addConnectionToConfig(string $name, string $dsn, string $user, string $password): void
{
    setConfigValue($name . '/dsn', $dsn);
    setConfigValue($name . '/user', $user);
    setConfigValue($name . '/password', $password);
}
