<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Rules;

use Coccoc\Validation\Rules\TypeInteger;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{

    public function setUp(): void
    {
        $this->rule = new TypeInteger;
    }

    public function testValids()
    {
        $this->assertTrue($this->rule->check(0));
        $this->assertTrue($this->rule->check('0'));
        $this->assertTrue($this->rule->check('123'));
        $this->assertTrue($this->rule->check('-123'));
        $this->assertTrue($this->rule->check(123));
        $this->assertTrue($this->rule->check(-123));
    }

    public function testInvalids()
    {
        $this->assertFalse($this->rule->check('foo123'));
        $this->assertFalse($this->rule->check('123foo'));
        $this->assertFalse($this->rule->check([123]));
        $this->assertFalse($this->rule->check('123.456'));
        $this->assertFalse($this->rule->check('-123.456'));
    }
}
