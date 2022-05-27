<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Numeric
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Numeric
 */
class Numeric extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.numeric';

    public function check($value): bool
    {
        return is_numeric($value);
    }
}
