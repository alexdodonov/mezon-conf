<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SetConfigValueUnitTest extends TestCase
{

    /**
     *
     * {@inheritdoc}
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        // TODO move to the base class
        Conf::clear();
    }

    /**
     * Testing method setConfigValue for object
     */
    public function testSetConfigValueForObject(): void
    {
        // setup
        Conf::setConfigValue('f', $f = new \stdClass());

        // test body and assertions
        $this->assertEquals($f, Conf::getConfigValueAsObject('f'));
    }

    /**
     * Testing method setConfigValue for bool
     */
    public function testSetConfigValueForBool(): void
    {
        // setup
        Conf::setConfigValue('f', true);

        // test body and assertions
        $this->assertTrue(Conf::getValue('f'));
    }

    /**
     * Testing exception in setConfigValue
     */
    public function testExceptionSetConfigValue(): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage('Unsupported value type');

        // test body
        Conf::setConfigValue('key', 1.1);
    }
}
