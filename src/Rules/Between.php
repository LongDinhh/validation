<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Behaviours\CanObtainSizeValue;

/**
 * Class Between
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Between
 */
class Between extends Rule
{
    use CanObtainSizeValue;

    /**
     * @var string
     */
    protected $message = 'rule.between';

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

        $min = $this->getSizeInBytes($this->parameter('min'));
        $max = $this->getSizeInBytes($this->parameter('max'));

        $valueSize = $this->getValueSize($value);

        if (!is_numeric($valueSize)) {
            return false;
        }

        return ($valueSize >= $min && $valueSize <= $max);
    }
}
