# Conf [![Build Status](https://travis-ci.com/alexdodonov/mezon-conf.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-conf) [![codecov](https://codecov.io/gh/alexdodonov/mezon-conf/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-conf)

# Configuration
Mezon has it's own routine for working with configs. It can be accesed with a set of functions, wich are described below.

Getting access to the key in config can be done with Conf::getValue($route, $defaultValue = false) function. It returns config value with route $route and return $defaultValue if this key was not found. For example:

```PHP
$value = Conf::getValue('res/images/favicon', 'http://yoursite.com/res/images/favicon.ico');
```

Setting values for the config key can be done by calling Conf::setConfigValue($route, $value) or Conf::addConfigValue($route, $value) function. The main difference between these two functions is that the first one sets scalar key, and the second one adds element to the array in config. Here is small example:

```PHP
Conf::setConfigValue('res/value', 'Value!');
var_dump(Conf::getValue('res/value')); // displays 'Value!' string

Conf::addConfigValue('res/value', 'Value 1!');
Conf::addConfigValue('res/value', 'Value 2!');
var_dump(Conf::getValue('res/value')); // displays array( [0] => 'Value 1!' , [1] => 'Value 2!' )
```

You also can use typed versions of these methods:

```php
Conf::getValueAsArray(...);
Conf::getValueAsObject(...);
Conf::getValueAsString(...);

Conf::setConfigArrayValue(...);
Conf::setConfigObjectValue(...);
Conf::setConfigStringValue(...);
```

You can set multyple values to the config:

```php
// here $settings is an associative array
Conf::setConfigValues(array $settings);
```

Or you can read config from JSON:

```php
Conf::loadConfigFromJson(string $pathToConfig);
```

If you are not shure that the key exists, then you can check it:

```PHP
Conf::setConfigValue('res/value', 'Value!');

var_dump(Conf::configKeyExists('res')); // true
var_dump(Conf::configKeyExists('res2')); // false
var_dump(Conf::configKeyExists('res/value')); // true
```

You can also able to delete config key

```PHP
Conf::setConfigValue('res/value', 'Value!');
Conf::deleteConfigValue('res/value');
var_dump(Conf::configKeyExists('res/value')); // false
var_dump(Conf::configKeyExists('res')); // also false
```

Or clear the entire config:

```php
Conf::clear();
```

That's all you need to know about config read/write.