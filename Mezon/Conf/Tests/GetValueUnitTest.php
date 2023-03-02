<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetValueUnitTest extends TestCase
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
     * Testing method getValue
     */
    public function testGetValue(): void
    {
        // setup
        Conf::setConfigBoolValue('bool', true);
        Conf::setConfigStringValue('string', $expectedString = 'str');
        Conf::setConfigObjectValue('object', $expectedObject = new \stdClass());
        Conf::setConfigArrayValue('array', $expectedArray = [
            1,
            2
        ]);

        // test body and assertions
        $this->assertEquals($expectedString, Conf::getValue('string'));
        $this->assertEquals($expectedObject, Conf::getValue('object'));
        $this->assertEquals($expectedArray, Conf::getValue('array'));
        $this->assertTrue(Conf::getValue('bool'));
    }

    /**
     * Testing method getValue with default value
     */
    public function testGetDefaultValue(): void
    {
        // test body
        /** @var int $result */
        $result = Conf::getValue('unexisting', 123);

        // assertions
        $this->assertEquals($result, 123);
    }
}