<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Fixtures;

use Coccoc\Validation\Rule;

/**
 * Class Even
 *
 * @package    Coccoc\Validation\Tests\Fixtures
 * @subpackage Coccoc\Validation\Tests\Fixtures\Even
 */
class Even extends Rule
{

    /**
     * @var string
     */
    protected $message = "The :attribute must be even";

    public function check($value): bool
    {
        if (! is_numeric($value)) {
            return false;
        }

        return $value % 2 === 0;
    }
}
