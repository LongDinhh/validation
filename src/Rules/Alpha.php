<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Alpha
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Alpha
 */
class Alpha extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.alpha';

    /**
     * @param $value
     * @return bool
     */
    public function check($value): bool
    {
        return is_string($value) && preg_match('/^[\pL\pM]+$/u', $value);
    }
}
