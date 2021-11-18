<?php
namespace Mezon\Conf\Tests\GetValue;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class BoolValueUnitTest extends TestCase
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
     * Testing method setConfigBoolValue
     */
    public function testSetConfigBoolValue(): void
    {
        // setup
        Conf::setConfigBoolValue('b', true);

        // test body and assertions
        $this->assertTrue(Conf::getValue('b'));
    }
}
