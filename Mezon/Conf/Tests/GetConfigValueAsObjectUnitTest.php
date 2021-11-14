<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetConfigValueAsObjectUnitTest extends TestCase
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
     * Testing method getConfigValueAsObject
     */
    public function testGetConfigValueAsObject(): void
    {
        // setup
        Conf::setConfigObjectValue('key', $expected = new \stdClass());

        // test body and assertions
        $this->assertEquals($expected, Conf::getConfigValueAsObject('key'));
    }

    /**
     * Testing method getConfigValueAsObject
     */
    public function testGetConfigValueAsObjectDefault(): void
    {
        // test body and assertions
        $this->assertEquals(null, Conf::getConfigValueAsObject('unexisting'));
    }
}
