<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;
use function in_array;

/**
 * Class TypeBoolean
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\TypeBoolean
 */
class TypeBoolean extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.boolean';

    public function check($value): bool
    {
        return in_array($value, [true, false, "true", "false", 1, 0, "0", "1", "y", "n"], true);
    }
}
