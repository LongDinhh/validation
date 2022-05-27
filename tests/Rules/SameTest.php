<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Coccoc\Validation\Factory;

/**
 * Class SameTest
 *
 * @package    Coccoc\Validation\Tests
 * @subpackage Coccoc\Validation\Tests\Rules\SameTest
 */
class SameTest extends TestCase
{
    protected ?Factory $validator = null;

    protected function setUp(): void
    {
        $this->validator = new Factory;
    }

    public function testSameRuleOnArrayAttribute()
    {
        $validation = $this->validator->validate([
            'users' => [
                [
                    'password' => 'foo',
                    'password_confirmation' => 'foo'
                ],
                [
                    'password' => 'foo',
                    'password_confirmation' => 'bar'
                ],
            ]
        ], [
            'users.*.password_confirmation' => 'required|same:users.*.password',
        ]);

        $this->assertFalse($validation->passes());

        $errors = $validation->errors();
        $this->assertNull($errors->first('users.0.password_confirmation:same'));
        $this->assertNotNull($errors->first('users.1.password_confirmation:same'));
    }
}
