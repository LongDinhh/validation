<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Fixtures;

use Coccoc\Validation\Rule;

/**
 * Class Unique
 *
 * @package    Coccoc\Validation\Tests\Fixtures
 * @subpackage Coccoc\Validation\Tests\Fixtures\Unique
 */
class Unique extends Rule
{

    /**
     * @var string
     */
    protected $message = "The :attribute must be unique";

    public function check($value): bool
    {
        return false;
    }
}
