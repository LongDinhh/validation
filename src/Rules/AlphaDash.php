<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class AlphaDash
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\AlphaDash
 */
class AlphaDash extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.alpha_dash';

    /**
     * @param $value
     * @return bool
     */
    public function check($value): bool
    {
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        return preg_match('/^[\pL\pM\pN_-]+$/u', $value) > 0;
    }
}
