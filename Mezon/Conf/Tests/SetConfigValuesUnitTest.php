<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SetConfigValuesUnitTest extends TestCase
{

    /**
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {
        // TODO move to the base class
        Conf::clear();
    }

    /**
     * Testing method setConfigValues
     */
    public function testSetConfigValues(): void
    {
        // setupa and test body
        Conf::setConfigValues([
            'setting1' => 'value1',
            'setting2' => 'value2'
        ]);

        // assertions
        $this->assertEquals('value1', Conf::getConfigValue('setting1'));
        $this->assertEquals('value2', Conf::getConfigValue('setting2'));
    }
}
