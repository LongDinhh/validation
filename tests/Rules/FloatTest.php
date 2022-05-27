<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Coccoc\Validation\Factory;
use Coccoc\Validation\Rules\TypeFloat;

/**
 * Class FloatTest
 *
 * @package    Coccoc\Validation\Tests\Rules
 * @subpackage Coccoc\Validation\Tests\Rules\FloatTest
 */
class FloatTest extends TestCase
{
    public function testFloat()
    {
        $validator = new Factory();
        $validator->addRule('float', new TypeFloat());

        $res = $validator->validate(
            [
                'foo' => 'bar',
                'bar' => 1.121,
                'baz' => '1.121',
            ],
            [
                'foo' => 'float',
                'bar' => 'float',
                'baz' => 'float',
            ],
        );

        $this->assertFalse($res->passes());

        $this->assertArrayNotHasKey('bar', $res->errors()->toArray());
        $this->assertArrayNotHasKey('baz', $res->errors()->toArray());
    }

    public function testFloatWithLongFloats()
    {
        $validator = new Factory();
        $validator->addRule('float', new TypeFloat());

        $res = $validator->validate(
            [
                'long'  => '3.1415926535897932384626433832795',
                'long2' => 3.1415926535897932384626433832795,
            ],
            [
                'long'  => 'float',
                'long2' => 'float',
            ],
        );

        $this->assertTrue($res->passes());
    }
}
