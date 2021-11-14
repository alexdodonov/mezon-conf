<?php
namespace Mezon\Conf\Tests\Expand;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ArrayValueUnitTest extends TestCase
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
     * Testing method expandArrayValue
     */
    public function testExpandArray(): void
    {
        // setup
        Conf::setConfigValue('var', 'val');

        // test body and assertions
        $this->assertEquals([
            'unchanged',
            'val'
        ], Conf::expandArrayValue([
            'unchanged',
            '{var}'
        ]));
    }
}
