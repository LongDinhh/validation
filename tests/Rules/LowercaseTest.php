<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Rules;

use Coccoc\Validation\Rules\Lowercase;
use PHPUnit\Framework\TestCase;

class LowercaseTest extends TestCase
{

    public function setUp(): void
    {
        $this->rule = new Lowercase;
    }

    public function testValids()
    {
        $this->assertTrue($this->rule->check('username'));
        $this->assertTrue($this->rule->check('full name'));
        $this->assertTrue($this->rule->check('full_name'));
    }

    public function testInvalids()
    {
        $this->assertFalse($this->rule->check('USERNAME'));
        $this->assertFalse($this->rule->check('Username'));
        $this->assertFalse($this->rule->check('userName'));
    }
}
