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
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        Conf::clear();
    }

    /**
     * Testing method getExpandedConfigValueAsString
     */
    public function testGetExpandedString(): void
    {
        // setup
        Conf::setConfigValue('@app-http-path', 'app-path');
        Conf::setConfigValue('@mezon-http-path', 'mezon-path');

        Conf::setConfigValue('some-var', 'some {var} @app-http-path @mezon-http-path');
        Conf::setConfigValue('var', 'var');

        // test body
        $result = Conf::getConfigValueAsString('some-var');

        // assertions
        $this->assertEquals('some var app-path mezon-path', $result);
    }

    /**
     * Testing method getExpandedConfigValueAsString for unexisting
     */
    public function testGetExpandedStringUnexistingSubvar(): void
    {
        // setup
        Conf::setConfigValue('some-var', 'some {var}');

        // test body
        $result = Conf::getConfigValueAsString('some-var');

        // assertions
        $this->assertEquals('some {var}', $result);
    }

    /**
     * Testing method getExpandedConfigValue for unexisting
     */
    public function testGetExpandedStringUnexistingVar(): void
    {
        // test body
        $result = Conf::getConfigValueAsString('some-var');

        // assertions
        $this->assertEquals('', $result);
    }
}
