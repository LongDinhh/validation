<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class TypeFloatRule
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\TypeFloatRule
 */
class TypeFloat extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.float';

    public function check($value): bool
    {
        // https://www.php.net/manual/en/function.is-float.php#117304
        if (!is_scalar($value)) {
            return false;
        }

        if ('double' === gettype($value)) {
            return true;
        } else {
            return preg_match('/^\\d+\\.\\d+$/', (string)$value) === 1;
        }
    }
}
