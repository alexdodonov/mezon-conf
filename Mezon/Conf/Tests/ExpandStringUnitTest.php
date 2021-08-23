<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ExpandStringUnitTest extends TestCase
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
     * Testing method expandString
     */
    public function testExpandString(): void
    {
        // setup
        Conf::setConfigValue('var', 'val');

        // test body and assertions
        $this->assertEquals('val', Conf::expandString('{var}'));
    }
}
