<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class TypeArray
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\TypeArray
 */
class TypeArray extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.array';

    public function check($value): bool
    {
        return is_array($value);
    }
}
