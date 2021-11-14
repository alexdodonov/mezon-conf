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
     * @param string $route
     *            key route in config
     * @param string $defaultValue
     *            default value if the key was not found
     * @return string key value
     */
    public static function getConfigValueAsString(string $route, $defaultValue = ''): string
    {
        $value = static::getValueAsString($route, $defaultValue);

        return static::expandStringValue($value);
    }

    /**
     * Function returns specified config key
     * If the key does not exists then $defaultValue will be returned
     *
     * @param string $route
     *            key route in config
     * @param ?object $defaultValue
     *            default value if the key was not found
     * @return ?object key value
     */
    public static function getConfigValueAsObject(string $route, ?object $defaultValue = null): ?object
    {
        $value = static::getValueAsObject($route, $defaultValue);

        return $value === null ? null : static::expandObjectValue($value);
    }

    /**
     * Function returns specified config key
     * If the key does not exists then $defaultValue will be returned
     *
     * @param string $route
     *            key route in config
     * @param array $defaultValue
     *            default value if the key was not found
     * @return array key value
     */
    public static function getConfigValueAsArray(string $route, array $defaultValue = []): array
    {
        $value = static::getValueAsArray($route, $defaultValue);

        return static::expandArrayValue($value);
    }

    /**
     * Function sets specified config key with value $value
     *
     * @param string $route
     *            route to key
     * @param mixed $value
     *            value to be set
     */
    public static function setConfigValue(string $route, $value): void
    {
        // TODO remove this method
        if (is_string($value)) {
            static::setConfigStringValue($route, $value);
        } elseif (is_object($value)) {
            static::setConfigObjectValue($route, $value);
        } elseif (is_array($value)) {
            static::setConfigArrayValue($route, $value);
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
        /** @var string $route */
        foreach (array_keys($settings) as $route) {
            static::setConfigValue($route, $settings[$route]);
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
        Conf::$appConfig[$route] = [
            $value
        ];
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
        if (is_array($route)) {
            $route = implode('/', $route);
        }

        return isset(Conf::$appConfig[$route]);
    }

    /**
     * Deleting config value
     *
     * @param string $route
     *            route to key
     * @return bool true if the key was deleted, false otherwise
     */
    public static function deleteConfigValue(string $route): bool
    {
        if (static::configKeyExists($route) === false) {
            return false;
        }

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
     *            DB User login
     * @param string $password
     *            DB User password
     */
    public static function addConnectionToConfig(string $name, string $dsn, string $user, string $password): void
    {
        static::setConfigStringValue($name . '/dsn', $dsn);
        static::setConfigStringValue($name . '/user', $user);
        static::setConfigStringValue($name . '/password', $password);
    }

    /**
     * Method loads config from JSON file
     *
     * @param string $path
     *            path to the config
     */
    public static function loadConfigFromJson(string $path): void
    {
        $configContent = file_get_contents($path);

        /** @var array $config */
        $config = json_decode($configContent, true);

        static::setConfigValues($config);
    }

    /**
     * Method clears config data
     */
    public static function clear(): void
    {
        Conf::$appConfig = [];
    }

    /**
     * Function sets specified config key with value $value
     *
     * @param string $route
     *            route to key
     * @param string $value
     *            value to be set
     */
    public static function setConfigStringValue(string $route, string $value): void
    {
        Conf::$appConfig[$route] = $value;
    }

    /**
     * Function sets specified config key with value $value
     *
     * @param string $route
     *            route to key
     * @param object $value
     *            value to be set
     */
    public static function setConfigObjectValue(string $route, object $value): void
    {
        Conf::$appConfig[$route] = $value;
    }

    /**
     * Function sets specified config key with value $value
     *
     * @param string $route
     *            route to key
     * @param array $value
     *            value to be set
     */
    public static function setConfigArrayValue(string $route, array $value): void
    {
        Conf::$appConfig[$route] = $value;
    }

    /**
     * Method returns config item as string
     *
     * @param string $route
     *            key
     * @param ?object $defaultValue
     * @return ?object value
     */
    public static function getValueAsObject(string $route, ?object $defaultValue = null): ?object
    {
        if (! isset(Conf::$appConfig[$route])) {
            return $defaultValue;
        }

        if (is_object(Conf::$appConfig[$route])) {
            return Conf::$appConfig[$route];
        }

        throw (new \Exception('Value is not an object: ' . $route, - 1));
    }

    /**
     * Method returns config item as string
     *
     * @param string $route
     *            key
     * @param string $defaultValue
     * @return string value
     */
    public static function getValueAsString(string $route, string $defaultValue = ''): string
    {
        if (! isset(Conf::$appConfig[$route])) {
            return $defaultValue;
        }

        if (is_string(Conf::$appConfig[$route])) {
            return Conf::$appConfig[$route];
        }

        throw (new \Exception('Value is not a string: ' . $route, - 1));
    }

    /**
     * Method returns config item as string
     *
     * @param string $route
     *            key
     * @param array $defaultValue
     * @return array value
     */
    public static function getValueAsArray(string $route, array $defaultValue = []): array
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
    public static function expandString($value)
    {
        if (is_string($value)) {
            $value = static::expandStringValue($value);
        } elseif (is_array($value)) {
            $value = static::expandArrayValue($value);
        } elseif (is_object($value)) {
            $value = static::expandObjectValue($value);
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
    public static function expandStringValue(string $value): string
    {
        $value = str_replace(
            [
                Conf::APP_HTTP_PATH_STRING,
                Conf::MEZON_HTTP_PATH_STRING
            ],
            [
                static::getValueAsString(Conf::APP_HTTP_PATH_STRING),
                static::getValueAsString(Conf::MEZON_HTTP_PATH_STRING)
            ],
            $value);

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
    public static function expandArrayValue(array $value): array
    {
        foreach (array_keys($value) as $key) {
            if (is_string($value[$key])) {
                $value[$key] = static::expandStringValue($value[$key]);
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
    public static function expandObjectValue(object $value): object
    {
        /**
         *
         * @var mixed $v
         * @var string $k
         */
        foreach ($value as $k => $v) {
            if (is_string($v)) {
                $value->$k = static::expandStringValue($v);
            }
        }

        return $value;
    }
}
