<?php
namespace Mezon\Conf\Tests\GetValue;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AsStringUnitTest extends TestCase
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
     * Testing method getValueAsString
     */
    public function testGetValueAsString(): void
    {
        // setup
        Conf::setConfigStringValue('key', 'str');

        // test body and assertions
        $this->assertEquals('str', Conf::getValueAsString('key'));
    }

    /**
     * Testing method getValueAsString
     */
    public function testGetValueAsStringDefault(): void
    {
        // test body and assertions
        $this->assertEquals('', Conf::getValueAsString('unexisting'));
    }

    /**
     * Testing exception getValueAsString
     */
    public function testExceptionGetValueAsString(): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage('Value is not a string: key');

        // setup
        Conf::setConfigValue('key', []);

        // test body
        Conf::getValueAsString('key');
    }
}
