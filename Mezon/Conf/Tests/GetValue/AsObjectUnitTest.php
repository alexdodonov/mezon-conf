<?php
namespace Mezon\Conf\Tests\GetValue;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AsObjectUnitTest extends TestCase
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
     * Testing method getValueAsObject
     */
    public function testGetValueAsObject(): void
    {
        // setup
        Conf::setConfigObjectValue('key', $expected = new \stdClass());

        // test body and assertions
        $this->assertEquals($expected, Conf::getValueAsObject('key'));
    }

    /**
     * Testing method getValueAsObject
     */
    public function testGetValueAsObjectDefault(): void
    {
        // test body and assertions
        $this->assertEquals(null, Conf::getValueAsObject('unexisting'));
    }

    /**
     * Testing exception getValueAsObject
     */
    public function testExceptionGetValueAsObject(): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage('Value is not an object: unexisting');

        // setup
        Conf::setConfigValue('unexisting', '1');

        // test body
        Conf::getValueAsObject('unexisting');
    }
}
