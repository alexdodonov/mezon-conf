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
     * @var array<string, string|object|array|mixed>
     */
    public static $appConfig = [];

    /**
     * Function returns specified config key
     * If the key does not exists then $defaultValue will be returned
     *
     * @param string|string[] $route
     *            key route in config
     * @param string $defaultValue
     *            default value if the key was not found
     * @return string key value
     */
    public static function getConfigValueAsString($route, string $defaultValue = ''): string
    {
        return getConfigValueAsString($route, $defaultValue);
    }

    /**
     * Function returns specified config key
     * If the key does not exists then $defaultValue will be returned
     *
     * @param string|string[] $route
     *            key route in config
     * @param array $defaultValue
     *            default value if the key was not found
     * @return array key value
     */
    public static function getConfigValueAsArray($route, array $defaultValue = []): array
    {
        if (is_array($route)) {
            $route = implode('/', $route);
        }

        if (isset(Conf::$appConfig[$route]) === false) {
            return $defaultValue;
        }

        $value = getValueAsArray($route);

        return expandArrayValue($value);
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
        if (is_string($value)) {
            setConfigStringValue($route, $value);
        } else {
            throw (new \Exception('Unsupported value type', - 1));
        }
    }

    /**
     * Function sets specified $settings
     *
     * @param
     *            array<string, string|object|array|mixed> $settings
     *            pair of routes and values
     */
    public static function setConfigValues(array $settings): void
    {
        foreach (array_keys($settings) as $route) {
            static::setConfigValue((string) $route, $settings[$route]);
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
    public static function addConfigValue(string $route, $value): void
    {
        addConfigValue($route, $value);
    }

    /**
     * Validating key existance
     *
     * @param string|string[] $route
     *            route to key
     * @return bool true if the key exists, false otherwise
     */
    public static function configKeyExists($route): bool
    {
        return configKeyExists($route);
    }

    /**
     * Deleting config value
     *
     * @param string|string[] $route
     *            route to key
     * @return bool true if the key was deleted, false otherwise
     */
    public static function deleteConfigValue($route): bool
    {
        return deleteConfigValue($route);
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
        addConnectionToConfig($name, $dsn, $user, $password);
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
        return expandString($value);
    }

    /**
     * Method loads config from JSON file
     *
     * @param string $path
     *            path to the config
     */
    public static function loadConfigFromJson(string $path): void
    {
        static::setConfigValues((array) json_decode(file_get_contents($path), true));
    }

    /**
     * Method clears config data
     */
    public static function clear(): void
    {
        Conf::$appConfig = [];
    }
}

/**
 * Method returns config item as string
 *
 * @param string $route
 *            key
 * @param string $defaultValue
 * @return string value
 */
function getValueAsString(string $route, string $defaultValue = ''): string
{
    if (! isset(Conf::$appConfig[$route])) {
        return $defaultValue;
    }

    if (is_string(Conf::$appConfig[$route])) {
        return Conf::$appConfig[$route];
    }

    throw (new \Exception('Value is not a string: ' . $route, - 1));
}

// TODO remove function and use only methods

/**
 * Method returns config item as string
 *
 * @param string $route
 *            key
 * @param array $defaultValue
 * @return array value
 */
function getValueAsArray(string $route, array $defaultValue = []): array
{
    if (! isset(Conf::$appConfig[$route])) {
        return $defaultValue;
    }

    if (is_array(Conf::$appConfig[$route])) {
        return Conf::$appConfig[$route];
    }

    throw (new \Exception('Value is not an array: ' . $route, - 1));
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
        $value = expandStringValue($value);
    } elseif (is_array($value)) {
        $value = expandArrayValue($value);
    } elseif (is_object($value)) {
        $value = expandObjectValue($value);
    }

    return $value;
}

/**
 * Method expands string
 *
 * @param string $value
 *            value to be expanded
 * @return string expanded value
 */
function expandStringValue(string $value): string
{
    $value = str_replace([
        Conf::APP_HTTP_PATH_STRING,
        Conf::MEZON_HTTP_PATH_STRING
    ], [
        getValueAsString(Conf::APP_HTTP_PATH_STRING),
        getValueAsString(Conf::MEZON_HTTP_PATH_STRING)
    ], $value);

    foreach (array_keys(Conf::$appConfig) as $key) {
        if (is_string(Conf::$appConfig[$key])) {
            $value = str_replace('{' . $key . '}', Conf::$appConfig[$key], $value);
        }
    }

    return $value;
}

/**
 * Method expands arrays
 *
 * @param array<mixed> $value
 *            value to be expanded
 * @return array<mixed> expanded value
 */
function expandArrayValue(array $value): array
{
    foreach (array_keys($value) as $key) {
        if (is_string($value[$key])) {
            $value[$key] = expandStringValue($value[$key]);
        }
    }

    return $value;
}

/**
 * Method expands objects
 *
 * @param object $value
 *            value to be expanded
 * @return object expanded value
 */
function expandObjectValue(object $value): object
{
    foreach (array_keys((array) $value) as $key) {
        if (is_string($value->$key)) {
            $value->$key = expandStringValue((string) $value->$key);
        }
    }

    return $value;
}

/**
 * Function returns specified config key
 * If the key does not exists then $defaultValue will be returned
 *
 * @param string|string[] $route
 *            key route in config
 * @param string $defaultValue
 *            default value if the key was not found
 * @return string key value
 */
function getConfigValueAsString($route, $defaultValue = ''): string
{
    if (is_array($route)) {
        $route = implode('/', $route);
    }

    if (isset(Conf::$appConfig[$route]) === false) {
        return $defaultValue;
    }

    $value = getValueAsString($route);

    return expandStringValue($value);
}

/**
 * Function sets specified config key with value $value
 *
 * @param string $route
 *            route to key
 * @param string $value
 *            value to be set
 */
function setConfigStringValue(string $route, string $value): void
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
 * @param string[]|string $route
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
 * @param string[]|string $route
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
    setConfigStringValue($name . '/dsn', $dsn);
    setConfigStringValue($name . '/user', $user);
    setConfigStringValue($name . '/password', $password);
}
