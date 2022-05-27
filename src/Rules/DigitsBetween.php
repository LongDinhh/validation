<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class DigitsBetween
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\DigitsBetween
 */
class DigitsBetween extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.digits_between';

    /**
     * @var array
     */
    protected $fillableParams = ['min', 'max'];

    /**
     * @param $value
     * @return bool
     * @throws \Coccoc\Validation\Exceptions\ParameterException
     */
    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $min = (int)$this->parameter('min');
        $max = (int)$this->parameter('max');

        $length = strlen((string)$value);

        return !preg_match('/[^0-9]/', (string)$value) && $length >= $min && $length <= $max;
    }
}
