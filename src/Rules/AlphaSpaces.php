<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class AlphaSpaces
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\AlphaSpaces
 */
class AlphaSpaces extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.alpha_spaces';

    /**
     * @param $value
     * @return bool
     */
    public function check($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return preg_match('/^[\pL\pM\s]+$/u', $value) > 0;
    }
}
