<?php
namespace Mezon\Conf\Tests\Expand;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class StringValueUnitTest extends TestCase
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
     * Testing method expandStringValue
     */
    public function testExpandString(): void
    {
        // setup
        Conf::setConfigValue('var', 'val');

        // test body and assertions
        $this->assertEquals('val', Conf::expandStringValue('{var}'));
    }
}
