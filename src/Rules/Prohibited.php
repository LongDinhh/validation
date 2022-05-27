<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class ProhibitedRule
 *
 * Based on Laravel validators prohibited
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\ProhibitedRule
 */
class Prohibited extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.prohibited';

    public function check($value): bool
    {
        return false;
    }
}
