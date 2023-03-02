<?php
namespace Mezon\Conf\Tests\GetValue;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AsBoolUnitTest extends TestCase
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
     * Testing method getValueAsBool
     */
    public function testGetValueAsBool(): void
    {
        // setup
        Conf::setConfigBoolValue('key', true);

        // test body and assertions
        $this->assertTrue(Conf::getValueAsBool('key'));
    }

    /**
     * Testing method getValueAsBool
     */
    public function testGetValueAsBoolDefault(): void
    {
        // test body and assertions
        $this->assertFalse(Conf::getValueAsBool('unexisting'));
    }

    /**
     * Testing exception getValueAsBool
     */
    public function testExceptionGetValueAsBool(): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage('Value is not a bool: key');

        // setup
        Conf::setConfigValue('key', []);

        // test body
        Conf::getValueAsBool('key');
    }
}
