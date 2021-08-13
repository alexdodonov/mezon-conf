<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetExpandedStringUnitTest extends TestCase
{

    /**
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {
        Conf::clear();
    }

    /**
     * Testing method getExpandedConfigValue
     */
    public function testGetExpandedString(): void
    {
        // setup
        Conf::setConfigValue('some-var', 'some {var}');
        Conf::setConfigValue('var', 'var');

        // test body
        $result = Conf::getExpandedConfigValue('some-var');

        // assertions
        $this->assertEquals('some var', $result);
    }

    /**
     * Testing method getExpandedConfigValue for unexisting
     */
    public function testGetExpandedStringUnexistingSubvar(): void
    {
        // setup
        Conf::setConfigValue('some-var', 'some {var}');

        // test body
        $result = Conf::getExpandedConfigValue('some-var');

        // assertions
        $this->assertEquals('some {var}', $result);
    }

    /**
     * Testing method getExpandedConfigValue for unexisting
     */
    public function testGetExpandedStringUnexistingVar(): void
    {
        // test body
        $result = Conf::getExpandedConfigValue('some-var');

        // assertions
        $this->assertFalse($result);
    }
}
