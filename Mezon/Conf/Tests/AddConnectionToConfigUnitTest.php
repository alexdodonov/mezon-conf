<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AddConnectionToConfigUnitTest extends TestCase
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
     * Testing fas BD setup.
     */
    public function testFastDbSetup(): void
    {
        Conf::addConnectionToConfig('connection', 'dsn', 'user', 'password');

        $value = Conf::getConfigValueAsString('connection/dsn', '');
        $this->assertEquals('dsn', $value, 'Key connection/dsn was not found');

        $value = Conf::getConfigValueAsString('connection/user', '');
        $this->assertEquals('user', $value, 'Key connection/user was not found');

        $value = Conf::getConfigValueAsString('connection/password', '');
        $this->assertEquals('password', $value, 'Key connection/password was not found');
    }
}
