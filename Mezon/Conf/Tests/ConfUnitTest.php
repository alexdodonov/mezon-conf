<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ConfUnitTest extends TestCase
{

    /**
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {
        Conf::clear();
    }

    /**
     * Testing setup of the existing key
     * It's value must be overwritten
     */
    public function testSetExistingKey(): void
    {
        $value = Conf::getConfigValue([
            '@app-http-path'
        ]);

        $this->assertEquals(false, $value, 'Invalid @app-http-path value');

        Conf::setConfigValue('@app-http-path', 'set-value');

        $value = Conf::getConfigValue([
            '@app-http-path'
        ]);

        $this->assertEquals('set-value', $value, 'Invalid @app-http-path value');
    }

    /**
     * Testing setup of the unexisting key
     * It's value must be overwritten
     */
    public function testSetUnexistingKey(): void
    {
        $this->assertFalse(Conf::getConfigValue([
            'unexisting-key'
        ]));

        Conf::setConfigValue('unexisting-key', 'set-value');

        $this->assertEquals('set-value', Conf::getConfigValue([
            'unexisting-key'
        ]));
    }

    /**
     * Testing setup of the unexisting key with complex route
     * It's value must be overwritten
     */
    public function testSetComplexUnexistingKey(): void
    {
        $value = Conf::getConfigValue([
            'res',
            'images',
            'unexisting-key'
        ]);

        $this->assertEquals(false, $value, 'Invalid res/images/unexisting-key processing');

        $value = Conf::getConfigValue([
            'res',
            'images',
            'unexisting-key'
        ], 'default');

        $this->assertEquals('default', $value, 'Invalid res/images/unexisting-key processing');

        Conf::setConfigValue('res/images/unexisting-key', 'set-value');

        $value = Conf::getConfigValue([
            'res',
            'images',
            'unexisting-key'
        ]);

        $this->assertEquals('set-value', $value, 'Invalid res/images/unexisting-key value');
    }

    /**
     * Testing setup of the existing array.
     */
    public function testAddComplexExistingArray(): void
    {
        $value = Conf::getConfigValue([
            'res',
            'css'
        ]);

        $this->assertStringContainsString('', $value, 'Invalid css files list');

        Conf::addConfigValue('res/css', 'set-value');

        $value = Conf::getConfigValue([
            'res',
            'css'
        ]);

        $this->assertContains('set-value', $value, 'Invalid css files list');
    }

    /**
     * Testing setup of the unexisting array.
     */
    public function testAddComplexUnexistingArray(): void
    {
        Conf::deleteConfigValue([
            'unexisting-key'
        ]);

        $value = Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertEquals(false, $value);

        Conf::addConfigValue('unexisting-key', 'set-value');

        $value = Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertContains('set-value', $value);
    }

    /**
     * Testing setup of the unexisting array with simple route.
     */
    public function testAddUnexistingArray(): void
    {
        Conf::deleteConfigValue([
            'unexisting-key'
        ]);

        $value = Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertEquals(false, $value);

        Conf::addConfigValue('unexisting-key', 'set-value');

        $value = Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertContains('set-value', $value);
    }

    /**
     * Testing setup of the existing array with simple route.
     */
    public function testAddExistingArray(): void
    {
        Conf::addConfigValue('unexisting-key', 'set-value-1');
        Conf::addConfigValue('unexisting-key', 'set-value-2');

        $value = Conf::getConfigValue([
            'unexisting-key'
        ]);

        $this->assertContains('set-value-2', $value);
    }

    /**
     * Testing setup of the existing array with simple route.
     */
    public function testComplexStringRoutes(): void
    {
        Conf::setConfigValue('f1/f2/unexisting-key', 'set-value-1');

        $value = Conf::getConfigValue('f1/f2/unexisting-key');

        $this->assertEquals('set-value-1', $value);
    }

    /**
     * Deleting simple key.
     */
    public function testDeleteFirstValue(): void
    {
        Conf::setConfigValue('key-1', 'value');

        $value = Conf::getConfigValue('key-1');

        $this->assertEquals('value', $value, 'Invalid setting value');

        Conf::deleteConfigValue('key-1');

        $value = Conf::getConfigValue('key-1', false);

        $this->assertEquals(false, $value);
    }

    /**
     * Deleting deep key.
     */
    public function testDeleteNextValue(): void
    {
        Conf::setConfigValue('key-2/key-3', 'value');

        $value = Conf::getConfigValue('key-2/key-3');

        $this->assertEquals('value', $value, 'Invalid setting value');

        Conf::deleteConfigValue('key-2/key-3');

        $value = Conf::getConfigValue('key-2/key-3', false);

        $this->assertEquals(false, $value);
    }

    /**
     * Deleting empty keys.
     */
    public function testDeleteEmptyKeys(): void
    {
        Conf::setConfigValue('key-4/key-5', 'value');

        Conf::deleteConfigValue('key-4/key-5');

        $value = Conf::getConfigValue('key-4', false);

        $this->assertEquals(false, $value);
    }

    /**
     * Testing delete results.
     */
    public function testDeleteResult(): void
    {
        Conf::setConfigValue('key-9/key-10', 'value');

        // deleting unexisting value
        $result = Conf::deleteConfigValue('key-9/key-unexisting');

        $this->assertEquals(false, $result, 'Invalid deleting result');

        // deleting existing value
        $result = Conf::deleteConfigValue('key-9/key-10');

        $this->assertEquals(true, $result, 'Invalid deleting result');
    }

    /**
     * Testing fas BD setup.
     */
    public function testFastDbSetup(): void
    {
        Conf::addConnectionToConfig('connection', 'dsn', 'user', 'password');

        $value = Conf::getConfigValue('connection/dsn', false);
        $this->assertEquals('dsn', $value, 'Key connection/dsn was not found');

        $value = Conf::getConfigValue('connection/user', false);
        $this->assertEquals('user', $value, 'Key connection/user was not found');

        $value = Conf::getConfigValue('connection/password', false);
        $this->assertEquals('password', $value, 'Key connection/password was not found');
    }
}
