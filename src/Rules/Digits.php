<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Digits
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Digits
 */
class Digits extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.digits';

    /**
     * @var array
     */
    protected $fillableParams = ['length'];

    /**
     * @param $value
     * @return bool
     * @throws \Coccoc\Validation\Exceptions\ParameterException
     */
    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $length = (int)$this->parameter('length');

        return !preg_match('/[^0-9]/', (string)$value) && strlen((string)$value) == $length;
    }
}
