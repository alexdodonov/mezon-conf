<?php
namespace Mezon\Conf\Tests\Expand;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class StringUnitTest extends TestCase
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
     * Testing method expandString
     */
    public function testExpandString(): void
    {
        // setup
        Conf::setConfigValue('var', 'val');

        // test body and assertions
        $this->assertEquals('val', Conf::expandString('{var}'));
    }

    /**
     * Testing method expandString
     */
    public function testExpandArray(): void
    {
        // setup
        Conf::setConfigValue('var', 'val');

        // test body and assertions
        $this->assertEquals([
            'val'
        ], Conf::expandString([
            '{var}'
        ]));
    }

    /**
     * Testing method expandString
     */
    public function testExpandObject(): void
    {
        // setup
        Conf::setConfigValue('var', 'val');

        // test body
        $obj = new \stdClass();
        $obj->field = '{var}';
        /** @var object{field:string} $obj */
        $obj = Conf::expandString($obj);

        // assertions
        $this->assertEquals('val', $obj->field);
    }
}
