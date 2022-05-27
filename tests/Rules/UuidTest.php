<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Coccoc\Validation\Factory;

/**
 * Class UuidTest
 *
 * @package    Coccoc\Validation\Tests\Rules
 * @subpackage Coccoc\Validation\Tests\Rules\UuidTest
 */
class UuidTest extends TestCase
{
    public function testUuid()
    {
        $validator = new Factory();

        $res = $validator->validate(
            [
                'foo' => '86e51afc-c626-4b28-999f-560a297d019f',
            ],
            [
                'foo' => 'uuid',
            ],
        );

        $this->assertTrue($res->passes());
    }

    public function testFailsOnNullOrNotString()
    {
        $validator = new Factory();

        $res = $validator->validate(
            [
                'foo' => null,
            ],
            [
                'foo' => 'uuid',
            ],
        );

        $this->assertTrue($res->fails());

        $res = $validator->validate(
            [
                'bar' => '',
            ],
            [
                'bar' => 'uuid',
            ],
        );

        $this->assertTrue($res->fails());
    }

    public function testFailsForNIL()
    {
        $validator = new Factory();

        $res = $validator->validate(
            [
                'foo' => Uuid::NIL,
            ],
            [
                'foo' => 'uuid',
            ],
        );

        $this->assertTrue($res->fails());
    }
}
