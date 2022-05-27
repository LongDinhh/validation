<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class AlphaNum
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\AlphaNum
 */
class AlphaNum extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.alpha_num';

    /**
     * @param $value
     * @return bool
     */
    public function check($value): bool
    {
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        return preg_match('/^[\pL\pM\pN]+$/u', $value) > 0;
    }
}
