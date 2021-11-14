<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ConfigKeyExistsUnitTest extends TestCase
{

    // TODO move to the base class

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
     * Testing for key existence
     */
    public function testConfigKeyExists(): void
    {
        // setup
        Conf::setConfigValue('existing/key', 'value');

        // test body and assertions
        $this->assertTrue(Conf::configKeyExists('existing/key'));
        $this->assertTrue(Conf::configKeyExists([
            'existing',
            'key'
        ]));
        $this->assertFalse(Conf::configKeyExists('unexisting'));
    }
}
