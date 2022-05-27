<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Nullable
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Nullable
 */
class Nullable extends Rule
{
    public function check($value): bool
    {
        return true;
    }
}
