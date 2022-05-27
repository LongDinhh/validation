<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Coccoc\Validation\Factory;

/**
 * Class NumbersTest
 *
 * @package    Coccoc\Validation\Tests
 * @subpackage Coccoc\Validation\Tests\Rules\NumbersTest
 */
class NumbersTest extends TestCase
{
    protected ?Factory $validator = null;

    protected function setUp(): void
    {
        $this->validator = new Factory;
    }

    public function testNumericStringSizeWithoutNumericRule()
    {
        $validation = $this->validator->validate([
            'number' => '1.2345'
        ], [
            'number' => 'max:2',
        ]);

        $this->assertFalse($validation->passes());
    }

    public function testNumericStringSizeWithNumericRule()
    {
        $validation = $this->validator->validate([
            'number' => '1.2345'
        ], [
            'number' => 'numeric|max:2',
        ]);

        $this->assertTrue($validation->passes());
    }
}
