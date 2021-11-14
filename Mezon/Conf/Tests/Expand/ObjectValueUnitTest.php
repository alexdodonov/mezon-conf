<?php
namespace Mezon\Conf\Tests\Expand;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ObjectValueUnitTest extends TestCase
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
     * Testing method expandObjectValue
     */
    public function testExpandObject(): void
    {
        // setup
        Conf::setConfigValue('var', 'val');

        // test body
        $obj = new \stdClass();
        $obj->field = '{var}';
        /** @var object{field:string} $obj */
        $obj = Conf::expandObjectValue($obj);

        // assertions
        $this->assertEquals('val', $obj->field);
    }
}
