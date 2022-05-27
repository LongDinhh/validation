<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Coccoc\Validation\Rules\Rejected;

class RejectedTest extends TestCase
{
    public function setUp(): void
    {
        $this->rule = new Rejected();
    }

    public function testValids()
    {
        $this->assertTrue($this->rule->check('no'));
        $this->assertTrue($this->rule->check('off'));
        $this->assertTrue($this->rule->check('0'));
        $this->assertTrue($this->rule->check(0));
        $this->assertTrue($this->rule->check(false));
        $this->assertTrue($this->rule->check('false'));
    }

    public function testInvalids()
    {
        $this->assertFalse($this->rule->check(''));
        $this->assertFalse($this->rule->check('of'));
        $this->assertFalse($this->rule->check(' 0'));
        $this->assertFalse($this->rule->check(10));
    }
}
