<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

/**
 * Class Sometimes
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Sometimes
 */
class Sometimes extends Required
{
    public function check($value): bool
    {
        return true;
    }
}
