<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class LoadConfigFromJsonUnitTest extends TestCase
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
     * Testing method loadConfigFromJson
     */
    public function testLoadConfigFromJson(): void
    {
        // setup and test body
        Conf::loadConfigFromJson(__DIR__ . '/Data/Conf.json');

        // assertions
        $this->assertEquals('value', Conf::getConfigValueAsString('route'));
    }
}
