<?php
namespace Mezon\Conf\Tests\GetValue;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AsArrayUnitTest extends TestCase
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
     * Testing method getValueAsArray
     */
    public function testGetValueAsArray(): void
    {
        // setup
        Conf::setConfigArrayValue('key', []);

        // test body and assertions
        $this->assertEquals([], Conf::getValueAsArray('key'));
    }

    /**
     * Testing method getValueAsArray
     */
    public function testGetValueAsArrayDefault(): void
    {
        // test body and assertions
        $this->assertEquals([
            1,
            2
        ], Conf::getValueAsArray('unexisting', [
            1,
            2
        ]));
    }

    /**
     * Testing exception getValueAsArray
     */
    public function testExceptionGetValueAsArray(): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage('Value is not an array: key');

        // setup
        Conf::setConfigValue('key', '');

        // test body
        Conf::getValueAsArray('key');
    }
}
