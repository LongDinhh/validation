<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class TypeInteger
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\TypeInteger
 */
class TypeInteger extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.integer';

    public function check($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
}
