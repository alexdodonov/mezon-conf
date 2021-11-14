<?php
namespace Mezon\Conf\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class DeleteConfigValueUnitTest extends TestCase
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
     * Deleting simple key.
     */
    public function testDeleteFirstValue(): void
    {
        Conf::setConfigValue('key-1', 'value');

        $value = Conf::getConfigValueAsString('key-1');

        $this->assertEquals('value', $value);

        Conf::deleteConfigValue('key-1');

        $value = Conf::getConfigValueAsString('key-1', '');

        $this->assertEquals('', $value);
    }

    /**
     * Deleting deep key.
     */
    public function testDeleteNextValue(): void
    {
        Conf::setConfigValue('key-2/key-3', 'value');

        $value = Conf::getConfigValueAsString('key-2/key-3');

        $this->assertEquals('value', $value);

        Conf::deleteConfigValue('key-2/key-3');

        $value = Conf::getConfigValueAsString('key-2/key-3', '');

        $this->assertEquals('', $value);
    }

    /**
     * Deleting empty keys.
     */
    public function testDeleteEmptyKeys(): void
    {
        Conf::setConfigValue('key-4/key-5', 'value');

        Conf::deleteConfigValue('key-4/key-5');

        $value = Conf::getConfigValueAsString('key-4', '');

        $this->assertEquals('', $value);
    }

    /**
     * Testing delete results
     */
    public function testDeleteResult(): void
    {
        Conf::setConfigValue('key-9/key-10', 'value');

        // deleting unexisting value
        $result = Conf::deleteConfigValue('key-9/key-unexisting');

        $this->assertEquals(false, $result);

        // deleting existing value
        $result = Conf::deleteConfigValue('key-9/key-10');

        $this->assertEquals(true, $result);
    }
}
