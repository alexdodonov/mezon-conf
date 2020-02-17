<?php

class ConfUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing setup of the existing key.
     * It's value must be overwritten.
     */
    public function testSetExistingKey()
    {
        $value = \Mezon\Conf\Conf::getConfigValue([
            '@app-http-path'
        ]);

        $this->assertEquals(false, $value, 'Invalid @app-http-path value');

        \Mezon\Conf\Conf::setConfigValue('@app-http-path', 'set-value');

        $value = \Mezon\Conf\Conf::getConfigValue([
            '@app-http-path'
        ]);

        $this->assertEquals('set-value', $value, 'Invalid @app-http-path value');
    }

    /**
     * Testing setup of the unexisting key.
     * It's value must be overwritten.
     */
    public function testSetUnexistingKey()
    {
        $value = \Mezon\Conf\Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertEquals(false, $value);

        \Mezon\Conf\Conf::setConfigValue('unexisting-key', 'set-value');

        $value = \Mezon\Conf\Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertEquals('set-value', $value);
    }

    /**
     * Testing setup of the unexisting key with complex route.
     * It's value must be overwritten.
     */
    public function testSetComplexUnexistingKey()
    {
        $value = \Mezon\Conf\Conf::getConfigValue([
            'res',
            'images',
            'unexisting-key'
        ]);

        $this->assertEquals(false, $value, 'Invalid res/images/unexisting-key processing');

        $value = \Mezon\Conf\Conf::getConfigValue([
            'res',
            'images',
            'unexisting-key'
        ], 'default');

        $this->assertEquals('default', $value, 'Invalid res/images/unexisting-key processing');

        \Mezon\Conf\Conf::setConfigValue('res/images/unexisting-key', 'set-value');

        $value = \Mezon\Conf\Conf::getConfigValue([
            'res',
            'images',
            'unexisting-key'
        ]);

        $this->assertEquals('set-value', $value, 'Invalid res/images/unexisting-key value');
    }

    /**
     * Testing setup of the existing array.
     */
    public function testAddComplexExistingArray()
    {
        $value = \Mezon\Conf\Conf::getConfigValue([
            'res',
            'css'
        ]);

        $this->assertStringContainsString('', $value, 'Invalid css files list');

        \Mezon\Conf\Conf::addConfigValue('res/css', 'set-value');

        $value = \Mezon\Conf\Conf::getConfigValue([
            'res',
            'css'
        ]);

        $this->assertContains('set-value', $value, 'Invalid css files list');
    }

    /**
     * Testing setup of the unexisting array.
     */
    public function testAddComplexUnexistingArray()
    {
        \Mezon\Conf\Conf::deleteConfigValue([
            'unexisting-key'
        ]);

        $value = \Mezon\Conf\Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertEquals(false, $value);

        \Mezon\Conf\Conf::addConfigValue('unexisting-key', 'set-value');

        $value = \Mezon\Conf\Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertContains('set-value', $value);
    }

    /**
     * Testing setup of the unexisting array with simple route.
     */
    public function testAddUnexistingArray()
    {
        \Mezon\Conf\Conf::deleteConfigValue([
            'unexisting-key'
        ]);

        $value = \Mezon\Conf\Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertEquals(false, $value);

        \Mezon\Conf\Conf::addConfigValue('unexisting-key', 'set-value');

        $value = \Mezon\Conf\Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertContains('set-value', $value);
    }

    /**
     * Testing setup of the existing array with simple route.
     */
    public function testAddExistingArray()
    {
        \Mezon\Conf\Conf::addConfigValue('unexisting-key', 'set-value-1');
        \Mezon\Conf\Conf::addConfigValue('unexisting-key', 'set-value-2');

        $value = \Mezon\Conf\Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertContains('set-value-2', $value);
    }

    /**
     * Testing setup of the existing array with simple route.
     */
    public function testComplexStringRoutes()
    {
        \Mezon\Conf\Conf::setConfigValue('f1/f2/unexisting-key', 'set-value-1');

        $value = \Mezon\Conf\Conf::getConfigValue('f1/f2/unexisting-key');

        $this->assertEquals('set-value-1', $value);
    }

    /**
     * Deleting simple key.
     */
    public function testDeleteFirstValue()
    {
        \Mezon\Conf\Conf::setConfigValue('key-1', 'value');

        $value = \Mezon\Conf\Conf::getConfigValue('key-1');

        $this->assertEquals('value', $value, 'Invalid setting value');

        \Mezon\Conf\Conf::deleteConfigValue('key-1');

        $value = \Mezon\Conf\Conf::getConfigValue('key-1', false);

        $this->assertEquals(false, $value);
    }

    /**
     * Deleting deep key.
     */
    public function testDeleteNextValue()
    {
        \Mezon\Conf\Conf::setConfigValue('key-2/key-3', 'value');

        $value = \Mezon\Conf\Conf::getConfigValue('key-2/key-3');

        $this->assertEquals('value', $value, 'Invalid setting value');

        \Mezon\Conf\Conf::deleteConfigValue('key-2/key-3');

        $value = \Mezon\Conf\Conf::getConfigValue('key-2/key-3', false);

        $this->assertEquals(false, $value);
    }

    /**
     * Deleting empty keys.
     */
    public function testDeleteEmptyKeys()
    {
        \Mezon\Conf\Conf::setConfigValue('key-4/key-5', 'value');

        \Mezon\Conf\Conf::deleteConfigValue('key-4/key-5');

        $value = \Mezon\Conf\Conf::getConfigValue('key-4', false);

        $this->assertEquals(false, $value);
    }

    /**
     * No deleting not empty keys.
     */
    public function testNoDeleteNotEmptyKeys()
    {
        \Mezon\Conf\Conf::setConfigValue('key-6/key-7', 'value');
        \Mezon\Conf\Conf::setConfigValue('key-6/key-8', 'value');

        \Mezon\Conf\Conf::deleteConfigValue('key-6/key-7');

        $value = \Mezon\Conf\Conf::getConfigValue('key-6', false);

        $this->assertEquals(true, is_array($value), 'Key was deleted');

        $value = \Mezon\Conf\Conf::getConfigValue('key-6/key-8', false);

        $this->assertEquals('value', $value, 'Key was deleted');
    }

    /**
     * Testing delete results.
     */
    public function testDeleteResult()
    {
        \Mezon\Conf\Conf::setConfigValue('key-9/key-10', 'value');

        // deleting unexisting value
        $result = \Mezon\Conf\Conf::deleteConfigValue('key-9/key-unexisting');

        $this->assertEquals(false, $result, 'Invalid deleting result');

        // deleting existing value
        $result = \Mezon\Conf\Conf::deleteConfigValue('key-9/key-10');

        $this->assertEquals(true, $result, 'Invalid deleting result');
    }

    /**
     * Testing fas BD setup.
     */
    public function testFastDbSetup()
    {
        \Mezon\Conf\Conf::addConnectionToConfig('connection', 'dsn', 'user', 'password');

        $value = \Mezon\Conf\Conf::getConfigValue('connection/dsn', false);
        $this->assertEquals('dsn', $value, 'Key connection/dsn was not found');

        $value = \Mezon\Conf\Conf::getConfigValue('connection/user', false);
        $this->assertEquals('user', $value, 'Key connection/user was not found');

        $value = \Mezon\Conf\Conf::getConfigValue('connection/password', false);
        $this->assertEquals('password', $value, 'Key connection/password was not found');
    }
}
