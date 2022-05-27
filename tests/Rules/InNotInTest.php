<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Coccoc\Validation\Factory;

/**
 * Class InNotInTest
 *
 * @package    Coccoc\Validation\Tests
 * @subpackage Coccoc\Validation\Tests\Rules\InNotInTest
 */
class InNotInTest extends TestCase
{
    protected ?Factory $validator = null;

    protected function setUp(): void
    {
        $this->validator = new Factory;
    }

    public function testRuleInInvalidMessages()
    {
        $validation = $this->validator->validate([
            'number' => 1
        ], [
            'number' => 'in:7,8,9',
        ]);

        $this->assertEquals('number must be one of "7", "8", "9"', $validation->errors()->first('number'));
    }

    public function testRuleNotInInvalidMessages()
    {
        $validation = $this->validator->validate([
            'number' => 1
        ], [
            'number' => 'not_in:1,2,3',
        ]);

        $this->assertEquals('number must not be one of "1", "2", "3"', $validation->errors()->first('number'));
    }
}
