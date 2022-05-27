<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests\Fixtures;

use Coccoc\Validation\Rule;

/**
 * Class Required
 *
 * @package    Coccoc\Validation\Tests\Fixtures
 * @subpackage Coccoc\Validation\Tests\Fixtures\Required
 */
class Required extends Rule
{
    public function check($value): bool
    {
        return true;
    }
}
