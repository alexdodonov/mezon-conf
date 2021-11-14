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
     * Testing method setConfigValue
     */
    public function testSetConfigValue(): void
    {
        // setup
        Conf::setConfigValue('f', $f = new \stdClass());

        // test body and assertions
        $this->assertEquals($f, Conf::getConfigValueAsObject('f'));
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
        Conf::setConfigValue('key', true);
    }
}
