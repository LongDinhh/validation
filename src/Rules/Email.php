<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Email
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Email
 */
class Email extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.email';

    /**
     * @param $value
     * @return bool
     */
    public function check($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
