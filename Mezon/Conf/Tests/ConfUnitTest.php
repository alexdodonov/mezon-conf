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
     * @see TestCase::setUp()
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
        $value = Conf::getConfigValueAsString('@app-http-path');

        $this->assertEquals(false, $value, 'Invalid @app-http-path value');

        Conf::setConfigValue('@app-http-path', 'set-value');

        $value = Conf::getConfigValueAsString('@app-http-path');

        $this->assertEquals('set-value', $value, 'Invalid @app-http-path value');
    }

    /**
     * Testing setup of the unexisting key
     * It's value must be overwritten
     */
    public function testSetUnexistingKey(): void
    {
        $this->assertEquals('', Conf::getConfigValueAsString('unexisting-key'));

        Conf::setConfigValue('unexisting-key', 'set-value');

        $this->assertEquals('set-value', Conf::getConfigValueAsString('unexisting-key'));
    }

    /**
     * Testing setup of the unexisting key with complex route
     * It's value must be overwritten
     */
    public function testSetComplexUnexistingKey(): void
    {
        $value = Conf::getConfigValueAsString('res/images/unexisting-key');

        $this->assertEquals('', $value);

        $value = Conf::getConfigValueAsString('res/images/unexisting-key', 'default');

        $this->assertEquals('default', $value);

        Conf::setConfigValue('res/images/unexisting-key', 'set-value');

        $value = Conf::getConfigValueAsString('res/images/unexisting-key');

        $this->assertEquals('set-value', $value);
    }

    /**
     * Testing setup of the existing array.
     */
    public function testAddComplexExistingArray(): void
    {
        $value = Conf::getConfigValueAsString('res/css');

        $this->assertStringContainsString('', $value);

        Conf::addConfigValue('res/css', 'set-value');

        $value = Conf::getConfigValueAsArray('res/css');

        $this->assertContains('set-value', $value);
    }

    /**
     * Testing setup of the unexisting array.
     */
    public function testAddComplexUnexistingArray(): void
    {
        Conf::deleteConfigValue('unexisting-key');

        $value = Conf::getConfigValueAsArray('unexisting-key');

        $this->assertEquals([], $value);

        Conf::addConfigValue('unexisting-key', 'set-value');

        $value = Conf::getConfigValueAsArray('unexisting-key');

        $this->assertContains('set-value', $value);
    }

    /**
     * Testing setup of the unexisting array with simple route.
     */
    public function testAddUnexistingArray(): void
    {
        Conf::deleteConfigValue('unexisting-key');

        $value = Conf::getConfigValueAsArray('unexisting-key');

        $this->assertEquals([], $value);

        Conf::addConfigValue('unexisting-key', 'set-value');

        $value = Conf::getConfigValueAsArray('unexisting-key');

        $this->assertContains('set-value', $value);
    }

    /**
     * Testing setup of the existing array with simple route.
     */
    public function testAddExistingArray(): void
    {
        Conf::addConfigValue('unexisting-key', 'set-value-1');
        Conf::addConfigValue('unexisting-key', 'set-value-2');

        $value = Conf::getConfigValueAsArray('unexisting-key');

        $this->assertContains('set-value-2', $value);
    }

    /**
     * Testing setup of the existing array with simple route.
     */
    public function testComplexStringRoutes(): void
    {
        Conf::setConfigValue('f1/f2/unexisting-key', 'set-value-1');

        $value = Conf::getConfigValueAsString('f1/f2/unexisting-key');

        $this->assertEquals('set-value-1', $value);
    }
}
