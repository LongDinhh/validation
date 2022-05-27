<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class TypeStringRule
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\TypeStringRule
 */
class TypeString extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.string';

    public function check($value): bool
    {
        return is_string($value);
    }
}
