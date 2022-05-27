<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Behaviours\CanObtainSizeValue;

/**
 * Class Min
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Min
 */
class Min extends Rule
{
    use CanObtainSizeValue;

    /**
     * @var string
     */
    protected $message = 'rule.min';

    /**
     * @var array
     */
    protected $fillableParams = ['min'];

    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $min = $this->getSizeInBytes($this->parameter('min'));
        $valueSize = $this->getValueSize($value);

        if (!is_numeric($valueSize)) {
            return false;
        }

        return $valueSize >= $min;
    }
}
